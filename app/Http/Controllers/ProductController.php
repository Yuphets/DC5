<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
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
            'supplier_id' => 'required|exists:suppliers,id',
            // Inventory fields
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string',
            'reorder_level' => 'required|integer|min:0',
            'last_restocked' => 'required|date'
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'supplier_id' => $validated['supplier_id']
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'location' => $validated['location'],
            'reorder_level' => $validated['reorder_level'],
            'last_restocked' => $validated['last_restocked']
        ]);

        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            // Inventory fields
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string',
            'reorder_level' => 'required|integer|min:0',
            'last_restocked' => 'required|date'
        ]);

        $product->update([
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'supplier_id' => $validated['supplier_id']
        ]);

        // Update or create inventory
        if ($product->inventory) {
            $product->inventory->update([
                'quantity' => $validated['quantity'],
                'location' => $validated['location'],
                'reorder_level' => $validated['reorder_level'],
                'last_restocked' => $validated['last_restocked']
            ]);
        } else {
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'location' => $validated['location'],
                'reorder_level' => $validated['reorder_level'],
                'last_restocked' => $validated['last_restocked']
            ]);
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
