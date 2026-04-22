<template>
  <form @submit.prevent="submit" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Client</label>
      <select v-model="form.client_id" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Select client</option>
        <option v-for="c in clientStore.clients" :key="c.id" :value="c.id">{{ c.name }}</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Service</label>
      <select v-model="form.service_id" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Select service</option>
        <option v-for="s in serviceStore.services" :key="s.id" :value="s.id">{{ s.name }} ({{ s.duration }} min)</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Date &amp; Time</label>
      <input v-model="form.starts_at" type="datetime-local" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Notes</label>
      <textarea v-model="form.notes" rows="3" placeholder="Optional notes..." class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none" />
    </div>
    <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
    <Button type="submit" :loading="loading">Book Appointment</Button>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAppointmentStore } from '@/stores/appointment'
import { useClientStore }      from '@/stores/client'
import { useServiceStore }     from '@/stores/service'
import Button from '@/components/ui/Button.vue'

const emit             = defineEmits(['saved'])
const appointmentStore = useAppointmentStore()
const clientStore      = useClientStore()
const serviceStore     = useServiceStore()

const error   = ref('')
const loading = ref(false)
const form    = ref({ client_id: '', service_id: '', starts_at: '', notes: '' })

onMounted(() => {
  if (!clientStore.clients.length)  clientStore.fetchClients()
  if (!serviceStore.services.length) serviceStore.fetchServices()
})

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    await appointmentStore.createAppointment(form.value)
    emit('saved')
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Something went wrong'
  } finally {
    loading.value = false
  }
}
</script>
