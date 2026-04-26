<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;

class HistoryController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        // Ambil data purchase user
        $purchases = Purchase::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Path ke JSON produk (hasil convert dari .pkl)
        $productsPath = base_path('../model/df_products_final.json');

        $products = [];
        if(file_exists($productsPath)){
            $content = file_get_contents($productsPath);
            $products = json_decode($content, true); // decode menjadi array associative
            if($products === null){
                // jika JSON error
                $products = [];
            }
        }

        // Gabungkan purchase dengan data produk
        $history = $purchases->map(function($p) use ($products){
            // cari produk berdasarkan product_id
            $product = collect($products)->firstWhere('product_id', $p->product_id);

            return [
                'product_name' => $product['product_name'] ?? 'Unknown',
                'product_category' => $product['product_category'] ?? '-',
                'cover_image' => $product['cover_image'] ?? 'https://via.placeholder.com/150',
                'quantity' => $p->quantity,
                'total_price' => $p->total_price,
                'created_at' => $p->created_at->format('d M Y H:i') // format readable
            ];
        });

        return view('history', compact('history'));
    }
}