<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outlet;


class OutletController extends Controller
{
    //add outlet to business
    public function addOutlet(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'business_id' => 'required|integer',
        ]);

        $outlet = Outlet::create([
            'name' => $request->name,
            'business_id' => $request->business_id,
            'address' => $request->address,
            'phone' => $request->phone,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Outlet added successfully',
            'data' => $outlet,
        ], 201);
    }

    //update outlet
    public function updateOutlet(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        $outlet = Outlet::find($id);
        $outlet->name = $request->name;
        $outlet->address = $request->address;
        $outlet->phone = $request->phone;
        $outlet->description = $request->description;
        $outlet->save();

        return response()->json([
            'message' => 'Outlet updated successfully',
            'data' => $outlet,
        ]);
    }

    //get outlets for business
    public function getOutlets($businessId)
    {
        $outlets = Outlet::where('business_id', $businessId)->get();

        return response()->json([
            'data' => $outlets,
        ]);
    }
}
