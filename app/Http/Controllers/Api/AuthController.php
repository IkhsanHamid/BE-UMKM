<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
        {
            $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cek apakah email sudah terdaftar
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email sudah terdaftar, silakan gunakan email lain.'
            ], 400);
        }

        $user = User::create([
            'name' => 'Owner',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1,
        ]);

        //create a business for the user
        $business = Business::create([
            'name' => $request->name,
            'owner_id' => $user->id,
        ]);

        $user->business_id = $business->id;

        //create an outlet for the business
        $outlet = Outlet::create([
            'name' => $request->name . ' Pusat',
            'business_id' => $business->id,
            'address' => $request->address,
        ]);

        $user->outlet_id = $outlet->id;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'data' => $user,
        ], 201);
    }

    //login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'data' => $user,
        ]);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    //me
    public function me(Request $request)
    {
        //get business and outlet
        $user = $request->user();
        $user->load('business', 'outlet', 'business.outlets', 'role');
        return response()->json([
            'data' => $user,
        ]);
    }

    //refresh
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'data' => $user,
        ]);
    }

    //get outlets by business
    public function getOutletsByBusiness(Request $request)
    {
        $user = $request->user();
        $outlets = $user->business->outlets;

        return response()->json([
            'data' => $outlets,
        ]);
    }

    //get outlet by user if owner or manager
    public function getOutletByUser(Request $request)
    {
        $user = $request->user();
        if ($user->role_id == 1) {
            $outlet = Outlet::where('business_id', $user->business_id)->first();
        } else {
            $outlet = Outlet::find($user->outlet_id);
        }

        return response()->json([
            'data' => $outlet,
        ]);
    }

    //get outlet by id
    public function getOutletById(Request $request, $id)
    {
        $outlet = Outlet::find($id);

        return response()->json([
            'data' => $outlet,
        ]);
    }

    //add manager
    public function addManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'outlet_id' => 'required|exists:outlets,id',
            'business_id' => 'required|exists:businesses,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'outlet_id' => $request->outlet_id,
            'business_id' => $request->business_id,
        ]);

        return response()->json([
            'data' => $user,
        ], 201);
    }

    //add staff
    public function addStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'outlet_id' => 'required|exists:outlets,id',
            'business_id' => 'required|exists:businesses,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'outlet_id' => $request->outlet_id,
            'business_id' => $request->business_id,
        ]);

        return response()->json([
            'data' => $user,
        ], 201);
    }

    //get user by business
    public function getUsersByBusiness(Request $request)
    {
        $user = $request->user();
        $users = User::where('business_id', $user->business_id)->get();

        return response()->json([
            'data' => $users,
        ]);
    }
}
