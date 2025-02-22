<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockHistory;

class StockController extends Controller
{
    //add stock
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|string',
            'note' => 'required|string',
        ]);

        $stock = Stock::find($id);

        if ($request->type == 'add') {
            $stock->quantity += $request->quantity;
        } else {
            $stock->quantity -= $request->quantity;
        }
        $stock->save();

        $history = StockHistory::create([
            'stock_id' => $stock->id,
            'quantity' =>   $request->quantity,
            'current_stock' => $stock->quantity,
            'type' => $request->type,
            'reference' => $request->reference ?? '',
            'user' => $request->user()->name,
            'note' => $request->note,
        ]);

        return response()->json([
            'message' => 'Stock added successfully',
            'data' => $history,
        ], 200);
    }
}
