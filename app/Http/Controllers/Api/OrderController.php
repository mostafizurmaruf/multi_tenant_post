<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    use AuthorizesRequests;

    protected OrderService $service;
    
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create', Order::class);

        $order = $this->service->create(
            $request->items,
            auth()->user()
        );

        return response()->json($order, 201);
    }

    public function update(Order $order)
    {
        $this->authorize('cancel', $order);

        $this->service->cancel($order);

        return response()->json(['message' => 'Order cancelled']);
    }
}
