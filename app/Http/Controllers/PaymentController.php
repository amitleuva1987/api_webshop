<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);
        $ordervalue = OrderProduct::where('order_id', $id)->sum('value');

        $client = new \GuzzleHttp\Client();
        $payment = [
            'order_id' => $order->id,
            'customer_email' => $customer->email,
            'value' => $ordervalue
        ];


        $response = $client->request('POST', 'https://superpay.view.agentur-loop.com/pay', [
            'body' => json_encode($payment),
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json'
            ],
        ]);

        $response_data = json_decode($response->getBody(), true);

        return response()->json($response_data);
    }
}
