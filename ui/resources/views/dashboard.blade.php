<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard</title>

<style>
/* ======================
   GLOBAL
====================== */
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

/* ======================
   NAVBAR
====================== */
.navbar {
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.logo img {
    width: 45px;
}

.link {
    color: white;
    text-decoration: none;
    margin-right: 15px;
}

.link:hover {
    opacity: 0.8;
}

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

/* ======================
   CONTAINER & WELCOME
====================== */
.container {
    padding: 20px 40px;
}

.welcome {
    color: white;
    margin-bottom: 25px;
}

/* ======================
   SECTION (GLASS EFFECT)
====================== */
.section {
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.17);
    backdrop-filter: blur(10px);
}

.section h3 {
    margin-bottom: 15px;
}

.section h2 {
    color: white;
}

/* ======================
   SCROLL & CARDS CONTAINER
====================== */
.scroll {
    flex: 0 0 auto;
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding-bottom: 10px;
}


.cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

/* ======================
   CARD
====================== */
.card {
    flex: 0 0 auto;
    width: 280px;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background-color: white;
    box-sizing: border-box;
}

.card:hover {
    transform: translateY(-5px) scale(1.03);
}

.card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

/* CARD HEADER FLEX */
.card .header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
}


.card .header h2 {
    flex: 1;
    color: black;
    margin: 0;
    font-size: 18px;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
}

.category-badge {
    background-color: #8B4513;
    color: white;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.card p {
    flex-grow: 1;
    margin: 0;
    font-size: 12px;
    color: #777;
}

.price {
    font-weight: bold;
    margin-top: 5px;
    font-size: 14px;
    color: #8B5E3C;
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

/* ======================
   MODAL
====================== */
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

.modal-box {
    width: 500px;
    max-width: 90%;
    background: white;
    border-radius: 25px;
    overflow: hidden;
    animation: fadeIn 0.3s ease;
}

.modal-image {
    height: 220px;
    background-size: cover;
    background-position: center;
}

.modal-content {
    padding: 25px;
}

.modal-content h2 {
    margin: 0;
    font-size: 22px;
}

.category {
    color: #8B5E3C;
    font-size: 16px;
    margin-bottom: 10px;
}

.description {
    font-size: 14px;
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
    margin-top:10px;
    max-height: 80px;
    overflow-y: auto;
}

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

.total {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 20px;
}

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

.btn-secondary {
    margin-top: 10px;
    width: 100%;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    background: white;
    cursor: pointer;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start; /* price tetap di atas */
    gap: 10px;
}

.modal-header h2 {
    flex: 1; /* ambil space yang tersisa */
    margin: 0;
    font-size: 22px;
    line-height: 1.2;
    word-break: break-word;
}

.modal-price {
    font-size: 20px;
    font-weight: bold;
    color: #6b4f3b;
    white-space: nowrap;
}

/* ======================
   ANIMATIONS
====================== */
@keyframes fadeIn {
    from {opacity:0; transform: scale(0.9);}
    to {opacity:1; transform: scale(1);}
}

/* ======================
   TOAST
====================== */
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

    <div class="nav-links">
        <a class="link" href="/dashboard">Home</a>
        <a class="link" href="/menu">Menu</a>
        <a class="link" href="/history">History</a>
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
    <h2 style="margin-top:10px">Recommended For You</h2>
    
    <div class="scroll">
        @foreach($recommendations as $item)
            <div class="card">
                <div class="header">
                    <h2>{{ $item['product_name'] }}</h2>
                    <span class="category-badge">{{ $item['product_category'] }}</span>
                </div>

                <p>{{ $item['product_description'] ?? '-' }}</p>

                <div class="price">
                    Rp {{ number_format($item['unit_price_idr']) }}
                </div>

                <button class="btn"
                    onclick='buyProduct(@json($item))'>
                    Buy
                </button>
            </div>
        @endforeach
        @if(count($recommendations) === 0)
            <p style="color:white">No recommendations yet</p>
        @endif
    </div>
</div>
    @if($hasSimilar)
    <div class="section">
        <h2>Because You Bought Similar Items</h2>
        <div class="scroll" id="similar-list">
        </div>
    </div>
    @endif
    <!-- POPULAR -->
    <div class="section">
        <h2 style="margin-top:10px">Popular</h2>

        <div class="scroll" id="popular-list">
            <!-- dynamic -->
        </div>
    </div>

</div>

</body>
<div id="purchaseModal" class="modal-overlay">
    <div class="modal-box">
        <!-- CONTENT -->
        <div class="modal-content">

            <div class="modal-header">
                <h2 id="modalName"></h2>
                <div class="modal-price" id="modalPrice"></div>
            </div>

            <p id="modalCategory" class="category"></p>
            <p id="modalDescription" class="description"></p>

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
const MODEL_USER_ID = @json($modelUserId ?? auth()->id());

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
                <div class="header">
                    <h2>${item.product_name}</h2>
                    <span class="category-badge">${item.product_category}</span>
                </div>
                <p>${item.product_description}</p>
                <div class="price">${formatRupiah(item.unit_price_idr)}</div>
                <button class="btn" onclick='buyProduct(${JSON.stringify(item)})'>Buy</button>
            </div>
        `;
    });
}
async function loadSimilarItems() {
    const container = document.getElementById("similar-list");

    if (!container) return;

    try {
        const response = await fetch("http://127.0.0.1:5000/similar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            
            body: JSON.stringify({
                user_id: MODEL_USER_ID
            })
        });

        const data = await response.json();

        console.log("SIMILAR DATA:", data);

        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = "";
            return;
        }

        container.innerHTML = "";

        data.forEach(item => {

            const safeItem = JSON.stringify(item).replace(/'/g, "&apos;");

            container.innerHTML += `
            <div class="card">
                <div class="header">
                    <h2>${item.product_name}</h2>
                    <span class="category-badge">${item.product_category}</span>
                </div>
                <p>${item.product_description}</p>
                <div class="price">${formatRupiah(item.unit_price_idr)}</div>
                <button class="btn" onclick='buyProduct(${JSON.stringify(item)})'>Buy</button>
            </div>
            `;
        });

    } catch (err) {
        console.error("Similar load error:", err);
        container.innerHTML = "";
    }
}
function formatRupiah(angka) {
    return "Rp " + Number(angka).toLocaleString("id-ID");
}

loadPopular();
loadSimilarItems();



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
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: selectedProduct.product_id,
            quantity: qty,
            total_price: selectedProduct.unit_price_idr * qty
        })
    })
    .then(res => res.json().catch(() => { throw new Error("Invalid JSON from server") }))
    .then(async data => {
        if(data.status === 'success'){
            closeModal();
            showToast("Pembelian berhasil!");

            await fetch("http://127.0.0.1:5000/refresh-model", {
                method: "POST"
            });

            location.reload();
        
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