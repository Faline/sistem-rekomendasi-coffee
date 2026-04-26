import pandas as pd
import numpy as np
from scipy.sparse import coo_matrix
from sklearn.metrics.pairwise import cosine_similarity

# ======================
# GLOBAL STATE
# ======================
df_products = None
implicit_df = None
model_als = None
tfidf = None
content_features = None
text_features = None

user_map = {}
item_map = {}
sparse_matrix = None
user_ids = None
item_ids = None

user_mapping_df = None
model_to_user = {}
user_to_model = {}



# ======================
# INIT SYSTEM
# ======================
def init_system(df_prod, df_imp, als_model, tfidf_model, content_feat, text_feat):
    global df_products, implicit_df, model_als, tfidf
    global content_features, text_features

    df_products = df_prod
    implicit_df = df_imp.copy()

    model_als = als_model
    tfidf = tfidf_model
    content_features = content_feat
    text_features = text_feat
    build_user_mapping() 
    rebuild_system()

def build_user_mapping():
    global user_mapping_df, user_to_model, model_to_user

    unique_users = implicit_df['user_id'].unique()

    user_mapping_df = pd.DataFrame({
        "user_id": unique_users,
        "model_user_id": np.arange(len(unique_users))
    })

    user_to_model = dict(zip(user_mapping_df['user_id'], user_mapping_df['model_user_id']))
    model_to_user = dict(zip(user_mapping_df['model_user_id'], user_mapping_df['user_id']))
# ======================
# REBUILD MATRIX
# ======================
def rebuild_system():
    global user_map, item_map, sparse_matrix, user_ids, item_ids
    
    user_ids = np.sort(implicit_df['user_id'].unique())
    item_ids = np.sort(implicit_df['product_id'].unique())

    user_map = {u: i for i, u in enumerate(user_ids)}
    item_map = {p: i for i, p in enumerate(item_ids)}

    rows = implicit_df['user_id'].map(user_map).values
    cols = implicit_df['product_id'].map(item_map).values
    data = implicit_df['purchase_count'].values.astype(np.float32)

    sparse_matrix = coo_matrix(
        (data, (rows, cols)),
        shape=(len(user_ids), len(item_ids))
    ).tocsr()


# ======================
# REFRESH DATA (IMPORTANT)
# ======================
def refresh_data(new_df):
    global implicit_df
    implicit_df = new_df.copy()
    rebuild_system()



# ======================
# NORMALIZE
# ======================
def normalize(x):
    if len(x) == 0:
        return np.array([])
    if x.max() == x.min():
        return np.zeros(len(x))
    return (x - x.min()) / (x.max() - x.min() + 1e-9)

def get_user_idx(user_id):
    model_user_id = get_model_user_id(user_id)
    if model_user_id is None:
        return None

    return user_map.get(model_user_id)
# ======================
# ALS COLLAB
# ======================
def recommend_collaborative(user_id, top_k=5):
    user_idx = get_user_idx(user_id)

    if user_idx is None:
        return pd.DataFrame()

    rec, scores = model_als.recommend(
        userid=user_idx,
        user_items=sparse_matrix[user_idx],
        N=top_k,
        filter_already_liked_items=False
    )

    items = [item_ids[i] for i in rec]

    result = df_products[df_products['product_id'].isin(items)].copy()
    result['score'] = result['product_id'].map(dict(zip(items, scores)))

    return result

# ======================
# COLD START
# ======================
def recommend_for_new_user(preferences: dict, top_k: int = 5):
    if preferences is None:
            popular = implicit_df.groupby('product_id')['purchase_count'].sum().nlargest(top_k)
            result = df_products[df_products['product_id'].isin(popular.index)].head(top_k).copy()
            result['similarity_score'] = 1.0
            return result[['product_id', 'product_name', 'product_category',
                          'product_type', 'unit_price_idr', 'similarity_score']]

    """Content-Based recommendation untuk user baru"""
    mask_price = df_products['unit_price_idr'] <= preferences.get('max_price_idr', 100000)
    mask_cat = df_products['product_category'].isin(
        preferences.get('categories', df_products['product_category'].unique())
    )
    mask_type = pd.Series(True, index=df_products.index)
    if preferences.get('types'):
        mask_type = df_products['product_type'].isin(preferences['types'])


    final_mask = mask_price & mask_cat & mask_type
    candidates = df_products[final_mask].copy()

    if len(candidates) == 0:
        # Fallback ke popular
        popular = implicit_df.groupby('product_id')['purchase_count'].sum().nlargest(top_k)
        result = df_products[df_products['product_id'].isin(popular.index)].head(top_k).copy()
        result['similarity_score'] = 1.0
        return result[['product_id', 'product_name', 'product_category',
                       'product_type', 'unit_price_idr', 'similarity_score']]

    user_query = ""
    if preferences.get('categories'):
        user_query += " ".join(preferences['categories']) + " "
    if preferences.get('types'):
        user_query += " ".join(preferences.get('types', [])) + " "
    if preferences.get('keywords'):
        user_query += str(preferences['keywords'])
    if not user_query.strip():
      result = candidates.sort_values('unit_price_idr').head(top_k).copy()
      result['similarity_score'] = None
      return result


    query_vec = tfidf.transform([user_query]).toarray() if 'tfidf' in globals() else np.zeros((1, content_features.shape[1]))
    sim_scores = cosine_similarity(query_vec, text_features[candidates.index])[0] if 'text_features' in globals() else np.ones(len(candidates))

    top_indices = sim_scores.argsort()[::-1][:top_k]
    result = candidates.iloc[top_indices][['product_id', 'product_name', 'product_category',
                                           'product_type', 'unit_price_idr']].copy()
    result['similarity_score'] = sim_scores[top_indices].round(4)

    return result

# ======================
# CONTENT BASED HISTORY
# ======================
def recommend_content_from_history(user_id, top_k=10):
    model_user_id = get_model_user_id(user_id)

    if model_user_id is None:
        return pd.DataFrame()

    user_items = implicit_df[
        implicit_df['user_id'] == model_user_id
    ]['product_id']

    if len(user_items) == 0:
        return pd.DataFrame()

    idx = []
    for i in user_items:
        i = int(i)
        if i in item_map:
            idx.append(item_map[i])

    if len(idx) == 0:
        return pd.DataFrame()

    
    full_vec = content_features[idx].mean(axis=0)

    # recent history (5 terakhir)
    recent_items = implicit_df[implicit_df['user_id'] == model_user_id]['product_id'].tail(5)

    recent_idx = []
    for i in recent_items:
        i = int(i)
        if i in item_map:
            recent_idx.append(item_map[i])

    # kalau tidak ada recent, fallback ke full
    if len(recent_idx) == 0:
        user_vec = full_vec
    else:
        recent_vec = content_features[recent_idx].mean(axis=0)

        user_vec = 0.5 * recent_vec + 0.5 * full_vec
    # similarity all items
    sim = cosine_similarity([user_vec], content_features)[0]

    df_score = pd.DataFrame({
        "product_id": df_products["product_id"].values,
        "similarity_score": sim
    })
    result = df_score.merge(df_products, on="product_id", how="left")

    return result.sort_values("similarity_score", ascending=False).head(top_k)

# ======================
# HYBRID (FIXED LOGIC)
# ======================
def hybrid_recommendation(user_id, preferences=None, top_k=5):
    model_user_id = get_model_user_id(user_id)
    print("MODEL USER:", get_model_user_id(user_id))
    print("HAS ALS IDX:", get_user_idx(user_id) is not None)
    if model_user_id is None:
        if preferences is not None:
            return recommend_for_new_user(preferences, top_k)
        return get_popular(top_k)

    user_history = implicit_df[implicit_df['user_id'] == model_user_id]
    recent_items = user_history.sort_index().tail(5)['product_id'].values

    collab = recommend_collaborative(user_id, top_k * 2)
    content = recommend_content_from_history(user_id, top_k * 2)

    if collab.empty and content.empty:
        return get_popular(top_k)

    if not collab.empty:
        collab['norm_score'] = normalize(collab['score'])
    else:
        collab = pd.DataFrame(columns=['product_id', 'norm_score'])

    if not content.empty:
        content['norm_score'] = normalize(content['similarity_score'])
    else:
        content = pd.DataFrame(columns=['product_id', 'norm_score'])

    hybrid = pd.merge(
        collab[['product_id', 'norm_score']],
        content[['product_id', 'norm_score']],
        on='product_id',
        how='outer',
        suffixes=('_collab', '_content')
    ).fillna(0)

    hybrid['recent_score'] = hybrid['product_id'].apply(lambda x: 1 if x in recent_items else 0)

    hybrid['final_score'] = (
        0.30 * hybrid['norm_score_collab'] +
        0.40 * hybrid['norm_score_content'] +
        0.30 * hybrid['recent_score']
    )
    
    return hybrid.merge(df_products, on='product_id', how='left') \
        .sort_values('final_score', ascending=False) \
        .head(top_k)

def get_model_user_id(user_id):
    return user_to_model.get(user_id, None)
# ======================
# POPULAR
# ======================
def get_popular(top_k=10):
    pop = (
        implicit_df.groupby('product_id')['purchase_count']
        .sum()
        .reset_index()
        .sort_values('purchase_count', ascending=False)
        .head(top_k)
    )
    return df_products.merge(pop, on='product_id', how='inner')