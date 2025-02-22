<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    //register
   public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'address' => 'required|string', // Tambahkan address agar tidak error saat membuat outlet
        ]);

        // Cek apakah email sudah terdaftar
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email sudah terdaftar, silakan gunakan email lain.'
            ], 400);
        }

        // Gunakan transaksi database
        DB::beginTransaction();

        try {
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => '210c72db-f86b-4cfe-ac69-cb0dc723df40',
            ]);

            // Buat bisnis untuk user
            $business = Business::create([
                'name' => $request->name,
                'owner_id' => $user->id,
            ]);

            // Assign business_id ke user
            $user->business_id = $business->id;

            // Buat outlet untuk bisnis
            $outlet = Outlet::create([
                'name' => $request->name . ' Pusat',
                'business_id' => $business->id,
                'address' => $request->address,
            ]);

            // Assign outlet_id ke user
            $user->outlet_id = $outlet->id;
            $user->save();

            // Commit transaksi jika semua berhasil
            DB::commit();

            // Generate token setelah transaksi sukses
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal mendaftarkan user, coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (Auth::attempt($credentials)) {
            // Cek apakah user sudah login sebelumnya
            $existingTokens = PersonalAccessToken::where('tokenable_id', $user->id)->get();

            if ($existingTokens->isNotEmpty()) {
                // Hapus semua token lama sebelum membuat token baru
                PersonalAccessToken::where('tokenable_id', $user->id)->delete();
            }

            // Buat token baru
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'data' => $user,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
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
