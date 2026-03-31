<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold">Products Management</h1>
              <button 
                @click="openModal()"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200"
              >
                + Add Product
              </button>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">SKU</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Supplier</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Price</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stock</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="product in products" :key="product.id" class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ product.name }}</td>
                    <td class="px-4 py-3"><span class="font-mono text-sm">{{ product.sku }}</span></td>
                    <td class="px-4 py-3">{{ product.supplier?.name }}</td>
                    <td class="px-4 py-3">${{ parseFloat(product.price).toFixed(2) }}</td>
                    <td class="px-4 py-3">
                      <span :class="getStockClass(product.inventory?.quantity)">
                        {{ product.inventory?.quantity || 0 }} units
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button 
                        @click="openModal(product)"
                        class="text-blue-600 hover:text-blue-900 mr-3 text-sm"
                      >
                        Edit
                      </button>
                      <button 
                        @click="deleteProduct(product.id)"
                        class="text-red-600 hover:text-red-900 text-sm"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                  <tr v-if="products.length === 0">
                    <td colspan="6" class="text-center py-8 text-gray-500">No products found. Click "Add Product" to create one.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Modal -->
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="flex justify-between items-center p-6 border-b">
                  <h3 class="text-xl font-bold">{{ editingProduct ? 'Edit Product' : 'Add New Product' }}</h3>
                  <button @click="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form @submit.prevent="saveProduct" class="p-6">
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                      <input 
                        v-model="form.name" 
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                      <input 
                        v-model="form.sku" 
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                      <textarea 
                        v-model="form.description" 
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                      ></textarea>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                      <input 
                        v-model="form.price" 
                        type="number" 
                        step="0.01" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Supplier *</label>
                      <select 
                        v-model="form.supplier_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                      >
                        <option value="">Select Supplier</option>
                        <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                          {{ supplier.name }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="flex justify-end gap-3 mt-6">
                    <button 
                      type="button"
                      @click="closeModal"
                      class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                    >
                      Cancel
                    </button>
                    <button 
                      type="submit"
                      class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                    >
                      {{ editingProduct ? 'Update' : 'Create' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

const props = defineProps({
  products: Array,
  suppliers: Array
});

const showModal = ref(false);
const editingProduct = ref(null);

const form = reactive({
  name: '',
  sku: '',
  description: '',
  price: '',
  supplier_id: ''
});

const getStockClass = (quantity) => {
  if (!quantity || quantity === 0) return 'text-red-600 font-bold';
  if (quantity < 10) return 'text-yellow-600 font-bold';
  return 'text-green-600';
};

const openModal = (product = null) => {
  if (product) {
    editingProduct.value = product;
    form.name = product.name;
    form.sku = product.sku;
    form.description = product.description;
    form.price = product.price;
    form.supplier_id = product.supplier_id;
  } else {
    editingProduct.value = null;
    Object.assign(form, {
      name: '',
      sku: '',
      description: '',
      price: '',
      supplier_id: ''
    });
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingProduct.value = null;
};

const saveProduct = () => {
  if (editingProduct.value) {
    router.put(`/products/${editingProduct.value.id}`, form, {
      onSuccess: () => closeModal(),
      preserveScroll: true
    });
  } else {
    router.post('/products', form, {
      onSuccess: () => closeModal(),
      preserveScroll: true
    });
  }
};

const deleteProduct = (id) => {
  if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
    router.delete(`/products/${id}`);
  }
};
</script>