<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService 
{
    private const CACHE_TTL = 300; // Cache for  5 minutes
    public function getActiveProducts(array $filters = [], int $perPage = 15):LengthAwarePaginator
    {
        $page = request()->query('page', 1);
      //  $cacheKey = "products:active:page:{$page}:per_page:{$perPage}:" . http_build_query($filters);

        return Cache::remember("product.active.page.{$page}.per_page.{$perPage}:", self::CACHE_TTL, function () use ( $perPage) {
           return Product::active()
                ->with('vendor:id,name,business_name')
                ->latest( )
                ->paginate($perPage);
        });
    }

    public function searchProducts(string $term, int $perPage = 15):LengthAwarePaginator
    {
      return Product::active()
            ->search($term)
            ->with('vendor:id,name, business_name')
            ->latest()
            ->paginate($perPage);
    }

    public function getActiveProduct(int $id): ?Product
    {
        return Product::active()
            ->with('vendor:id,name, business_name')
            ->find($id);
    }

    public function getVendorProducts(int $vendorId, int $perPage = 15): LengthAwarePaginator
    {
        return Product::where('vendor_id', $vendorId)
            ->latest()
            ->paginate($perPage);
    }

    public function getVendorProduct(int $vendorId, int$productId): ?Product
    {
        return Product::where('vendor_id', $vendorId)
            ->find($productId);
    }

    public function createProduct(int $vendorId, array $data): Product
    {
       $product = Product::create(array_merge($data, ['vendor_id' => $vendorId]));

         $this->flushProductCache();
         
        return $product;
    }

    public function updateProduct(Product $product, array $data): Product
    {
          if (isset($data['stock_adjustment'])) {
            $newQty = $product->stock_quantity + (int) $data['stock_adjustment'];

            if ($newQty < 0) {
                throw new \DomainException('Stock cannot go below zero.');
            }

            $data['stock_quantity'] = $newQty;
            unset($data['stock_adjustment']);
        }
        $product->update($data);
        $this->flushProductCache();
        return $product->fresh();
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
        $this->flushProductCache();
    }
    public function flushProductCache(): void
    {
        $version = Cache::get('product_cache_version', 1);
        Cache::put('product_cache_version', $version + 1, now()->addDays(30));
    }
}