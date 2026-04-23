from flask import Flask, request, jsonify
import pandas as pd
import numpy as np
import pickle

app = Flask(__name__)

# LOAD SEMUA FILE
df_products = pd.read_pickle('model/df_products_final.pkl')
implicit_df = pd.read_pickle('model/implicit_df.pkl')

with open('model/als_model.pkl', 'rb') as f:
    model_als = pickle.load(f)

with open('model/tfidf.pkl', 'rb') as f:
    tfidf = pickle.load(f)

content_features = np.load('model/content_features.npy')
text_features = np.load('model/text_features.npy')

# REBUILD MAP
user_ids = implicit_df['user_id'].unique()
item_ids = implicit_df['product_id'].unique()

user_map = {uid: i for i, uid in enumerate(user_ids)}
item_map = {iid: i for i, iid in enumerate(item_ids)}

from scipy.sparse import coo_matrix
rows = implicit_df['user_id'].map(user_map).values
cols = implicit_df['product_id'].map(item_map).values
data = implicit_df['purchase_count'].values.astype(np.float32)

sparse_matrix = coo_matrix((data, (rows, cols)),
                          shape=(len(user_ids), len(item_ids))).tocsr()

# ===== RECOMMEND FUNCTION =====
def recommend_collaborative(user_id, top_k=5):
    if user_id not in user_map:
        return []

    user_idx = user_map[user_id]

    recommended, scores = model_als.recommend(
        userid=user_idx,
        user_items=sparse_matrix[user_idx],
        N=top_k
    )

    items = [item_ids[i] for i in recommended]

    result = df_products[df_products['product_id'].isin(items)].copy()

    return result[['product_id', 'product_name']].to_dict(orient='records')


# API ENDPOINT
@app.route('/recommend', methods=['GET'])
def recommend():
    user_id = request.args.get('user_id', type=int)

    if user_id is None:
        return jsonify({"error": "user_id is required"}), 400

    recs = recommend_collaborative(user_id)

    return jsonify(recs)

@app.route('/')
def home():
    return "API Recommender is running!"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)