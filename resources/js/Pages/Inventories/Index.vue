<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold">Inventory</h1>
              <button
                @click="openModal()"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              >
                Add Inventory Record
              </button>
            </div>

            <!-- Inventory Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="px-4 py-2 text-left">Product</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Location</th>
                    <th class="px-4 py-2 text-left">Reorder Level</th>
                    <th class="px-4 py-2 text-left">Last Restocked</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="inventory in inventories" :key="inventory.id" class="border-b">
                    <td class="px-4 py-2">{{ inventory.product?.name }}</td>
                    <td class="px-4 py-2">{{ inventory.quantity }}</td>
                    <td class="px-4 py-2">{{ inventory.location }}</td>
                    <td class="px-4 py-2">{{ inventory.reorder_level }}</td>
                    <td class="px-4 py-2">{{ inventory.last_restocked }}</td>
                    <td class="px-4 py-2">
                      <span :class="getStatusClass(inventory)">
                        {{ getStatus(inventory) }}
                      </span>
                    </td>
                    <td class="px-4 py-2">
                      <button
                        @click="editInventory(inventory)"
                        class="text-blue-600 hover:text-blue-900 mr-2"
                      >
                        Edit
                      </button>
                      <button
                        @click="deleteInventory(inventory.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Modal -->
            <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
              <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-bold mb-4">{{ editingInventory ? 'Edit Inventory' : 'Add Inventory' }}</h3>
                <form @submit.prevent="saveInventory">
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product *</label>
                    <select
                      v-model="form.product_id"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                      :disabled="editingInventory || availableProducts.length === 0"
                      required
                    >
                      <option value="">Select Product</option>
                      <option v-for="product in availableProducts" :key="product.id" :value="product.id">
                        {{ product.name }} ({{ product.sku }})
                      </option>
                    </select>
                    <p v-if="editingInventory" class="text-xs text-gray-500 mt-1">Product cannot be changed after creation.</p>
                    <p v-if="!editingInventory && availableProducts.length === 0" class="text-xs text-red-500 mt-1">
                      No products without inventory available. You can delete an existing inventory record to add a new one.
                    </p>
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Quantity *</label>
                    <input
                      v-model="form.quantity"
                      type="number"
                      min="0"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                      required
                    >
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Location *</label>
                    <input
                      v-model="form.location"
                      type="text"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                      required
                    >
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Reorder Level *</label>
                    <input
                      v-model="form.reorder_level"
                      type="number"
                      min="0"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                      required
                    >
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Last Restocked *</label>
                    <input
                      v-model="form.last_restocked"
                      type="date"
                      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                      required
                    >
                  </div>
                  <div class="flex justify-end gap-2">
                    <button
                      type="button"
                      @click="closeModal"
                      class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                      :disabled="!editingInventory && availableProducts.length === 0"
                      :class="{ 'opacity-50 cursor-not-allowed': !editingInventory && availableProducts.length === 0 }"
                    >
                      Save
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
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

const props = defineProps({
  inventories: Array,
  products: Array
});

const showModal = ref(false);
const editingInventory = ref(null);

const availableProducts = computed(() => {
  const existingProductIds = props.inventories.map(inv => inv.product_id);
  return props.products.filter(p => !existingProductIds.includes(p.id));
});

const form = reactive({
  product_id: '',
  quantity: 0,
  location: '',
  reorder_level: 10,
  last_restocked: new Date().toISOString().slice(0,10)
});

const getStatus = (inventory) => {
  if (inventory.quantity <= 0) return 'Out of Stock';
  if (inventory.quantity <= inventory.reorder_level) return 'Low Stock';
  return 'In Stock';
};

const getStatusClass = (inventory) => {
  if (inventory.quantity <= 0) return 'text-red-600 font-bold';
  if (inventory.quantity <= inventory.reorder_level) return 'text-yellow-600 font-bold';
  return 'text-green-600';
};

const openModal = () => {
  editingInventory.value = null;
  form.product_id = '';
  form.quantity = 0;
  form.location = '';
  form.reorder_level = 10;
  form.last_restocked = new Date().toISOString().slice(0,10);
  showModal.value = true;
};

const editInventory = (inventory) => {
  editingInventory.value = inventory;
  form.product_id = inventory.product_id;
  form.quantity = inventory.quantity;
  form.location = inventory.location;
  form.reorder_level = inventory.reorder_level;
  form.last_restocked = inventory.last_restocked;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingInventory.value = null;
  Object.assign(form, {
    product_id: '',
    quantity: 0,
    location: '',
    reorder_level: 10,
    last_restocked: new Date().toISOString().slice(0,10)
  });
};

const saveInventory = () => {
  if (editingInventory.value) {
    router.put(`/inventories/${editingInventory.value.id}`, form, {
      onSuccess: () => closeModal(),
      preserveScroll: true
    });
  } else {
    router.post('/inventories', form, {
      onSuccess: () => closeModal(),
      preserveScroll: true
    });
  }
};

const deleteInventory = (id) => {
  if (confirm('Are you sure you want to delete this inventory record?')) {
    router.delete(`/inventories/${id}`);
  }
};
</script>
