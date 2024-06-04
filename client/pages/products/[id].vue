<template>
  <div class="lg:grid lg:grid-cols-2 lg:space-x-10">
    <div id="preview box" class="p-6 bg-gray-800 rounded-lg ring-1 ring-gray-700">
      <div v-if="state.products" class="">
        <div class="flex justify-center">
          <img :src="product.photo || state.products.photo" alt="Product Photo" class="w-6/7 h-5/6" />
        </div>
        <div class="my-3 text-4xl text-gray-100">{{ previewProductData.name || state.products.name }}</div>
        <div class="my-3 text-lg text-gray-400">{{ previewProductData.description || state.products.description }}</div>
        <div class="my-3 text-xl text-gray-100">Quantity: {{ previewProductData.quantity || state.products.quantity }}</div>
        <div class="my-3 text-xl text-gray-100">Price: {{ previewProductData.price || state.products.price }}</div>
      </div>
      <div v-else>
        <p>Loading product details...</p>
      </div>
    </div>
    <div id="input box" class="grid justify-center grid-cols-1 px-6 bg-gray-800 rounded-lg ring-1 ring-gray-700">
      <div v-if="state.products" class="flex flex-col space-y-4">
        <label class="self-center my-4 text-lg font-medium text-gray-100">Update Product</label>
        <input v-model="product.name" @input="updatePreview" type="text" name="product name" class="h-10 px-3 py-2 rounded-lg" :placeholder="state.products.name">
        <textarea v-model="product.description" @input="updatePreview" name="description" id="" cols="30" rows="10" class="px-3 py-2 rounded-lg h-28" :placeholder="state.products.description"></textarea>
        <div class="flex justify-center space-x-4">
          <input v-model.number="product.quantity" @input="updatePreview" type="number" :placeholder="state.products.quantity" class="p-2 border-2 rounded-lg input hover:border-gray-700" />
          <input v-model.number="product.price" @input="updatePreview" type="number" :placeholder="state.products.price" class="p-2 border-2 rounded-lg mb- input hover:border-gray-700" />
        </div>
        <div>
          <button @click="cancelUpdate" class="btn w-full mb-4 ring-1 hover:bg-[#1B5D88] text-white p-2 rounded-lg">Cancel</button>
          <button @click="updateProduct" class="w-full px-2 py-2 mb-8 text-gray-100 transition-colors duration-300 ease-in-out bg-[#0072BC] rounded hover:bg-[#1B5D88] hover:text-gray-100">Update</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// State management
const state = reactive({
  id: null,
  products: null,
  errors: null
})

// Fetch product details
async function fetchProductDetails(id) {
  const params = {
    Authorization: "Bearer " + localStorage.getItem('_token'),
    Accept: "application/json"
  }

  try {
    const response = await $fetch(`http://127.0.0.1:8000/api/products/${id}`, {
      headers: params
    })

    state.products = response.data
  } catch (error) {
    state.errors = error.response
    console.error('Error:', error)

    // Navigate to login page if not logged in
    router.push('/')
  }
}

// Handle component mount
onMounted(() => {
  state.id = router.currentRoute.value.params.id
  fetchProductDetails(state.id);
})

// Product form management
const product = ref({
  name: '',
  description: '',
  quantity: '',
  price: '',
})

const previewProductData = ref({ ...product.value })

const updatePreview = () => {
  previewProductData.value = { ...product.value };
}

// Method to update product
const updateProduct = async () => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/products/${state.id}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Authorization: "Bearer " + localStorage.getItem('_token')
      },
      body: JSON.stringify(product.value)
    })

    if (!response.ok) {
      throw new Error('Failed to update product')
    }

    // Assuming successful update, you can handle response accordingly
    alert("Product successfully updated!")
    console.log('Product updated:', response.data)

    // Redirect to products page after update
    router.push('/products');

  } catch (error) {
    state.errors = error.message
    console.error('Error updating product:', error)
  }
}

const cancelUpdate = () => {
  router.push('/products');
}
</script>
