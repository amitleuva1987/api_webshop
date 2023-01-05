<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return new OrderCollection(Order::all());
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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
        $order = Order::find($id);
        if ($order) {
            return new OrderResource($order);
        } else {
            return response()->json([
                'message' => 'Order does not exist'
            ], Response::HTTP_NOT_FOUND);
        }
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
        $order = Order::find($id);
        if ($order) {
            $order->customer_id = $request->customer_id;
            $order->paid = $request->paid;

            $order->save();

            $data = [
                'data' => $order,
                'message' => 'updated successfully'
            ];

            return response()->json($data);
        } else {
            return response()->json([
                'message' => 'Order does not exist'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();

            $data['data'] = [
                'id' => $id,
                'message' => 'deleted successfully'
            ];

            return response()->json($data);
        } else {
            return response()->json([
                'message' => 'Order does not exist'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
