import pandas as pd

# Path ke file pickle
pkl_path = 'model/df_products_final.pkl'  # sesuaikan dengan lokasi file pkl Anda

# Path output JSON
json_path = 'df_products_final.json'

# Load data dari pickle
df = pd.read_pickle(pkl_path)

# Konversi menjadi JSON, orient='records' menghasilkan array of objects
df.to_json(json_path, orient='records', force_ascii=False)

print(f"File JSON berhasil dibuat: {json_path}")