<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService) {}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->placeOrder($request->validated());

        if(!$order['success'])
        {
            return response()->json([
                'message' => $order['message']
            ], 422);
        }
        return response()->json([
            'message' => 'Order created successfully',
            'data' => new OrderResource($order['order']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
