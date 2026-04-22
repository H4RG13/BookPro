<template>
  <aside class="w-64 bg-white border-r flex flex-col">
    <div class="px-6 py-5 border-b">
      <span class="text-xl font-bold text-indigo-600">BookPro</span>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-1">
      <RouterLink
        v-for="item in nav"
        :key="item.to"
        :to="item.to"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
        active-class="bg-indigo-50 text-indigo-600"
      >
        <span>{{ item.icon }}</span>
        {{ item.label }}
      </RouterLink>
    </nav>
  </aside>
</template>

<script setup>
const nav = [
  { to: '/dashboard',    label: 'Dashboard',    icon: '🏠' },
  { to: '/clients',      label: 'Clients',      icon: '👥' },
  { to: '/services',     label: 'Services',     icon: '✂️' },
  { to: '/appointments', label: 'Appointments', icon: '📅' },
  { to: '/calendar',     label: 'Calendar',     icon: '🗓' },
]
</script>
