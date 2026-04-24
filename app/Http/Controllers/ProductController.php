<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

use function Termwind\terminal;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):AnonymousResourceCollection
    {
        $products = $this->productService->getActiveProducts(
            perPage:(int) $request->query('per_page', 15)
        );

        return ProductResource::collection($products);
    }

    public function search(Request $request)
    {
        $query = $request->query('q');
        $products = $this->productService->searchProducts(
            term: $query,
            perPage: (int) $request->query('per_page', 15)
        );

        return response()->json([
            'message' => 'Search results',
            'data' => ProductResource::collection($products)
        ], 200);
    }
    public function show(string $id):JsonResponse
    {
        $product = $this->productService->getActiveProduct($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => new ProductResource($product)
        ], 200);
    }

    public function vendorIndex(Request $Request):AnonymousResourceCollection
    {
        $products = $this->productService->getVendorProducts(
            vendorId: $Request->user()->id,
            perPage: (int) $Request->get('per_page', 15)
        );
        return  ProductResource::collection($products);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request):JsonResponse
    {
        $product = $this->productService->createProduct(
            vendorId: $request->user()->id,
            data: $request->validated()
        );
        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function update(UpdateProductRequest $request, string $id):JsonResponse
     {
        $product = $this->productService->getVendorProduct(
            productId: $id,
            vendorId: $request->user()->id,
           
        );

        if (!$product) {
            return response()->json([
                'message' => 'Product not found or unauthorized'
            ], 404);
        }
        $updated = $this->productService->updateProduct($product, $request->validated());

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($updated)
        ], 200);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id):JsonResponse
     {
        $product = $this->productService->getVendorProduct(
            productId: $id,
            vendorId: $request->user()->id
        );

        if (!$product) {
            return response()->json([
                'message' => 'Product not found or unauthorized'
            ], 404);
        }

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    {
        
    }
}
}
