<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService 
{
    public function placeOrder(array $data): array
    {
        try{
            $order = DB::transaction(function () use ($data) {
                $product = Product::where('id', $data['product_id'])
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

                if (!$product) {
                    throw new \Exception('Product not found or inactive');
                }

                if (!$product->isInStock($data['quantity'])) {
                    throw new \Exception('Insufficient stock for the requested quantity');
                }

                $product->decrement('stock_quantity', $data['quantity']);
                $order = Order::create([
                    'product_id' => $data['product_id'],
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'],
                    'quantity' => $data['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $data['quantity'],
                    'status' => 'pending',
                ]);


            });

            return [
                'success' => true,
                'order' => $order->load('product'),
            ];
        } catch (\DomainException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } catch (Throwable $e) {
            report($e);
            return [
                'success' => false,
                'message' => 'An unexpected error occurred',
            ];
        }
    }
    
    
}