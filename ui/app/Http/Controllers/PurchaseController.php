<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PurchaseController extends Controller
{
   public function store(Request $request)
    {
        // simpan ke DB dulu (seperti biasa)

        Http::post('http://127.0.0.1:5000/update-interaction', [
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        return response()->json(['status' => 'success']);
    }
}