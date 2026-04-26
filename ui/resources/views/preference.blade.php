<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Preferences</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size: cover;
    background-position: center;
}

/* CONTAINER */
.container {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.card {
    width: 750px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 35px;
    border-radius: 20px;
    background: rgba(255,255,255,0.97);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

/* TITLE */
h1 {
    margin-top : -10px;
    margin-bottom: -15px;
}

.subtitle {
    color: #777;
    margin-bottom: 25px;
}

/* SECTION */
.section {
    margin-bottom: 25px;
}

.title {
    font-weight: 600;
    margin-bottom: 10px;
    display: block;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

/* TILE */
.tile input {
    display: none;
}

.box {
    background: #c8b79b;
    padding: 14px;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: 0.2s;
    font-weight: 600;
    font-size: 13px;
}

.tile input:checked + .box {
    background: #f39c34;
    color: white;
}

/* INPUT */
input[type="text"], input[type="number"] {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    outline: none;
}

/* SLIDER */
.slider-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

input[type="range"] {
    width: 100%;
    accent-color: #8B5E3C;
}

/* TAGS */
.tags {
    margin-top:10px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    padding: 6px 10px;
    background: #eee;
    border-radius: 20px;
    cursor: pointer;
    font-size: 12px;
    transition: 0.2s;
}

.tag:hover {
    background: #ddd;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    background: #8B5E3C;
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
}

button:hover {
    background: #6f4a2d;
}
</style>
</head>

<body>

<div class="container">
<div class="card">

    <h1>What’s your coffee vibe? </h1>
    <p class="subtitle">We’ll match your taste with the perfect recommendation</p>

    <form method="POST" action="/preference">
    @csrf
    <!-- CATEGORY -->
    <div class="section">
        <label class="title">Category</label>
        <div class="grid">
            @foreach(['Coffee','Tea','Drinking Chocolate','Bakery','Coffee beans','Flavours','Loose Tea','Packaged Chocolate'] as $cat)
            <label class="tile">
                <input type="checkbox" name="categories[]" value="{{ $cat }}">
                <div class="box">{{ $cat }}</div>
            </label>
            @endforeach
        </div>
    </div>

    <!-- TYPE -->
    <div class="section">
        <label class="title">Type</label>
        <div class="grid">
            @foreach(['Barista Espresso','Drip coffee','Gourmet brewed coffee','Brewed Chai Tea','Brewed Green tea','Hot chocolate','Biscotti','Pastry','Scone','Espresso Beans'] as $type)
            <label class="tile">
                <input type="checkbox" name="types[]" value="{{ $type }}">
                <div class="box">{{ $type }}</div>
            </label>
            @endforeach
        </div>
    </div>

    <!-- KEYWORDS -->
    <div class="section">
        <label class="title">Keywords (tap suggestion)</label>
        <input type="text" id="keywordInput" name="keywords" placeholder="type or click suggestion...">
        <div class="tags">
            @foreach(['strong','sweet','milky','iced','chocolate','fruity','bold','smooth','premium','spicy','clean','refreshing','bitter','rich'] as $kw)
                <div class="tag" onclick="addKeyword('{{ $kw }}')">{{ $kw }}</div>
            @endforeach
        </div>
    </div>

    <!-- PRICE SLIDER -->
    <div class="section">
        <label class="title">Max Budget: <span id="priceLabel">Rp 60.000</span></label>
        <div class="slider-wrap">
            <input type="range" id="priceSlider" name="max_price_idr" min="10000" max="800000" step="5000" value="60000" oninput="updatePrice(this.value)">
        </div>
    </div>

    <button type="submit">Get My Recommendation</button>
</form>

<script>
function addKeyword(word){
    let input = document.getElementById("keywordInput");
    if(input.value.trim() === "") input.value = word;
    else input.value += " " + word;
}

function formatRupiah(number) {
    return "Rp " + new Intl.NumberFormat("id-ID").format(number);
}

function updatePrice(value) {
    document.getElementById("priceLabel").innerText = formatRupiah(value);
}
</script>

</body>
</html>