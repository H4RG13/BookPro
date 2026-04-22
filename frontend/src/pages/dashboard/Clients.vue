<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Clients</h1>
      <Button @click="openModal()">Add Client</Button>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Phone</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="client in store.clients" :key="client.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ client.name }}</td>
            <td class="px-4 py-3">{{ client.email }}</td>
            <td class="px-4 py-3">{{ client.phone ?? '—' }}</td>
            <td class="px-4 py-3 text-right space-x-2">
              <button @click="openModal(client)" class="text-indigo-600 hover:underline">Edit</button>
              <button @click="remove(client.id)" class="text-red-500 hover:underline">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal v-if="showModal" :title="editing ? 'Edit Client' : 'Add Client'" @close="showModal = false">
      <ClientForm :initial="editing" @saved="onSaved" />
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useClientStore } from '@/stores/client'
import Button     from '@/components/ui/Button.vue'
import Modal      from '@/components/ui/Modal.vue'
import ClientForm from '@/components/features/ClientForm.vue'

const store     = useClientStore()
const showModal = ref(false)
const editing   = ref(null)

onMounted(() => store.fetchClients())

function openModal(client = null) {
  editing.value   = client
  showModal.value = true
}

function onSaved() {
  showModal.value = false
}

async function remove(id) {
  if (confirm('Delete this client?')) await store.deleteClient(id)
}
</script>
