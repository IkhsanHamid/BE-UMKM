<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    //add staff to outlet
    public function addStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'outlet_id' => 'required|integer',
            'role_id' => 'required|integer',
            'business_id' => 'required|integer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'outlet_id' => $request->outlet_id,
            'business_id' => $request->business_id,
        ]);

        return response()->json([
            'data' => $user,
        ], 201);
    }

    // get all staff for business
    public function getStaff($businessId)
    {
        $staff = User::where('business_id', $businessId)->get();

        //load role and outlet
        $staff->load('role', 'outlet');

        return response()->json([
            'data' => $staff,
        ]);
    }

    //edit role staff
    public function editStaff(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::find($id);
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->outlet_id = $request->outlet_id;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Role updated successfully',
            'data' => $user,
        ]);
    }
}
