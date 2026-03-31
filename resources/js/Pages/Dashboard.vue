<template>
    <Layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back! Here's what's happening with your inventory.</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Total Products</p>
                                <p class="text-3xl font-bold text-gray-800">{{ stats?.total_products ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Total Suppliers</p>
                                <p class="text-3xl font-bold text-gray-800">{{ stats?.total_suppliers ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Low Stock Items</p>
                                <p class="text-3xl font-bold text-gray-800">{{ stats?.low_stock ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two-column layout for recent products and low stock items -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Products -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Products</h2>
                        </div>
                        <div class="p-6">
                            <div v-if="!recentProducts?.length" class="text-center text-gray-500 py-4">
                                No products added yet.
                            </div>
                            <ul v-else class="divide-y divide-gray-200">
                                <li v-for="product in recentProducts" :key="product.id" class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ product.name }}</p>
                                        <p class="text-sm text-gray-500">{{ product.supplier?.name || 'No supplier' }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-600">${{ formatPrice(product.price) }}</span>
                                </li>
                            </ul>
                            <div class="mt-4 text-right">
    <Link href="/products" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View all products →</Link>
</div>
                        </div>
                    </div>

                    <!-- Low Stock Alerts -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800">Low Stock Alerts</h2>
                        </div>
                        <div class="p-6">
                            <div v-if="!lowStockItems?.length" class="text-center text-gray-500 py-4">
                                All stock levels are healthy!
                            </div>
                            <ul v-else class="divide-y divide-gray-200">
                                <li v-for="item in lowStockItems" :key="item.id" class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ item.product?.name || 'Unknown Product' }}</p>
                                        <p class="text-sm text-gray-500">Reorder level: {{ item.reorder_level }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-red-600">{{ item.quantity }} in stock</span>
                                </li>
                            </ul>
                            <div class="mt-4 text-right">
    <Link href="/inventories" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Manage inventory →</Link>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from '../Layouts/Layout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentProducts: Array,
    lowStockItems: Array
});

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};
</script>