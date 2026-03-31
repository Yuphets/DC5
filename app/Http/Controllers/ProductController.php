<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['supplier', 'inventory'])->get();
        $suppliers = Supplier::all();
        
        return Inertia::render('Products/Index', [
            'products' => $products,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        Product::create($validated);
        
        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $product->update($validated);
        
        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
}