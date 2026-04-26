<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MenuController extends Controller
{
    public function index(Request $request)
{
    $category = $request->category;

    $categories = [
        'Coffee',
        'Tea',
        'Drinking Chocolate',
        'Bakery',
        'Coffee beans',
        'Flavours',
        'Loose Tea',
        'Packaged Chocolate'
    ];

    $response = Http::get("http://127.0.0.1:5000/products");

    $products = $response->json() ?? []; 

    if (!empty($category)) {
        $products = array_filter($products, function ($item) use ($category) {
            return $item['product_category'] === $category;
        });
    }

    return view('menu', compact('products', 'categories', 'category'));
}
}