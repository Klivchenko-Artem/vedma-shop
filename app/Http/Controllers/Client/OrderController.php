<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'comment' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $validated['comment'] = isset($validated['comment'])
            ? strip_tags($validated['comment'])
            : null;

        Order::create($validated);

        return back()->with('success', 'Заказ успешно оформлен!');
    }
}
