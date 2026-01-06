<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{

    public function list()
    {
        return Product::query()
            ->latest()
            ->paginate(20);
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $existing = Product::where('sku', $data['sku'])->first();
            if ($existing) {
                throw new \Exception("SKU already exists for this tenant");
            }

            $product = Product::create($data);
            return $product;
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            if (isset($data['sku'])) {
                $existing = Product::where('sku', $data['sku'])
                    ->where('id', '!=', $product->id)
                    ->first();

                if ($existing) {
                    throw new \Exception("SKU already exists for this tenant");
                }
            }

            $product->update($data);
            return $product;
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            $product->delete();
        });
    }
}
