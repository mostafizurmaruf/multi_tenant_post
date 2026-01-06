<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportService
{

    public function dailySales()
    {
        return Order::query()
            ->whereDate('created_at', now())
            ->where('status', 'paid')
            ->selectRaw('
                COUNT(id) as total_orders,
                COALESCE(SUM(total_amount),0) as total_revenue
            ')
            ->first();
    }

    public function topProducts(string $from, string $to)
    {
        return OrderItem::query()
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
            ->whereHas('order', function ($q) use ($from, $to) {
                $q->where('status', 'paid')
                  ->whereBetween('created_at', [$from, $to]);
            })
            ->with([
                'product:id,name,sku'
            ])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->paginate(20);
    }

    public function lowStock()
    {
        return Product::query()
            ->whereColumn(
                'stock_quantity',
                '<=',
                'low_stock_threshold'
            )
            ->get();
    }
}
