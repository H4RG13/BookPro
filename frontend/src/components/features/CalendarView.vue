<template>
  <div class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
      <button @click="prevMonth" class="px-3 py-1 rounded hover:bg-gray-100">&larr;</button>
      <h2 class="text-lg font-semibold">{{ monthLabel }}</h2>
      <button @click="nextMonth" class="px-3 py-1 rounded hover:bg-gray-100">&rarr;</button>
    </div>
    <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
      <span v-for="d in days" :key="d">{{ d }}</span>
    </div>
    <div class="grid grid-cols-7 gap-1">
      <div v-for="blank in firstDayOfMonth" :key="'b' + blank" />
      <div
        v-for="day in daysInMonth"
        :key="day"
        class="h-16 border rounded-lg p-1 text-sm relative"
        :class="{ 'bg-indigo-50 border-indigo-300': isToday(day) }"
      >
        <span class="font-medium">{{ day }}</span>
        <div v-for="appt in appointmentsOnDay(day)" :key="appt.id" class="text-xs bg-indigo-100 text-indigo-700 rounded px-1 mt-0.5 truncate">
          {{ appt.service?.name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAppointmentStore } from '@/stores/appointment'

const store = useAppointmentStore()
onMounted(() => { if (!store.appointments.length) store.fetchAppointments() })

const today   = new Date()
const current = ref(new Date(today.getFullYear(), today.getMonth(), 1))

const days         = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
const monthLabel   = computed(() => current.value.toLocaleString('default', { month: 'long', year: 'numeric' }))
const daysInMonth  = computed(() => new Date(current.value.getFullYear(), current.value.getMonth() + 1, 0).getDate())
const firstDayOfMonth = computed(() => new Date(current.value.getFullYear(), current.value.getMonth(), 1).getDay())

function prevMonth() { current.value = new Date(current.value.getFullYear(), current.value.getMonth() - 1, 1) }
function nextMonth() { current.value = new Date(current.value.getFullYear(), current.value.getMonth() + 1, 1) }
function isToday(day) {
  return today.getFullYear() === current.value.getFullYear() &&
         today.getMonth()    === current.value.getMonth() &&
         today.getDate()     === day
}
function appointmentsOnDay(day) {
  return store.appointments.filter((a) => {
    const d = new Date(a.starts_at)
    return d.getFullYear() === current.value.getFullYear() &&
           d.getMonth()    === current.value.getMonth() &&
           d.getDate()     === day
  })
}
</script>
