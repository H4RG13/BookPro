import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useClientStore = defineStore('client', () => {
  const clients = ref([])

  async function fetchClients() {
    const { data } = await api.get('/clients')
    clients.value = data
  }

  async function createClient(payload) {
    const { data } = await api.post('/clients', payload)
    clients.value.push(data)
    return data
  }

  async function updateClient(id, payload) {
    const { data } = await api.put(`/clients/${id}`, payload)
    const idx = clients.value.findIndex((c) => c.id === id)
    if (idx !== -1) clients.value[idx] = data
    return data
  }

  async function deleteClient(id) {
    await api.delete(`/clients/${id}`)
    clients.value = clients.value.filter((c) => c.id !== id)
  }

  return { clients, fetchClients, createClient, updateClient, deleteClient }
})
