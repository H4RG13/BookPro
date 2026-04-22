<template>
  <div class="bg-white rounded-xl shadow p-6">
    <p class="text-sm text-gray-500 mb-1">{{ title }}</p>
    <p class="text-3xl font-bold text-indigo-600">{{ value }}</p>
  </div>
</template>

<script setup>
defineProps({ title: String, value: [String, Number] })
</script>
