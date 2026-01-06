<?php

namespace App\Services;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $items, $user): Order
    {
        return DB::transaction(function () use ($items, $user) {

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {

                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                // Deduct stock
                $product->decrement('stock_quantity', $item['quantity']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $total += $product->price * $item['quantity'];
            }

            $order->update(['total_amount' => $total]);

            return $order;
        });
    }

    public function cancel(Order $order): void
    {
        DB::transaction(function () use ($order) {

            if ($order->status === 'cancelled') {
                throw new \Exception('Order already cancelled');
            }

            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
        });
    }

}
