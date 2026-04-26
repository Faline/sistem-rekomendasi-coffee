<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>History - Cangkir Kita</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;

    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)),
                url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085');
    background-size: cover;
    background-position: center;
}

/* NAVBAR */
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
    font-weight: 500;
}

.logout-btn {
    padding: 8px 15px;
    border-radius: 20px;
    border: 1px solid white;
    background: transparent;
    color: white;
    cursor: pointer;
}

/* CONTAINER */
.container {
    padding: 20px 40px;
}

/* TITLE */
h2 {
    color: white;
    font-weight: 700;
}

/* DATE */
.history-date {
    color: white;
    margin: 25px 0 10px;
    font-weight: 600;
    opacity: 0.8;
}

/* CARD */
.history-card {
    background: rgba(255,255,255,0.96);
    border-radius: 15px;
    padding: 15px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    margin-bottom: 12px;

    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* IMAGE */
.history-card img {
    width: 65px;
    height: 65px;
    border-radius: 12px;
    object-fit: cover;
    margin-right: 15px;
}

/* LEFT SIDE */
.left {
    display: flex;
    align-items: center;
    flex: 1;
}

/* INFO */
.info h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: #2c2c2c;
}

.info p {
    margin: 2px 0;
    font-size: 12px;
    color: #777;
}

/* QTY STYLE (lebih elegan dari "Qty") */
.qty {
    font-size: 12px;
    color: #444;
    background: #f1f1f1;
   
    border-radius: 10px;
    display: inline-block;
    margin-top: 4px;
}

/* RIGHT SIDE (TOTAL PRICE) */
.price-box {
    text-align: right;
}

.label {
    font-size: 11px;
    color: #888;
}

.total-price {
    font-size: 16px;
    font-weight: 700;
    color: #6b4f3b;
}

/* EMPTY */
.empty {
    color: white;
    text-align: center;
    margin-top: 50px;
    opacity: 0.8;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <b>Cangkir Kita</b>
    </div>

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

<!-- CONTENT -->
<div class="container">
    <h2>Purchase History</h2>

    @if($history->isEmpty())
        <div class="empty">No purchase history yet.</div>
    @else
        @php $currentDate = null; @endphp

        @foreach($history as $item)
            @php
                $date = \Carbon\Carbon::parse($item['created_at'])->format('d M Y');
            @endphp

            @if($currentDate != $date)
                <div class="history-date">{{ $date }}</div>
                @php $currentDate = $date; @endphp
            @endif

            <div class="history-card">
                <div class="left">
                
                    <div class="info">
                        <h4>{{ $item['product_name'] }}</h4>
                        <p>{{ $item['product_category'] }}</p>
                        <span class="qty">Quantity: {{ $item['quantity'] }}</span>
                    </div>
                </div>
                <div class="price-box">
                    <div class="label">Total</div>
                    <div class="total-price">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</div>
                </div>
            </div>
        @endforeach
    @endif
</div>

</body>
</html>