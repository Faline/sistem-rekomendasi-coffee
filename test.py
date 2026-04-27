# ====================== TEST SCRIPT RECOMMENDATION SAFE TF-IDF ======================
import pandas as pd
import numpy as np

# Import semua fungsi dari recommendation_api.py
from recommendation_api import init_system, recommend_for_new_user, \
    recommend_content_from_history, recommend_collaborative, hybrid_recommendation

# ======================
# CLEAN DATA LAYER
# ======================
df_products = None
implicit_df = None

# runtime cache
user_registry = set()

# ---------------------- Dummy Data ----------------------
df_products_test = pd.DataFrame({
    'product_id': [1,2,3,4,5,6],
    'product_name': ['Coffee A','Tea B','Coffee C','Chocolate D','Tea E','Coffee F'],
    'product_category': ['Coffee','Tea','Coffee','Chocolate','Tea','Coffee'],
    'product_type': ['Brewed','Packaged','Packaged','Packaged','Brewed','Brewed'],
    'unit_price_idr': [30000, 25000, 40000, 60000, 20000, 45000]
})

implicit_df_test = pd.DataFrame({
    'user_id': [1,1,2,3,3,4],
    'product_id': [1,2,2,3,5,6],
    'purchase_count': [2,1,1,1,2,1]
})

# Dummy ALS model
class DummyALS:
    def recommend(self, userid, user_items, N, filter_already_liked_items):
        rec = list(range(N))  # dummy rekomendasi
        scores = np.random.rand(N)
        return rec, scores

# Dummy TF-IDF (tidak ada transform sebenarnya)
class DummyTFIDF:
    def transform(self, texts):
        return np.zeros((len(texts), df_products_test.shape[0]))  # dummy array

# Dummy content features dan text features
dummy_content_features = np.random.rand(df_products_test.shape[0], 4)
dummy_text_features = np.random.rand(df_products_test.shape[0], 4)

# ---------------------- Inisialisasi sistem ----------------------
def init_system(products, implicit, model, tfidf_model, content_feat, text_feat):
    global df_products, implicit_df, als_model, tfidf
    global content_features, text_features, user_registry

    df_products = products.copy()
    implicit_df = implicit.copy()

    als_model = model
    tfidf = tfidf_model
    content_features = content_feat
    text_features = text_feat

    # reset registry berdasarkan data asli
    user_registry = set(implicit_df['user_id'].unique())

    print("SYSTEM INITIALIZED")
    print("USERS:", len(user_registry))

# ---------------------- Test recommend_for_new_user ----------------------
print("=== TEST: recommend_for_new_user ===")
preferences = {
    'categories': ['Coffee','Tea'],
    'types': ['Brewed', 'Packaged'],
    'keywords': 'organic',
    'max_price_idr': 50000
}
res_new_user = recommend_for_new_user(preferences, top_k=5)
print(res_new_user)

# ---------------------- Test recommend_content_from_history ----------------------
print("\n=== TEST: recommend_content_from_history for user 1 ===")
res_content = recommend_content_from_history(user_id=1, top_k=5)
print(res_content)

# ---------------------- Test recommend_collaborative ----------------------
print("\n=== TEST: recommend_collaborative for user 1 ===")
res_collab = recommend_collaborative(user_id=1, top_k=5)
print(res_collab)

# ---------------------- Test hybrid_recommendation ----------------------
print("\n=== TEST: hybrid_recommendation for user 1 ===")
res_hybrid = hybrid_recommendation(user_id=1, preferences=preferences, top_k=5)
print(res_hybrid)