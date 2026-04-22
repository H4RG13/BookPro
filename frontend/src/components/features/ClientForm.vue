<template>
  <form @submit.prevent="submit" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Name</label>
      <input v-model="form.name" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Email</label>
      <input v-model="form.email" type="email" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Phone</label>
      <input v-model="form.phone" class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
    <Button type="submit" :loading="loading">{{ initial ? 'Save Changes' : 'Add Client' }}</Button>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import { useClientStore } from '@/stores/client'
import Button from '@/components/ui/Button.vue'

const props = defineProps({ initial: { type: Object, default: null } })
const emit  = defineEmits(['saved'])

const store   = useClientStore()
const error   = ref('')
const loading = ref(false)
const form    = ref({ name: props.initial?.name ?? '', email: props.initial?.email ?? '', phone: props.initial?.phone ?? '' })

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    if (props.initial) {
      await store.updateClient(props.initial.id, form.value)
    } else {
      await store.createClient(form.value)
    }
    emit('saved')
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Something went wrong'
  } finally {
    loading.value = false
  }
}
</script>
