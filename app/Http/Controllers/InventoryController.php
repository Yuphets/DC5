<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product')->get();
        $products = Product::all();
        
        return Inertia::render('Inventories/Index', [
            'inventories' => $inventories,
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id|unique:inventories,product_id',
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string',
            'reorder_level' => 'required|integer|min:0',
            'last_restocked' => 'required|date'
        ]);

        Inventory::create($validated);
        
        return redirect()->back()->with('success', 'Inventory record created successfully');
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string',
            'reorder_level' => 'required|integer|min:0',
            'last_restocked' => 'required|date'
        ]);

        $inventory->update($validated);
        
        return redirect()->back()->with('success', 'Inventory updated successfully');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        
        return redirect()->back()->with('success', 'Inventory record deleted successfully');
    }
}