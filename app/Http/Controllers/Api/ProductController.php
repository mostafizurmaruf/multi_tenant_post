<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    use AuthorizesRequests; 
    
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $this->authorize('viewAny', Product::class);
        return response()->json($this->service->list());
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);
        $product = $this->service->create($request->validated());
        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);
        $product = $this->service->update($product, $request->validated());
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->service->delete($product);
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
