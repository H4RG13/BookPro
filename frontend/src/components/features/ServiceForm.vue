<template>
  <form @submit.prevent="submit" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Name</label>
      <input v-model="form.name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
      <input v-model.number="form.duration" type="number" min="1" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Price ($)</label>
      <input v-model.number="form.price" type="number" min="0" step="0.01" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
    <Button type="submit" :loading="loading">Add Service</Button>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { useServiceStore } from '@/stores/service'
import Button from '@/components/ui/Button.vue'

const emit    = defineEmits(['saved'])
const store   = useServiceStore()
const error   = ref('')
const loading = ref(false)
const form    = ref({ name: '', duration: 30, price: 0 })

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    await store.createService(form.value)
    emit('saved')
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Something went wrong'
  } finally {
    loading.value = false
  }
}
</script>
