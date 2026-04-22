<template>
  <button
    v-bind="$attrs"
    :disabled="loading || $attrs.disabled"
    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
  >
    <span v-if="loading" class="mr-2 animate-spin">⟳</span>
    <slot />
  </button>
</template>

<script setup>
defineProps({ loading: { type: Boolean, default: false } })
</script>
