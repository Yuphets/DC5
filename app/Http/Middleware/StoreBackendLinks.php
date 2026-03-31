<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreBackendLinks
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store backend URLs for use in views
        view()->share('backendLinks', [
            'products' => [
                'index' => route('products.index'),
                'store' => route('products.store'),
                'update' => route('products.update', ['product' => '__ID__']),
                'destroy' => route('products.destroy', ['product' => '__ID__']),
            ],
            'suppliers' => [
                'index' => route('suppliers.index'),
                'store' => route('suppliers.store'),
                'update' => route('suppliers.update', ['supplier' => '__ID__']),
                'destroy' => route('suppliers.destroy', ['supplier' => '__ID__']),
            ],
            'inventories' => [
                'index' => route('inventories.index'),
                'store' => route('inventories.store'),
                'update' => route('inventories.update', ['inventory' => '__ID__']),
                'destroy' => route('inventories.destroy', ['inventory' => '__ID__']),
            ],
        ]);

        return $next($request);
    }
}