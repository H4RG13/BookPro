<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Appointments</h1>
      <Button @click="showModal = true">Book Appointment</Button>
    </div>

    <!-- Status tabs -->
    <div class="flex gap-1 mb-4 bg-gray-100 p-1 rounded-lg w-fit">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        @click="switchTab(tab.key)"
        class="px-4 py-1.5 rounded-md text-sm font-medium transition"
        :class="activeTab === tab.key
          ? 'bg-white shadow text-gray-900'
          : 'text-gray-500 hover:text-gray-700'"
      >
        {{ tab.label }}
        <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full" :class="tab.badge">
          {{ tabCount(tab.key) }}
        </span>
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Client</th>
            <th class="px-4 py-3 text-left">Service</th>
            <th class="px-4 py-3 text-left">Date &amp; Time</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Notes</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-if="rows.length === 0">
            <td colspan="6" class="px-4 py-8 text-center text-gray-400">No appointments found.</td>
          </tr>
          <tr v-for="appt in rows" :key="appt.id" class="hover:bg-gray-50">
            <td class="px-4 py-3 font-medium">{{ appt.client?.name }}</td>
            <td class="px-4 py-3">{{ appt.service?.name }}</td>
            <td class="px-4 py-3">
              <span>{{ formatDate(appt.starts_at) }}</span>
              <div v-if="appt.rescheduled_from" class="text-xs text-amber-600 mt-0.5">
                Rescheduled from {{ formatDate(appt.rescheduled_from) }}
              </div>
            </td>
            <td class="px-4 py-3">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statusClass(appt.status)">
                {{ appt.status }}
              </span>
            </td>
            <td class="px-4 py-3 text-gray-500">{{ appt.notes ?? '—' }}</td>
            <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
              <!-- Active appointment actions -->
              <template v-if="activeTab !== 'deleted'">
                <button
                  v-if="appt.status === 'pending'"
                  @click="changeStatus(appt, 'confirmed')"
                  class="text-xs text-indigo-600 hover:underline"
                >Confirm</button>
                <button
                  v-if="appt.status !== 'cancelled' && appt.status !== 'completed'"
                  @click="changeStatus(appt, 'cancelled')"
                  class="text-xs text-orange-500 hover:underline"
                >Cancel</button>
                <button
                  v-if="appt.status !== 'completed'"
                  @click="openReschedule(appt)"
                  class="text-xs text-blue-500 hover:underline"
                >Reschedule</button>
                <button
                  @click="remove(appt.id)"
                  class="text-xs text-red-500 hover:underline"
                >Delete</button>
              </template>
              <!-- Deleted appointment actions -->
              <template v-else>
                <button @click="restore(appt.id)" class="text-xs text-green-600 hover:underline">Restore</button>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Book modal -->
    <Modal v-if="showModal" title="Book Appointment" @close="showModal = false">
      <AppointmentForm @saved="onBooked" />
    </Modal>

    <!-- Reschedule modal -->
    <Modal v-if="showReschedule" title="Reschedule Appointment" @close="showReschedule = false">
      <form @submit.prevent="submitReschedule" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">New Date &amp; Time</label>
          <input v-model="newStartsAt" type="datetime-local" required
            class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
        </div>
        <Button type="submit" :loading="rescheduling">Save</Button>
      </form>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAppointmentStore } from '@/stores/appointment'
import Button          from '@/components/ui/Button.vue'
import Modal           from '@/components/ui/Modal.vue'
import AppointmentForm from '@/components/features/AppointmentForm.vue'
import { formatDate }  from '@/utils/helpers'

const store = useAppointmentStore()

const showModal     = ref(false)
const showReschedule = ref(false)
const rescheduling  = ref(false)
const rescheduleTarget = ref(null)
const newStartsAt   = ref('')
const activeTab     = ref('all')

const tabs = [
  { key: 'all',         label: 'All',         badge: 'bg-gray-200 text-gray-700' },
  { key: 'pending',     label: 'Pending',     badge: 'bg-yellow-100 text-yellow-700' },
  { key: 'confirmed',   label: 'Confirmed',   badge: 'bg-indigo-100 text-indigo-700' },
  { key: 'completed',   label: 'Completed',   badge: 'bg-green-100 text-green-700' },
  { key: 'cancelled',   label: 'Cancelled',   badge: 'bg-orange-100 text-orange-700' },
  { key: 'rescheduled', label: 'Rescheduled', badge: 'bg-amber-100 text-amber-700' },
  { key: 'deleted',     label: 'Deleted',     badge: 'bg-red-100 text-red-700' },
]

const rows = computed(() => {
  if (activeTab.value === 'deleted') return store.trashed
  return store.appointments
})

function tabCount(key) {
  if (key === 'deleted')     return store.trashed.length
  if (key === 'all')         return store.appointments.length
  if (key === 'rescheduled') return store.appointments.filter((a) => a.rescheduled_from).length
  return store.appointments.filter((a) => a.status === key).length
}

function statusClass(status) {
  return {
    pending:   'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-indigo-100 text-indigo-700',
    completed: 'bg-green-100 text-green-700',
    cancelled: 'bg-orange-100 text-orange-700',
  }[status] ?? 'bg-gray-100 text-gray-600'
}

async function switchTab(key) {
  activeTab.value = key
  if (key === 'deleted') {
    await store.fetchTrashed()
  } else if (key === 'rescheduled') {
    await store.fetchAppointments('rescheduled')
  } else if (key === 'all') {
    await store.fetchAppointments()
  } else {
    await store.fetchAppointments(key)
  }
}

async function changeStatus(appt, status) {
  await store.updateAppointment(appt.id, { status })
  await store.fetchAppointments(activeTab.value === 'all' ? null : activeTab.value)
}

function openReschedule(appt) {
  rescheduleTarget.value = appt
  newStartsAt.value = appt.starts_at?.slice(0, 16) ?? ''
  showReschedule.value = true
}

async function submitReschedule() {
  rescheduling.value = true
  try {
    await store.updateAppointment(rescheduleTarget.value.id, { starts_at: newStartsAt.value })
    showReschedule.value = false
    await store.fetchAppointments(activeTab.value === 'all' ? null : activeTab.value)
  } finally {
    rescheduling.value = false
  }
}

async function remove(id) {
  if (confirm('Delete this appointment?')) {
    await store.deleteAppointment(id)
  }
}

async function restore(id) {
  await store.restoreAppointment(id)
  await store.fetchTrashed()
}

async function onBooked() {
  showModal.value = false
  await store.fetchAppointments(activeTab.value === 'all' ? null : activeTab.value)
}

onMounted(() => store.fetchAppointments())
</script>
