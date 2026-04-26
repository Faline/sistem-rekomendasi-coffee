<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;

        // Validasi request
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1'
        ]);

        $product_id = $request->product_id;
        $quantity   = $request->quantity;

        DB::beginTransaction();

        try {
        
            $purchase = Purchase::create([
                'user_id'    => $user_id,
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'total_price'=> $request->total_price ?? 0
            ]);

        
            $flaskResponse = Http::timeout(5)->post('http://127.0.0.1:5000/update-interaction', [
                'user_id'    => $user_id,
                'product_id' => $product_id,
                'quantity'   => $quantity
            ]);

            if (!$flaskResponse->successful()) {
                // Rollback DB jika Flask API gagal
                DB::rollBack();
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'Flask API error',
                    'detail'  => $flaskResponse->body()
                ], 500);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Purchase recorded successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            \Log::error("PurchaseController error: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}