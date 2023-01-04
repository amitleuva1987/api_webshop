<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class OrderProductController extends Controller
{
    public function add($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $product = Product::findOrFail($request->product_id);

        if ($order && $order->paid === 'no') {
            $order_product = OrderProduct::create([
                'order_id' => $id,
                'product_id' => $request->product_id,
                'quantity' => 1,
                'value' => $product->price
            ]);

            if ($order_product) {
                $data = [
                    'data' => $order_product,
                    'message' => 'Product added to order'
                ];

                return response()->json($data);
            }
        } else {
            $data = [
                'id' => $id,
                'message' => 'Order is already paid'
            ];
            return response()->json($data);
        }
    }
}
