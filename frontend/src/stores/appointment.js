import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useAppointmentStore = defineStore('appointment', () => {
  const appointments = ref([])
  const trashed      = ref([])

  async function fetchAppointments(status = null) {
    const params = status ? { status } : {}
    const { data } = await api.get('/appointments', { params })
    appointments.value = data
  }

  async function fetchTrashed() {
    const { data } = await api.get('/appointments/trashed')
    trashed.value = data
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

  async function deleteAppointment(id) {
    await api.delete(`/appointments/${id}`)
    appointments.value = appointments.value.filter((a) => a.id !== id)
  }

  async function restoreAppointment(id) {
    const { data } = await api.post(`/appointments/${id}/restore`)
    trashed.value = trashed.value.filter((a) => a.id !== id)
    appointments.value.push(data)
    return data
  }

  return {
    appointments,
    trashed,
    fetchAppointments,
    fetchTrashed,
    createAppointment,
    updateAppointment,
    deleteAppointment,
    restoreAppointment,
  }
})
