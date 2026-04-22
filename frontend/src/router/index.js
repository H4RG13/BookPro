import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/',
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      { path: 'login',    name: 'Login',    component: () => import('@/pages/auth/Login.vue') },
      { path: 'register', name: 'Register', component: () => import('@/pages/auth/Register.vue') },
    ],
  },
  {
    path: '/',
    component: () => import('@/layouts/DashboardLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: 'dashboard',    name: 'Dashboard',    component: () => import('@/pages/dashboard/Dashboard.vue') },
      { path: 'clients',      name: 'Clients',      component: () => import('@/pages/dashboard/Clients.vue') },
      { path: 'services',     name: 'Services',     component: () => import('@/pages/dashboard/Services.vue') },
      { path: 'appointments', name: 'Appointments', component: () => import('@/pages/dashboard/Appointments.vue') },
      { path: 'calendar',     name: 'Calendar',     component: () => import('@/pages/dashboard/Calendar.vue') },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isAuthenticated()) {
    return { name: 'Login' }
  }
})

export default router
