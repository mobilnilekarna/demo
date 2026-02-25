<template>
  <DashboardLayout>
    <div class="p-4 relative">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2">
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
              </svg>
            </div>
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Správa doprav</h1>
          </div>
          <button
            @click="showSortingModal = true"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
            </svg>
            <span>Seřadit</span>
          </button>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Spravujte způsoby dopravy
        </p>
      </div>

      <!-- DataTable (with CRUD form) -->
      <DataTable
        :items="transports"
        :columns="columns"
        :form-fields="formFields"
        :filter-sections="filterSections"
        item-key="id"
        persist-key="transports-table"
        :show-create-button="true"
        create-button-label="Přidat dopravu"
        create-title="Nová doprava"
        edit-title="Upravit dopravu"
        save-button-label="Uložit"
        :enable-pagination="false"
        @create="handleCreate"
        @update="handleUpdate"
        @delete="handleDelete"
      >
        <!-- Custom column for image -->
        <template #cell-image="{ item }">
          <img
            v-if="item.image"
            :src="item.image"
            :alt="item.name"
            class="w-10 h-10 object-contain rounded"
          />
        </template>

        <!-- Custom column for type -->
        <template #cell-type="{ value }">
          <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs rounded font-medium">
            {{ getTransportTypeLabel(value) }}
          </span>
        </template>

        <!-- Custom column for type_delivery -->
        <template #cell-type_delivery="{ value }">
          <span v-if="value" class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs rounded font-medium">
            {{ getDeliveryTypeLabel(value) }}
          </span>
          <span v-else class="text-gray-400 text-xs">-</span>
        </template>

        <!-- Custom column for price -->
        <template #cell-price="{ value }">
          {{ formatPrice(value) }}
        </template>

        <!-- Custom column for active -->
        <template #cell-active="{ value }">
          <span :class="value ? 'text-green-600' : 'text-gray-400'" class="font-medium">
            {{ value ? 'Ano' : 'Ne' }}
          </span>
        </template>

        <!-- Custom form -->
        <template #form="{ form, editing }">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Název <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Název dopravy"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Popis
              </label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="Popis dopravy"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Typ dopravy <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.type"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                  <option value="">Vyberte typ</option>
                  <option v-for="type in transportTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Typ doručení
                </label>
                <select
                  v-model="form.type_delivery"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                  <option :value="null">-</option>
                  <option v-for="type in deliveryTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                  </option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Cena (Kč) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  placeholder="0.00"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Doprava zdarma od (Kč)
                </label>
                <input
                  v-model.number="form.free_from"
                  type="number"
                  step="0.01"
                  min="0"
                  placeholder="0.00"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Pořadí
                </label>
                <input
                  v-model.number="form.order"
                  type="number"
                  min="0"
                  placeholder="0"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="flex items-center space-x-2 mt-7">
                  <input
                    v-model="form.active"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                  />
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktivní</span>
                </label>
              </div>
            </div>
          </div>
        </template>

        <!-- Custom actions -->
        <template #actions="{ item }">
          <div class="flex justify-end gap-2">
            <button
              @click="router.visit(`/dashboard/transports/${item.id}/edit`)"
              class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
              title="Upravit"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
          </div>
        </template>
      </DataTable>

      <!-- Sorting Modal -->
      <SortingModal
        :show="showSortingModal"
        :items="transports"
        item-key="id"
        title="Seřadit dopravy"
        description="Přetáhněte položky pro změnu jejich pořadí. Po uložení se pořadí použije ve výpisu."
        @close="showSortingModal = false"
        @save="handleSortingSave"
      >
        <template #item="{ item, index }">
          <div class="font-medium text-gray-900 dark:text-gray-100">
            {{ item.name }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ getTransportTypeLabel(item.type) }}
          </div>
        </template>
      </SortingModal>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from '@/Components/Dashboard/DataTable.vue'
import SortingModal from '@/Components/Dashboard/SortingModal.vue'
import { useToast } from '@/Composables/useToast'

const toast = useToast()
const showSortingModal = ref(false)

const props = defineProps({
  transports: {
    type: Array,
    default: () => []
  },
  transportTypes: {
    type: Array,
    default: () => []
  },
  deliveryTypes: {
    type: Array,
    default: () => []
  }
})

// Table columns configuration
const columns = [
  {
    key: 'image',
    label: 'Logo',
    sortable: false
  },
  {
    key: 'name',
    label: 'Název',
    sortable: true,
    searchable: true
  },
  {
    key: 'type',
    label: 'Typ',
    sortable: true
  },
  {
    key: 'type_delivery',
    label: 'Typ doručení',
    sortable: true
  },
  {
    key: 'price',
    label: 'Cena',
    sortable: true
  },
  {
    key: 'free_from',
    label: 'Zdarma od',
    sortable: true
  },
  {
    key: 'order',
    label: 'Pořadí',
    sortable: true
  },
  {
    key: 'active',
    label: 'Aktivní',
    sortable: true
  }
]

// Form fields configuration
const formFields = [
  { key: 'name', label: 'Název', type: 'text', required: true, default: '' },
  { key: 'description', label: 'Popis', type: 'textarea', required: false, default: '' },
  { key: 'type', label: 'Typ', type: 'select', required: true, default: '' },
  { key: 'type_delivery', label: 'Typ doručení', type: 'select', required: false, default: null },
  { key: 'price', label: 'Cena', type: 'number', required: true, default: 0 },
  { key: 'free_from', label: 'Doprava zdarma od', type: 'number', required: false, default: null },
  { key: 'order', label: 'Pořadí', type: 'number', required: false, default: 0 },
  { key: 'active', label: 'Aktivní', type: 'checkbox', required: false, default: true }
]

// Filter sections
const filterSections = computed(() => [
  {
    id: 'filters',
    label: 'Filtry',
    filters: [
      {
        key: 'type',
        type: 'select',
        label: 'Typ dopravy',
        options: [
          { value: '', label: 'Vše' },
          ...props.transportTypes.map(type => ({
            value: type.value,
            label: type.label
          }))
        ]
      },
      {
        key: 'active',
        type: 'select',
        label: 'Aktivní',
        options: [
          { value: '', label: 'Vše' },
          { value: '1', label: 'Ano' },
          { value: '0', label: 'Ne' }
        ]
      }
    ]
  }
])

// Helper functions
const getTransportTypeLabel = (value) => {
  const type = props.transportTypes.find(t => t.value === value)
  return type ? type.label : value
}

const getDeliveryTypeLabel = (value) => {
  const type = props.deliveryTypes.find(t => t.value === value)
  return type ? type.label : value
}

const formatPrice = (value) => {
  if (value === null || value === undefined) return '-'
  return new Intl.NumberFormat('cs-CZ', {
    style: 'currency',
    currency: 'CZK'
  }).format(value)
}

// Handlers
const handleCreate = (formData) => {
  router.post('/dashboard/transports', formData, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Vytvořeno', 'Doprava byla úspěšně vytvořena.')
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se vytvořit dopravu.')
    }
  })
}

const handleUpdate = ({ id, data }) => {
  router.put(`/dashboard/transports/${id}`, data, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Uloženo', 'Doprava byla úspěšně aktualizována.')
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se uložit změny dopravy.')
    }
  })
}

const handleDelete = (transport) => {
  if (confirm(`Opravdu chcete smazat dopravu "${transport.name}"?`)) {
    router.delete(`/dashboard/transports/${transport.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        toast.success('Smazáno', `Doprava "${transport.name}" byla úspěšně smazána.`)
      },
      onError: (errors) => {
        toast.error('Chyba', 'Nepodařilo se smazat dopravu.')
      }
    })
  }
}

const handleSortingSave = (itemsWithOrder) => {
  router.post('/dashboard/transports/order', {
    items: itemsWithOrder
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Uloženo', 'Pořadí doprav bylo úspěšně aktualizováno.')
      showSortingModal.value = false
      router.reload({ only: ['transports'] })
    },
    onError: (errors) => {
      toast.error('Chyba', 'Nepodařilo se uložit pořadí doprav.')
      showSortingModal.value = false
    }
  })
}
</script>
