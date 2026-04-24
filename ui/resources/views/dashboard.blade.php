<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;

    /* BACKGROUND COFFEE */
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)),
                url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size: cover;
    background-position: center;
}

#modalDescription {
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* NAVBAR */
.navbar {
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

/* LOGO */
.logo img {
    width: 45px;
}

/* LOGOUT */
.logout-btn {
    padding: 8px 15px;
    border-radius: 20px;
    border: 1px solid white;
    background: transparent;
    color: white;
    cursor: pointer;
}

.logout-btn:hover {
    background: white;
    color: #6b4f3b;
}

/* CONTAINER */
.container {
    padding: 20px 40px;
}

/* WELCOME */
.welcome {
    color: white;
    margin-bottom: 25px;
}

/* SECTION CARD (GLASS EFFECT) */
.section {
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 20px;

    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
}

/* TITLE */
.section h3 {
    margin-bottom: 15px;
}

/* SCROLL */
.scroll {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding-bottom: 10px;
}

/* CARD */
.card {
    min-width: 180px;
    background: white;
    border-radius: 15px;
    padding: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    flex-shrink: 0;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px) scale(1.03);
}

/* IMAGE */
.card img {
    width: 100%;
    height: 110px;
    object-fit: cover;
    border-radius: 10px;
}

/* TEXT */
.card h4 {
    margin: 8px 0 3px;
    font-size: 14px;
}

.card p {
    margin: 0;
    font-size: 12px;
    color: #777;
}

/* PRICE */
.price {
    color: #8B5E3C;
    font-weight: bold;
    margin-top: 5px;
}

/* BUTTON */
.btn {
    margin-top: 8px;
    width: 100%;
    padding: 8px;
    border: none;
    border-radius: 20px;
    background: #8B5E3C;
    color: white;
    cursor: pointer;
}

.btn:hover {
    background: #6f4a2d;
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
    width: 500px;
    max-width: 90%;
    background: white;
    border-radius: 25px;
    overflow: hidden;
    animation: fadeIn 0.3s ease;
}

/* IMAGE */
.modal-image {
    height: 220px;
    background-size: cover;
    background-position: center;
}

/* CONTENT */
.modal-content {
    padding: 25px;
}

/* TITLE */
.modal-content h2 {
    margin: 0;
    font-size: 22px;
}

/* CATEGORY */
.category {
    color: #8B5E3C;
    font-size: 13px;
    margin-bottom: 10px;
}

/* DESCRIPTION */
.description {
    font-size: 14px;
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;

    max-height: 80px;
    overflow-y: auto;
}

/* PRICE */
.price {
    font-size: 20px;
    font-weight: bold;
    color: #6b4f3b;
    margin-bottom: 20px;
}

/* QTY SECTION */
.qty-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.qty-section span {
    font-size: 14px;
    color: #333;
}

/* QTY BOX */
.qty-box {
    display: flex;
    gap: 10px;
    align-items: center;
}

.qty-box button {
    width: 35px;
    height: 35px;
    border: none;
    background: #8B5E3C;
    color: white;
    border-radius: 10px;
    cursor: pointer;
}

.qty-box input {
    width: 50px;
    text-align: center;
    border-radius: 8px;
    border: 1px solid #ddd;
    height: 35px;
}

/* TOTAL */
.total {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 20px;
}

/* BUTTON PRIMARY */
.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 20px;
    border: none;
    background: #6b4f3b;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

.btn-primary:hover {
    background: #543d2d;
}

/* BUTTON SECONDARY */
.btn-secondary {
    margin-top: 10px;
    width: 100%;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    background: white;
    cursor: pointer;
}

/* ANIMATION */
@keyframes fadeIn {
    from {opacity:0; transform: scale(0.9);}
    to {opacity:1; transform: scale(1);}
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

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        <p>Cangkir Kita</p>
    </div>

    <form method="POST" action="/logout">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>

</div>

<div class="container">

    <!-- WELCOME -->
    <div class="welcome">
        <h1>Hello, {{ auth()->user()->name }}</h1>
        <p style="margin-top:-15px">Find your perfect coffee today ☕</p>
    </div>

    <!-- RECOMMEND -->
    <div class="section">
        <h3>Recommended For You</h3>

        <div class="scroll" id="recommendation-list">
            <!-- dynamic -->
        </div>
    </div>

    <!-- POPULAR -->
    <div class="section">
        <h3>Popular</h3>

        <div class="scroll" id="popular-list">
            <!-- dynamic -->
        </div>
    </div>

</div>

</body>
<div id="purchaseModal" class="modal-overlay">

    <div class="modal-box">

        <!-- IMAGE -->
        <div class="modal-image" id="modalImage"></div>

        <!-- CONTENT -->
        <div class="modal-content">

            <h2 id="modalName"></h2>
            <p id="modalCategory" class="category"></p>

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
async function loadRecommendations() {
    const response = await fetch("http://127.0.0.1:5000/recommend", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            user_id: {{ auth()->user()->id }},
            preferences: null
        })
    });

    const data = await response.json();

    const container = document.getElementById("recommendation-list");
    container.innerHTML = "";

    data.forEach(item => {
        container.innerHTML += `
            <div class="card">
                <img src="${item.cover_image || 'https://via.placeholder.com/150'}">
                <h4>${item.product_name}</h4>
                <p>${item.product_category}</p>
                <div class="price">${formatRupiah(item.unit_price_idr)}</div>
                <button class="btn" onclick='buyProduct(${JSON.stringify(item)})'>
                    Buy
                </button>
            </div>
        `;
    });
}

function showToast(message) {
    const toast = document.getElementById("toast");
    toast.innerText = message;

    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

async function loadPopular() {
    const response = await fetch("http://127.0.0.1:5000/popular");
    const data = await response.json();

    console.log("POPULAR:", data);

    const container = document.getElementById("popular-list");
    container.innerHTML = "";

    data.forEach(item => {
        container.innerHTML += `
            <div class="card">
                <img src="${item.cover_image || 'https://via.placeholder.com/150'}">
                <h4>${item.product_name}</h4>
                <p>${item.product_category}</p>
                <div class="price">${formatRupiah(item.unit_price_idr)}</div>
                <button class="btn" onclick='buyProduct(${JSON.stringify(item)})'>
                    Buy
                </button>
            </div>
        `;
    });
}
function formatRupiah(angka) {
    return "Rp " + Number(angka).toLocaleString("id-ID");
}
loadRecommendations();
loadPopular();


</script>
<script>
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
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            user_id: {{ auth()->user()->id }},
            product_id: selectedProduct.product_id,
            quantity: qty
        })
    })
    .then(() => {
        return fetch("http://127.0.0.1:5000/update-interaction", {
            method: "POST", 
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                user_id: {{ auth()->user()->id }},
                product_id: selectedProduct.product_id,
                quantity: qty
            })
        })

    })
    .then(() => {
        closeModal();
        showToast("Pembelian berhasil!");

        loadRecommendations();
    });
}
function closeModal() {
    document.getElementById("purchaseModal").style.display = "none";
}
</script>
</html>