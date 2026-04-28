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

implicit_df['user_id'] = implicit_df['user_id'].astype(int)
implicit_df['product_id'] = implicit_df['product_id'].astype(int)

with open('model/als_model.pkl', 'rb') as f:
    als_model = pickle.load(f)

with open('model/tfidf.pkl', 'rb') as f:
    tfidf = pickle.load(f)

content_features = np.load('model/content_features.npy')
text_features = np.load('model/text_features.npy')

# ======================
# INIT SYSTEM
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
# USER OFFSET CONFIG
# ======================
OFFSET = 100000

def normalize_user_id(user_id: int):
    """
    FIX UTAMA:
    - kalau user masih kirim Laravel ID (misal 1–99999)
      → ubah ke model ID (100001 dst)
    """
    if user_id < OFFSET:
        return user_id + OFFSET
    return user_id


# ======================
# CREATE USER
# ======================
@app.route('/create-user', methods=['POST'])
def create_user():
    try:
        data = request.get_json(force=True)
        laravel_user_id = int(data.get('user_id'))

        model_user_id = laravel_user_id + OFFSET

        return jsonify({
            "status": "created",
            "model_user_id": model_user_id
        })

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500


# ======================
# REFRESH MODEL
# ======================
@app.route('/refresh-model', methods=['POST'])
def refresh_model():
    try:
        rec.rebuild_system()   # update mapping dulu
        rec.retrain_als()     # baru retrain model

        return jsonify({"status": "ok"})
    except Exception as e:
        return jsonify({"error": str(e)})


# ======================
# RECOMMEND (FIX CORE BUG HERE)
# ======================
@app.route('/recommend', methods=['POST'])
def recommend():
    rec.check_and_retrain() 
    data = request.get_json(force=True)

    raw_user_id = int(data.get("user_id"))
    user_id = normalize_user_id(raw_user_id)

    preferences = data.get("preferences", None)

    print("RAW USER ID:", raw_user_id)
    print("MODEL USER ID:", user_id)
    print("PREFERENCES:", preferences)

    history_count = len(implicit_df[implicit_df['user_id'] == user_id])

    if history_count == 0:
        print("COLD START MODE")
        result = rec.recommend_for_new_user(preferences, top_k=5)
    else:
        print("HYBRID MODE")
        result = rec.hybrid_recommendation(
            user_id,
            preferences=preferences,
            top_k=5
        )

    print("HISTORY COUNT:", history_count)

    return jsonify(result.to_dict(orient='records'))


# ======================
# POPULAR
# ======================
@app.route('/popular')
def popular():
    result = rec.get_popular(10)
    return jsonify(result.to_dict('records'))


# ======================
# SIMILAR
# ======================
@app.route("/similar", methods=["POST"])
def similar():
    data = request.get_json(force=True)

    user_id = normalize_user_id(int(data.get("user_id", 0)))

    result = rec.recommend_content_from_history(user_id, top_k=10)

    if result.empty:
        result = rec.get_popular(10)

    return jsonify(result.to_dict(orient="records"))


# ======================
# UPDATE INTERACTION
# ======================
@app.route('/update-interaction', methods=['POST'])
def update_interaction():
    global implicit_df

    data = request.get_json(force=True)

    user_id = normalize_user_id(int(data.get('user_id')))
    product_id = int(data.get('product_id'))
    qty = int(data.get('quantity', 1))

    mask = (
        (implicit_df['user_id'] == user_id) &
        (implicit_df['product_id'] == product_id)
    )

    if mask.any():
        implicit_df.loc[mask, 'purchase_count'] += qty
    else:
        implicit_df = pd.concat([implicit_df, pd.DataFrame([{
            'user_id': user_id,
            'product_id': product_id,
            'purchase_count': qty
        }])], ignore_index=True)


    rec.refresh_data(implicit_df)
    rec.rebuild_system()   # FORCE SYNC MAP

    print("USER MAP AFTER UPDATE:", user_id in rec.user_map)

    return jsonify({"status": "updated"})

# ======================
# PRODUCTS
# ======================
@app.route('/products')
def get_products():
    try:
        with open('model/df_products_final.json', 'r', encoding='utf-8') as f:
            return jsonify(json.load(f))
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# ======================
@app.route('/')
def home():
    return "API RUNNING"


# ======================
@app.route('/debug')
def debug():
    return jsonify({
        "total_users": len(rec.implicit_df['user_id'].unique()),
        "total_interactions": len(rec.implicit_df)
    })


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)