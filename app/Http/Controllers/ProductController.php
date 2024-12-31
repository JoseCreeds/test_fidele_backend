<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'picture' => 'image|required|max:1999'
        ]);

        //Handle File upload
        if ($request->hasFile('picture')) {
            //Get filename with extension
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //Upload Image
            $path = $request->file('picture')->storeAs('public/product_images', $fileNameToStore);
        }

        $product = new Product;
        $product->vendor_id = auth()->id();
        $product->libelle = $request->input('libelle');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->picture = $fileNameToStore;
        $product->save();

        return response(['product' => $product, 'message' => 'created'], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function getByVendorId(Product $product)
    {
        $vendor_id = auth()->id();
        $product = Product::where('vendor_id', '=', $vendor_id)->latest()->get();

        return  response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function getProductById(Product $product, int $id)
    {
        $product = Product::findOrfail($id);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, int $id)
    {


        //Handle File upload
        if ($request->hasFile('picture')) {
            //Get filename with extension
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //Upload Image
            $path = $request->file('picture')->storeAs('public/product_images', $fileNameToStore);
        }

        $product = Product::findOrFail($id);
        //Log::info('Produit trouvé pour mise à jour', $product->toArray());
        $product->vendor_id = auth()->id();
        if ($request->filled('libelle')) $product->libelle = $request->input('libelle');
        if ($request->filled('description')) $product->description = $request->input('description');
        if ($request->filled('price')) $product->price = (float) $request->input('price');
        if ($request->filled('stock')) $product->stock =  (int) $request->input('stock');
        if ($request->filled('picture')) $product->picture = $fileNameToStore;

        $product->save();
        //Log::info('Produit mis à jour', $product->toArray());

        return response(['product' => $product, 'message' => 'updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response(['message' => 'deleted'], 200);
    }

    public function getProducts()
    {
        $products = Product::where('stock', '>', 0)->get();
        return response()->json($products);
    }
};
