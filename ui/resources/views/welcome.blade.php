<!DOCTYPE html>
<html>
<head>
    <title>Rekomendasi Produk</title>
</head>
<body>

    <h1>Rekomendasi Produk</h1>

    @if(isset($products) && count($products) > 0)
        @foreach($products as $p)
            <div style="border:1px solid #ccc; padding:10px; margin:10px;">
                <h3>{{ $p['product_name'] }}</h3>
                <p>ID: {{ $p['product_id'] }}</p>
            </div>
        @endforeach
    @else
        <p>Tidak ada rekomendasi</p>
    @endif

</body>
</html>