<template>
  <div class="bg-white rounded-xl shadow p-8">
    <h2 class="text-2xl font-semibold mb-6">Create account</h2>
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input v-model="form.name" type="text" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input v-model="form.email" type="email" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input v-model="form.password" type="password" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
      </div>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      <Button type="submit" :loading="loading" class="w-full">Register</Button>
    </form>
    <p class="mt-4 text-sm text-center text-gray-600">
      Already have an account? <RouterLink to="/login" class="text-indigo-600 hover:underline">Sign in</RouterLink>
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Button from '@/components/ui/Button.vue'

const auth   = useAuthStore()
const router = useRouter()

const form    = ref({ name: '', email: '', password: '', password_confirmation: '' })
const error   = ref('')
const loading = ref(false)

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    await auth.register(form.value)
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Registration failed'
  } finally {
    loading.value = false
  }
}
</script>
