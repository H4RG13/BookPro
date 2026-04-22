<template>
  <header class="bg-white border-b px-6 py-3 flex items-center justify-end">
    <div class="flex items-center gap-4">
      <span class="text-sm text-gray-600">{{ auth.user?.name }}</span>
      <button @click="handleLogout" class="text-sm text-red-500 hover:underline">Logout</button>
    </div>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth   = useAuthStore()
const router = useRouter()

async function handleLogout() {
  await auth.logout()
  router.push('/login')
}
</script>
