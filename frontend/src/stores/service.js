import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useServiceStore = defineStore('service', () => {
  const services = ref([])

  async function fetchServices() {
    const { data } = await api.get('/services')
    services.value = data
  }

  async function createService(payload) {
    const { data } = await api.post('/services', payload)
    services.value.push(data)
    return data
  }

  return { services, fetchServices, createService }
})
