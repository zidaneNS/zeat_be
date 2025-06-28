<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
