<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<title>Menu - Cangkir Kita</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)),
                url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size: cover;
    background-position: center;
}

.navbar {
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.nav-links a {
    color: white;
    text-decoration: none;
    margin: 0 12px;
}

.logout-btn {
    padding: 8px 15px;
    border-radius: 20px;
    border: 1px solid white;
    background: transparent;
    color: white;
    cursor: pointer;
}

.container {
    padding: 20px 40px;
}

h2 {
    color: white;
}

.categories {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.cat-btn {
    padding: 8px 16px;
    border: 1px solid #8B5E3C;
    background: white;
    color: #6b4f3b;
    border-radius: 20px;
    cursor: pointer;
    font-weight: 500;
    transition: 0.2s;
}

.cat-btn:hover {
    background: #a06a3c;
    color: white;
}

.cat-btn.active {
    background: #6b4f3b;
    color: white;
    border-color: #6b4f3b;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(107, 79, 59, 0.3);
}

.grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;

    align-items: stretch;
}

.card {
    background: white;
    border-radius: 15px;
    padding: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    position: relative;
    display: flex;
    flex-direction: column;

    height: auto;   
}
.card-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.card-body {
    display: flex;
    flex-direction: column;
}
.card-footer {
    margin-top: auto;
}
.card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
}

.price {
    color: #8B5E3C;
    font-weight: bold;
}

.buy-btn {
    font-size:12px;
    position: absolute;
    bottom: 10px; 
    right: 10px; 
    width: 100px;
    padding: 8px;
    background: #8B5E3C;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

.buy-btn:hover {
    background: #6f4a2d;
}

.title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0px;
    margin-top: 8px;
}

.product-title {
    font-size: 18px;
    margin: 0;
    color: #2c2c2c;
    font-weight: 600;
    flex: 1;
}

/* CATEGORY di kanan */
.category {
    font-size: 11px;
    color: #f8f5f5;
    background: #4f3232;
    padding: 3px 8px;
    border-radius: 10px;
    white-space: nowrap;
}

/* DESCRIPTION */
.desc {
    font-size: 11px;
    color: #777;
    margin-top: 5px;
    margin-bottom: 20px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* OVERLAY */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(6px);
    justify-content: center;
    align-items: center;
    z-index: 999;
}

/* BOX (LEBIH BESAR) */
.modal-box {
    width: 480px;
    max-width: 92%;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    animation: pop 0.25s ease;
}

/* IMAGE */
.modal-image img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

/* CONTENT */
.modal-content {
    padding: 20px 22px;
}
#modalName {
    font-size: 22px;
    font-weight: 700;
    color: #2c2c2c;
    margin: 0;
    line-height: 1.2;
}

/* CATEGORY (lebih kecil + jarak jelas) */
#modalCategory {
    font-size: 12px;
    color: #6b4f3b;
    margin: 0;
    opacity: 0.8;
}
#modalDescription {
    font-size: 13px;
    color: #666;
    line-height: 1.6;
    margin-top: 12px;
    margin-bottom: 18px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
    gap: 10px;
}

/* LEFT SIDE (NAME + CATEGORY) */
.modal-title-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* DESCRIPTION */
#modalDescription {
    font-size: 13px;
    color: #666;
    line-height: 1.5;
    margin-top: 8px;
    margin-bottom: 15px;
}
.modal-price {
    font-size: 18px;
    font-weight: 700;
    color: #8B5E3C;
    white-space: nowrap;
}



/* TITLE */
.modal-content h2 {
    margin: 0;
    font-size: 22px;
}

.qty-section {
    display: flex;
    justify-content: space-between;
    margin: 15px 0;
}

.qty-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-box button {
    width: 30px;
    height: 30px;
    border: none;
    background: #8B5E3C;
    color: white;
    border-radius: 8px;
    cursor: pointer;
}

.qty-box input {
    width: 50px;
    text-align: center;
}
.toast {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translate(-50%, -20px);

    background: #6b4f3b;
    color: white;

    padding: 16px 30px;
    font-size: 16px;
    font-weight: 500;

    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);

    opacity: 0;
    transition: all 0.4s ease;

    z-index: 999;
}

.toast.show {
    opacity: 1;
    transform: translate(-50%, 0);
}

.qty-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0;
}

.qty-section span {
    font-size: 13px;
    color: #333;
}

.qty-box {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-box button {
    width: 32px;
    height: 32px;
    border: none;
    background: #8B5E3C;
    color: white;
    border-radius: 8px;
    cursor: pointer;
}

.qty-box input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    height: 32px;
}

/* TOTAL */
.total {
    font-size: 15px;
    font-weight: 600;
    margin: 10px 0 15px;
    color: #2c2c2c;
}
.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: #6b4f3b;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-primary:hover {
    background: #543d2d;
}

.btn-secondary {
    width: 100%;
    margin-top: 8px;
    padding: 10px;
    border-radius: 12px;
    border: 1px solid #ddd;
    background: white;
    font-size: 13px;
    cursor: pointer;
}  
/* ANIMATION */
@keyframes pop {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div><b>Cangkir Kita</b></div>

    <div class="nav-links">
        <a href="/dashboard">Home</a>
        <a href="/menu">Menu</a>
        <a href="/history">History</a>
    </div>

    <form method="POST" action="/logout">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>
</div>

<div class="container">

    <h2>Menu Produk</h2>

    <!-- FILTER CATEGORY -->
<form method="GET" action="/menu">
    <div class="categories">

        <!-- ALL -->
        <button type="submit"
            name="category"
            value=""
            class="cat-btn {{ empty($category) ? 'active' : '' }}">
            All

        </button>

        @foreach($categories as $cat)
            <button type="submit"
                name="category"
                value="{{ $cat }}"
                class="cat-btn {{ $category == $cat ? 'active' : '' }}">
                {{ $cat }}
            </button>
        @endforeach

    </div>
</form>

    <!-- PRODUCT GRID -->
    <div class="grid">

        @forelse($products as $item)
            <div class="card">

                <div class="card-body">

                    <div class="title-row">
                        <h4 class="product-title">{{ $item['product_name'] }}</h4>
                        <span class="category">{{ $item['product_category'] }}</span>
                    </div>

                    <p class="desc">
                        {{ $item['product_description'] ?? 'No description available' }}
                    </p>

                </div>

                <div class="card-footer">
                    <div class="price">
                        Rp {{ number_format($item['unit_price_idr'], 0, ',', '.') }}
                    </div>

                    <button class="buy-btn"
                        onclick='buyProduct(@json($item))'>
                        Buy
                    </button>
                </div>

</div>
        @empty
            <p style="color:white">No products found</p>
        @endforelse

    </div>

</div>

</body>
<div id="purchaseModal" class="modal-overlay">

    <div class="modal-box">

        <!-- IMAGE -->
        <div class="modal-image" id="modalImage">
            {{-- <img src='https://images.unsplash.com/photo-1495474472287-4d71bcdd2085'> --}}
        </div>

        <!-- CONTENT -->
        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-title-group">
                    <h2 id="modalName"></h2>
                    <p id="modalCategory"></p>
                </div>

                <div id="modalPrice" class="modal-price"></div>

            </div>

            <p id="modalDescription" class="description"></p>

            <div class="price" id="modalPrice"></div>

            <!-- QTY -->
            <div class="qty-section">
                <span>Quantity</span>
                <div class="qty-box">
                    <button onclick="changeQty(-1)">-</button>
                    <input type="number" id="qtyInput" value="1" min="1">
                    <button onclick="changeQty(1)">+</button>
                </div>
            </div>

            <!-- TOTAL -->
            <div class="total">
                Total: <span id="totalPrice"></span>
            </div>

            <!-- BUTTON -->
            <button class="btn-primary" onclick="confirmPurchase()">Buy Now</button>
            <button class="btn-secondary" onclick="closeModal()">Cancel</button>

        </div>

    </div>

</div>
<div id="toast" class="toast"></div>
<script>

function showToast(message) {
    const toast = document.getElementById("toast");
    toast.innerText = message;

    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
function formatRupiah(angka) {
    return "Rp " + Number(angka).toLocaleString("id-ID");
}




let selectedProduct = null;

function buyProduct(product) {
    selectedProduct = product;

    document.getElementById("modalName").innerText = product.product_name;
    document.getElementById("modalCategory").innerText = product.product_category;

    document.getElementById("modalDescription").innerText =
        product.text_content || "No description available";

    document.getElementById("modalPrice").innerText =
        "Rp " + Number(product.unit_price_idr).toLocaleString("id-ID");

    document.getElementById("qtyInput").value = 1;

    updateTotal();

    document.getElementById("purchaseModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("purchaseModal").style.display = "none";
}

function changeQty(val) {
    let input = document.getElementById("qtyInput");
    let qty = parseInt(input.value) || 1;

    qty += val;
    if (qty < 1) qty = 1;

    input.value = qty;
    updateTotal();
}

document.getElementById("qtyInput").addEventListener("input", updateTotal);

function updateTotal() {
    let qty = parseInt(document.getElementById("qtyInput").value) || 1;
    let total = qty * selectedProduct.unit_price_idr;

    document.getElementById("totalPrice").innerText =
        "Rp " + total.toLocaleString("id-ID");
}

function confirmPurchase() {
    const qty = parseInt(document.getElementById("qtyInput").value);

    fetch("/purchase", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: selectedProduct.product_id ?? selectedProduct.id,
            quantity: qty,
            total_price: selectedProduct.unit_price_idr * qty
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success'){
            closeModal();
            showToast("Pembelian berhasil!");
        } else {
            showToast("Error: " + data.message);
        }
    })
    .catch(err => {
        showToast("Purchase failed: " + err.message);
    });
}
function closeModal() {
    document.getElementById("purchaseModal").style.display = "none";
}

</script>
</html>