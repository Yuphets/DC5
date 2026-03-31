<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_suppliers' => Supplier::count(),
            'low_stock' => Inventory::whereRaw('quantity <= reorder_level')->count(),
        ];

        $recentProducts = Product::with('supplier')->latest()->take(5)->get();
        $lowStockItems = Inventory::with('product')
            ->whereRaw('quantity <= reorder_level')
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'lowStockItems' => $lowStockItems,
        ]);
    }
}