<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Services</h1>
      <Button @click="showModal = true">Add Service</Button>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Duration</th>
            <th class="px-4 py-3 text-left">Price</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="service in store.services" :key="service.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ service.name }}</td>
            <td class="px-4 py-3">{{ service.duration }} min</td>
            <td class="px-4 py-3">${{ service.price }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal v-if="showModal" title="Add Service" @close="showModal = false">
      <ServiceForm @saved="showModal = false" />
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useServiceStore } from '@/stores/service'
import Button      from '@/components/ui/Button.vue'
import Modal       from '@/components/ui/Modal.vue'
import ServiceForm from '@/components/features/ServiceForm.vue'

const store     = useServiceStore()
const showModal = ref(false)

onMounted(() => store.fetchServices())
</script>
