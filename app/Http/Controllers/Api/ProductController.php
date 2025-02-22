<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Services\SupabaseStorage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //add product
   public function addProduct(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'category_id' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'cost' => 'required|numeric',
        'stock' => 'required|integer',
        'business_id' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Mulai transaksi database
    return DB::transaction(function () use ($request) {
        $sku = time();

        // Buat produk
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'business_id' => $request->business_id,
            'description' => $request->description,
            'color' => $request->color ?? null,
            'price' => $request->price,
            'cost' => $request->cost,
            'stock' => $request->stock,
            'barcode' => $request->barcode ?? null,
            'sku' => $sku,
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $supabase = new SupabaseStorage();
            $imageUrl = $supabase->uploadImage($request->file('image'), 'products');

            if ($imageUrl) {
                $product->image = $imageUrl;
                $product->save();
            }
        }

        // Tambahkan stok ke setiap outlet yang terkait dengan business_id
        $outlets = Outlet::where('business_id', $request->business_id)->get();

        foreach ($outlets as $outlet) {
            Stock::create([
                'product_id' => $product->id,
                'quantity' => $request->stock,
                'outlet_id' => $outlet->id,
            ]);
        }

        return response()->json([
            'message' => 'Product added successfully',
            'data' => $product,
        ], 201);
    });
}

    //update product
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',


        ]);

        $product = Product::find($id);
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->cost = $request->cost;

        $product->barcode = $request->barcode;
        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    //edit product
    public function updateProductWithImage(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',


        ]);

        $product = Product::find($id);
        $product->name = $request->name;
        $product->category_id = $request
            ->category_id;
        $product->description = $request->description;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->cost = $request->cost;

        $product->barcode = $request->barcode;
        $product->save();

        //if image is sent
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Simpan file di storage dan dapatkan path
            $path = $image->store('public/products');

            // Simpan path relatif ke database
            $product->image = Storage::url($path);
            $product->save();
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    //get products for business
    public function getProducts(Request $request)
    {
        $products = Product::where('business_id', $request->user()->business_id)->orderBy('id', 'desc')->get();

        $products->load('category', 'stocks', 'stocks.outlet', 'stocks.product');

        return response()->json([
            'data' => $products,
        ]);
    }

    //get product by id
    public function getProduct($id)
    {
        $product = Product::find($id);

        $product->load('category');

        return response()->json([
            'data' => $product,
        ]);
    }

    //delete product
    public function deleteProduct($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
