<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold">Inventory</h1>
              <button 
                @click="showModal = true"
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
  inventories: Array,
  products: Array
});

const showModal = ref(false);
const editingInventory = ref(null);

const form = reactive({
  product_id: '',
  quantity: '',
  location: '',
  reorder_level: '',
  last_restocked: ''
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

const editInventory = (inventory) => {
  editingInventory.value = inventory;
  form.product_id = inventory.product_id;
  form.quantity = inventory.quantity;
  form.location = inventory.location;
  form.reorder_level = inventory.reorder_level;
  form.last_restocked = inventory.last_restocked;
  showModal.value = true;
};

const saveInventory = () => {
  if (editingInventory.value) {
    router.put(`/inventories/${editingInventory.value.id}`, form, {
      onSuccess: () => closeModal()
    });
  } else {
    router.post('/inventories', form, {
      onSuccess: () => closeModal()
    });
  }
};

const deleteInventory = (id) => {
  if (confirm('Are you sure you want to delete this inventory record?')) {
    router.delete(`/inventories/${id}`);
  }
};

const closeModal = () => {
  showModal.value = false;
  editingInventory.value = null;
  Object.assign(form, {
    product_id: '',
    quantity: '',
    location: '',
    reorder_level: '',
    last_restocked: ''
  });
};
</script>