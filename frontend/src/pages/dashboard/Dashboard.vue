<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <Card title="Clients" :value="clientStore.clients.length" />
      <Card title="Services" :value="serviceStore.services.length" />
      <Card title="Appointments" :value="appointmentStore.appointments.length" />
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useClientStore }      from '@/stores/client'
import { useServiceStore }     from '@/stores/service'
import { useAppointmentStore } from '@/stores/appointment'
import Card from '@/components/ui/Card.vue'

const clientStore      = useClientStore()
const serviceStore     = useServiceStore()
const appointmentStore = useAppointmentStore()

onMounted(async () => {
  await Promise.all([
    clientStore.fetchClients(),
    serviceStore.fetchServices(),
    appointmentStore.fetchAppointments(),
  ])
})
</script>
