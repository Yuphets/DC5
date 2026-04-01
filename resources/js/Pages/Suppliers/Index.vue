<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h1 class="text-2xl font-bold">Suppliers</h1>
              <button
                @click="showModal = true"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              >
                Add Supplier
              </button>
            </div>

            <!-- Suppliers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="supplier in suppliers" :key="supplier.id" class="border rounded-lg p-4 shadow-sm">
                <h3 class="text-lg font-bold mb-2">{{ supplier.name }}</h3>
                <p class="text-gray-600 mb-1">Email: {{ supplier.email }}</p>
                <p class="text-gray-600 mb-1">Phone: {{ supplier.phone }}</p>
                <p class="text-gray-600 mb-1">Contact: {{ supplier.contact_person }}</p>
                <p class="text-gray-600 mb-3">Address: {{ supplier.address }}</p>
                <div class="flex justify-end gap-2">
                  <button
                    @click="editSupplier(supplier)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteSupplier(supplier.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>

            <!-- Modal (similar to products) -->
             <!-- Modal for suppliers -->
<div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <h3 class="text-lg font-bold mb-4">{{ editingSupplier ? 'Edit Supplier' : 'Add Supplier' }}</h3>
    <form @submit.prevent="saveSupplier">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
        <input v-model="form.name" type="text" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
        <input v-model="form.email" type="email" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Phone *</label>
        <input v-model="form.phone" type="text" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Address *</label>
        <textarea v-model="form.address" rows="2" class="w-full px-3 py-2 border rounded-lg" required></textarea>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Contact Person *</label>
        <input v-model="form.contact_person" type="text" class="w-full px-3 py-2 border rounded-lg" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>
            <!-- Add modal implementation here -->
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
  suppliers: Array
});

const showModal = ref(false);
const editingSupplier = ref(null);

const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  contact_person: ''
});

const editSupplier = (supplier) => {
  editingSupplier.value = supplier;
  Object.assign(form, supplier);
  showModal.value = true;
};

const saveSupplier = () => {
  if (editingSupplier.value) {
    router.put(`/suppliers/${editingSupplier.value.id}`, form, {
      onSuccess: closeModal
    });
  } else {
    router.post('/suppliers', form, {
      onSuccess: closeModal
    });
  }
};

const deleteSupplier = (id) => {
  if (confirm('Are you sure you want to delete this supplier?')) {
    router.delete(`/suppliers/${id}`);
  }
};

const closeModal = () => {
  showModal.value = false;
  editingSupplier.value = null;
  Object.assign(form, {
    name: '',
    email: '',
    phone: '',
    address: '',
    contact_person: ''
  });
};
</script>
