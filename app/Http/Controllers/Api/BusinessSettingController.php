<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;

class BusinessSettingController extends Controller
{
    //add business setting
    public function addBusinessSetting(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'charge_type' => 'required|string',
            'type' => 'required|string',
            'value' => 'required|string',
            'business_id' => 'required|string',
        ]);

        $businessSetting = BusinessSetting::create([
            'name' => $request->name,
            'charge_type' => $request->charge_type,
            'type' => $request->type,
            'value' => $request->value,
            'business_id' => $request->business_id,
        ]);

        return response()->json([
            'message' => 'Business setting added successfully',
            'data' => $businessSetting,
        ], 201);
    }

    //get business settings by business
    public function getBusinessSettingsByBusiness($business_id)
    {
        $businessSettings = BusinessSetting::where('business_id', $business_id)->get();

        return response()->json([
            'data' => $businessSettings,
        ]);
    }

    //update business setting
    public function updateBusinessSetting(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'charge_type' => 'required|string',
            'type' => 'required|string',
            'value' => 'required|string',
        ]);

        $businessSetting = BusinessSetting::find($id);
        $businessSetting->name = $request->name;
        $businessSetting->charge_type = $request->charge_type;
        $businessSetting->type = $request->type;
        $businessSetting->value = $request->value;
        $businessSetting->save();

        return response()->json([
            'message' => 'Business setting updated successfully',
            'data' => $businessSetting,
        ]);
    }

    //delete business setting
    public function deleteBusinessSetting($id)
    {
        $businessSetting = BusinessSetting::find($id);
        $businessSetting->delete();

        return response()->json([
            'message' => 'Business setting deleted successfully',
        ]);
    }
}
