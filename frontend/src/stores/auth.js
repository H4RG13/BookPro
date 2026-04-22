import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/axios'

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(null)
  const token = ref(localStorage.getItem('token'))

  const isAuthenticated = () => !!token.value

  async function login(credentials) {
    const { data } = await api.post('/login', credentials)
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('token', data.token)
  }

  async function register(payload) {
    const { data } = await api.post('/register', payload)
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('token', data.token)
  }

  async function logout() {
    await api.post('/logout')
    token.value = null
    user.value  = null
    localStorage.removeItem('token')
  }

  return { user, token, isAuthenticated, login, register, logout }
})
