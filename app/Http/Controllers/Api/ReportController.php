<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Report\TopProductsRequest;
use App\Services\ReportService;
use App\Models\Order;

class ReportController extends Controller
{
    use AuthorizesRequests;

    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function daily()
    {
        $this->authorize('viewReports', Order::class);
        return response()->json($this->service->dailySales());
    }

    public function topProducts(TopProductsRequest $request)
    {
        $this->authorize('viewReports', Order::class);
        return response()->json($this->service->topProducts($request->from, $request->to));
    }

    public function lowStock()
    {
        $this->authorize('viewReports', Order::class);
        return response()->json($this->service->lowStock());
    }
}

