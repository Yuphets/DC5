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
      onSuccess: () => closeModal()
    });
  } else {
    router.post('/suppliers', form, {
      onSuccess: () => closeModal()
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