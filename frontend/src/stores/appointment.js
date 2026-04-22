import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useAppointmentStore = defineStore('appointment', () => {
  const appointments = ref([])

  async function fetchAppointments() {
    const { data } = await api.get('/appointments')
    appointments.value = data
  }

  async function createAppointment(payload) {
    const { data } = await api.post('/appointments', payload)
    appointments.value.push(data)
    return data
  }

  async function updateAppointment(id, payload) {
    const { data } = await api.put(`/appointments/${id}`, payload)
    const idx = appointments.value.findIndex((a) => a.id === id)
    if (idx !== -1) appointments.value[idx] = data
    return data
  }

  return { appointments, fetchAppointments, createAppointment, updateAppointment }
})
