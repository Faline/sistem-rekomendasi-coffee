from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import numpy as np
import pickle
import json

import recommendation_api as rec

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

# ======================
# LOAD DATA
# ======================
df_products = pd.read_pickle('model/df_products_final.pkl')
implicit_df = pd.read_pickle('model/implicit_df.pkl')

with open('model/als_model.pkl', 'rb') as f:
    als_model = pickle.load(f)

with open('model/tfidf.pkl', 'rb') as f:
    tfidf = pickle.load(f)

content_features = np.load('model/content_features.npy')
text_features = np.load('model/text_features.npy')


# ======================
# INIT RECOMMENDATION ENGINE
# ======================
rec.init_system(
    df_products,
    implicit_df,
    als_model,
    tfidf,
    content_features,
    text_features
)


# ======================
# RECOMMEND API
# ======================
@app.route('/recommend', methods=['POST'])
def recommend():
    data = request.json
    user_id = data.get("user_id")

    result = rec.hybrid_recommendation(user_id, top_k=5)

    return jsonify(result.to_dict(orient='records'))


# ======================
# POPULAR API (FIXED)
# ======================
@app.route('/popular')
def popular():
    result = rec.get_popular(10)
    return jsonify(result.to_dict('records'))

@app.route("/similar", methods=["POST"])
def similar():
    print("HIT /similar")
    print(request.json)

    data = request.json
    user_id = data["user_id"]

    result = rec.recommend_content_from_history(user_id, top_k=10)

    return jsonify(result.to_dict(orient="records"))


@app.route('/update-interaction', methods=['POST'])
def update_interaction():
    try:
        data = request.get_json(force=True)
        user_id = int(data.get('user_id'))
        product_id = int(data.get('product_id'))
        qty = int(data.get('quantity', 1))

        global implicit_df
        mask = (implicit_df['user_id'] == user_id) & (implicit_df['product_id'] == product_id)

        if mask.any():
            implicit_df.loc[mask, 'purchase_count'] += qty
        else:
            implicit_df = pd.concat([implicit_df, pd.DataFrame([{
                'user_id': user_id,
                'product_id': product_id,
                'purchase_count': qty
            }])], ignore_index=True)

        rec.refresh_data(implicit_df.copy())

        return jsonify({
            "status": "updated",
            "user_id": user_id,
            "product_id": product_id
        })
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500
    

@app.route('/products', methods=['GET'])
def get_products():
    try:
        with open('model/df_products_final.json', 'r', encoding='utf-8') as f:
            products = json.load(f)

        return jsonify(products)

    except Exception as e:
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500

# ======================
@app.route('/')
def home():
    return "API RUNNING"


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)