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

    rebuild_system()


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


# ======================
# NORMALIZE
# ======================
def normalize(x):
    if len(x) == 0:
        return x
    if x.max() == x.min():
        return np.zeros(len(x))
    return (x - x.min()) / (x.max() - x.min() + 1e-9)


# ======================
# ALS COLLAB
# ======================
def recommend_collaborative(user_id, top_k=5):
    if user_id not in user_map:
        return pd.DataFrame()

    user_idx = user_map[user_id]

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
# CONTENT BASED HISTORY
# ======================
def recommend_content_from_history(user_id, top_k=10):
    user_items = implicit_df[implicit_df['user_id'] == user_id]['product_id']

    if len(user_items) == 0:
        return pd.DataFrame()

    idx = [item_map[i] for i in user_items if i in item_map]

    if len(idx) == 0:
        return pd.DataFrame()

    user_vec = content_features[idx].mean(axis=0)
    sim = cosine_similarity([user_vec], content_features)[0]

    df_score = pd.DataFrame({
        "product_id": df_products['product_id'],
        "similarity_score": sim
    })

    return df_score.sort_values("similarity_score", ascending=False).head(top_k)


# ======================
# HYBRID (FIXED LOGIC)
# ======================
def hybrid_recommendation(user_id, preferences=None, top_k=5):

    if user_id not in user_map:
        return get_popular(top_k)

    recent_items = implicit_df[implicit_df['user_id'] == user_id].tail(5)['product_id'].values

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
        0.20 * hybrid['norm_score_collab'] +
        0.60 * hybrid['norm_score_content'] +
        0.20 * hybrid['recent_score']
    )

    return hybrid.merge(df_products, on='product_id', how='left') \
        .sort_values('final_score', ascending=False) \
        .head(top_k)