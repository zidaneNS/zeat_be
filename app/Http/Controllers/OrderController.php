<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders;

        return response([
            'status' => 200,
            'message' => 'orders successfully retrieved',
            'data' => $orders
        ]);
    }

    public function checkout(Order $order)
    {
        Gate::authorize('checkout', $order);

        $order->update([
            'status' => 'success'
        ]);

        return response([
            'status' => 200,
            'message' => 'checkout success'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required'
        ]);

        $user = $request->user();

        Order::create([
            'status' => 'pending',
            'user_id' => $user->id,
            'payment_method' => 'dummy',
            'quantity' => $validatedFields['quantity'],
            'product_id' => $validatedFields['product_id']
        ]);

        return response([
            'status' => 201,
            'message' => 'order successfully created'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        Gate::authorize('checkout', $order);

        $validatedFields = $request->validate([
            'quantity' => 'required'
        ]);

        $order->update([
            'quantity' => $validatedFields['quantity']
        ]);

        return response([
            'status' => 200,
            'message' => 'order successfully updated',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        Gate::authorize('checkout', $order);

        $order->delete();

        return response([
            'status' => 200,
            'message' => 'order successfully deleted'
        ]);
    }
}
