<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Appointments</h1>
      <Button @click="showModal = true">Book Appointment</Button>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Client</th>
            <th class="px-4 py-3 text-left">Service</th>
            <th class="px-4 py-3 text-left">Date &amp; Time</th>
            <th class="px-4 py-3 text-left">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="appt in store.appointments" :key="appt.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ appt.client?.name }}</td>
            <td class="px-4 py-3">{{ appt.service?.name }}</td>
            <td class="px-4 py-3">{{ formatDate(appt.starts_at) }}</td>
            <td class="px-4 py-3 capitalize">{{ appt.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal v-if="showModal" title="Book Appointment" @close="showModal = false">
      <AppointmentForm @saved="showModal = false" />
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAppointmentStore } from '@/stores/appointment'
import Button          from '@/components/ui/Button.vue'
import Modal           from '@/components/ui/Modal.vue'
import AppointmentForm from '@/components/features/AppointmentForm.vue'
import { formatDate }  from '@/utils/helpers'

const store     = useAppointmentStore()
const showModal = ref(false)

onMounted(() => store.fetchAppointments())
</script>
