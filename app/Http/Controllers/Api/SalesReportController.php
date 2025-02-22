<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Outlet;

class SalesReportController extends Controller
{
    //get daily sales report
    public function getDailySalesReport(Request $request)
    {
        $businessId = $request->business_id;
        $outletIds = Outlet::where('business_id', $businessId)->pluck('id');
        $date = $request->date;
        $sales = Order::whereIn('outlet_id', $outletIds)->whereDate('created_at', $date)->get();
        $totalRecipts = $sales->count();
        $totalSales = $sales->sum('total_price');
        $averageSales = $sales->avg('total_price');
        return response()->json([
            'date' => $date,
            'totalRecipts' => $totalRecipts,
            'totalSales' => $totalSales,
            'averageSales' => $averageSales,
            'sales' => $sales
        ]);
    }
}
