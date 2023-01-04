<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new OrderCollection(Order::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'paid' => $request->paid
        ]);

        if ($order) {
            $data['data'] = [
                'id' => $order->id,
                'message' => 'success'
            ];

            return response()->json($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new OrderResource(Order::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->customer_id = $request->customer_id;
        $order->paid = $request->paid;

        $order->save();

        $data = [
            'data' => $order,
            'message' => 'updated successfully'
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        $data['data'] = [
            'id' => $id,
            'message' => 'deleted successfully'
        ];

        return response()->json($data);
    }
}
