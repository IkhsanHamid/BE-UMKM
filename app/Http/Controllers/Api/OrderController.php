<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockHistory;

class OrderController extends Controller
{
    //add order
    public function addOrder(Request $request)
    {
        // 'order_number',
        // 'outlet_id',
        // 'sub_total',
        // 'total_price',
        // 'total_items',
        // 'tax',
        // 'discount',
        // 'payment_method',
        // 'status',
        // 'cashier_id'

        $request->validate([
            'outlet_id' => 'required|integer',
            'sub_total' => 'required|numeric',
            'total_price' => 'required|numeric',
            'total_items' => 'required|integer',
            'tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $order = Order::create([
            'order_number' => 'ORD' . time(),
            'outlet_id' => $request->outlet_id,
            'sub_total' => $request->sub_total,
            'total_price' => $request->total_price,
            'total_items' => $request->total_items,
            'tax' => $request->tax,
            'discount' => $request->discount,
            'payment_method' => $request->payment_method,
            'status' => 'success',
            'cashier_id' => $request->user()->id,
        ]);

        //add order items
        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
        }

        //update stock
        foreach ($request->items as $item) {
            //stock where product_id and outlet_id
            $stock = Stock::where('product_id', $item['product_id'])
                ->where('outlet_id', $request->outlet_id)
                ->first();
            $stock->quantity -= $item['quantity'];
            $stock->save();

            //create stock history
            StockHistory::create([
                'stock_id' => $stock->id,
                'quantity' => $item['quantity'],
                'current_stock' => $stock->quantity,
                'type' => 'deduct',
                'reference' => $order->order_number,
                'user' => $request->user()->name,
                'note' => 'Order #' . $order->order_number,
            ]);
        }

        return response()->json([
            'message' => 'Order added successfully',
            'data' => $order,
        ], 201);
    }

    //get orders for outlet
    public function getOrdersByOutlet($id)
    {
        $orders = Order::where('outlet_id', $id)->orderBy('id', 'desc')->get();

        //load order items, product
        $orders->load('items.product',);

        return response()->json([
            'data' => $orders,
        ]);
    }
}
