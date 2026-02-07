<template>
  <DashboardLayout>
    <div class="p-4 relative">
      <!-- Loading Overlay - Teleported to body for proper z-index -->
      <Teleport to="body">
        <Transition name="fade">
          <div
            v-if="isLoading"
            class="fixed inset-0 bg-gray-500/60 dark:bg-gray-900/80 z-[9999] flex items-center justify-center"
          >
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-6 flex items-center gap-4 border border-gray-200 dark:border-gray-700">
              <!-- Spinning loader icon -->
              <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <div>
                <p class="text-gray-800 dark:text-gray-100 font-semibold">
                  <template v-if="loadingAction === 'create'">Vytvářím uživatele...</template>
                  <template v-else-if="loadingAction === 'update'">Ukládám změny...</template>
                  <template v-else-if="loadingAction === 'delete'">Mažu uživatele...</template>
                  <template v-else-if="loadingAction === 'bulk'">Provádím akci...</template>
                  <template v-else>Načítám...</template>
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Prosím čekejte</p>
              </div>
            </div>
          </div>
        </Transition>
      </Teleport>

      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
          <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
          </div>
          <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Správa uživatelů</h1>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Spravujte uživatele systému
        </p>
      </div>

      <!-- DataTable (with CRUD form) -->
      <DataTable
        :items="transformedUsers"
        :columns="columns"
        :form-fields="formFields"
        :filter-sections="filterSections"
        item-key="id"
        persist-key="users-table"
        :show-create-button="true"
        create-button-label="Přidat uživatele"
        create-title="Nový uživatel"
        edit-title="Upravit uživatele"
        save-button-label="Uložit"
        :bulk-actions="bulkActions"
        @create="handleCreate"
        @update="handleUpdate"
        @delete="handleDelete"
        @bulk-action="handleBulkAction"
      >
        <!-- Custom column for roles -->
        <template #cell-roles="{ value }">
          <div class="flex flex-wrap gap-1">
            <span
              v-for="role in value"
              :key="role"
              class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs rounded font-medium"
            >
              {{ role }}
            </span>
            <span v-if="!value || value.length === 0" class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs rounded">
              Bez role
            </span>
          </div>
        </template>

        <!-- Custom form slot for password and role selection -->
        <template #form="{ form, editing }">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Jméno <span class="text-secondary-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Jan Novák"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Email <span class="text-secondary-500">*</span>
              </label>
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="jan.novak@example.com"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Heslo <span v-if="!editing" class="text-secondary-500">*</span>
                <span v-if="editing" class="text-gray-400 font-normal">(nechte prázdné pro zachování)</span>
              </label>
              <input
                v-model="form.password"
                type="password"
                :required="!editing"
                minlength="8"
                placeholder="Minimálně 8 znaků"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Role
              </label>
              <div class="flex flex-wrap gap-2">
                <label
                  v-for="role in roles"
                  :key="role"
                  class="relative cursor-pointer"
                >
                  <input
                    type="radio"
                    :value="role"
                    v-model="form.role"
                    name="user-role"
                    class="sr-only"
                  />
                  <span
                    :class="[
                      'inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all',
                      form.role === role
                        ? 'bg-blue-600 text-white shadow-md'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    ]"
                  >
                    {{ role }}
                  </span>
                </label>
                <label
                  class="relative cursor-pointer"
                >
                  <input
                    type="radio"
                    value=""
                    v-model="form.role"
                    name="user-role"
                    class="sr-only"
                  />
                  <span
                    :class="[
                      'inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all italic',
                      form.role === ''
                        ? 'bg-gray-600 text-white shadow-md'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600'
                    ]"
                  >
                    Bez role
                  </span>
                </label>
              </div>
            </div>
          </div>
        </template>
      </DataTable>
    </div>
  </DashboardLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import DataTable from '@/Components/Dashboard/DataTable.vue'

const props = defineProps({
  users: {
    type: Array,
    default: () => []
  },
  roles: {
    type: Array,
    default: () => []
  }
})

// Loading states
const isLoading = ref(false)
const loadingAction = ref(null) // 'create' | 'update' | 'delete' | 'bulk'
const loadingItemId = ref(null)

// Transform users data to include 'role' field for form and filter compatibility
const transformedUsers = computed(() => {
  return props.users.map(user => ({
    ...user,
    // 'role' je použit pro formulář (prázdný string = bez role)
    role: user.roles && user.roles.length > 0 ? user.roles[0] : '',
    // 'roleFilter' je použit pro filtraci ('__none__' = bez role)
    roleFilter: user.roles && user.roles.length > 0 ? user.roles[0] : '__none__'
  }))
})

// Table columns configuration
const columns = [
  {
    key: 'name',
    label: 'Jméno',
    sortable: true,
    searchable: true
  },
  {
    key: 'email',
    label: 'Email',
    sortable: true,
    searchable: true
  },
  {
    key: 'roles',
    label: 'Role',
    sortable: false
  },
  {
    key: 'created_at',
    label: 'Vytvořeno',
    sortable: true
  }
]

// Form fields configuration (used for initial form state)
const formFields = [
  { key: 'name', label: 'Jméno', type: 'text', required: true, default: '' },
  { key: 'email', label: 'Email', type: 'email', required: true, default: '' },
  { key: 'password', label: 'Heslo', type: 'password', required: false, default: '' },
  { key: 'role', label: 'Role', type: 'select', required: false, default: '' }
]

// Filter sections - filtrace dle role
const filterSections = computed(() => [
  {
    id: 'role-filter',
    label: 'Role',
    filters: [
      {
        key: 'roleFilter',
        type: 'select',
        label: 'Filtrovat dle role',
        options: [
          ...props.roles.map(role => ({
            value: role,
            label: role
          })),
          { value: '__none__', label: 'Bez role' }
        ]
      }
    ]
  }
])

// Bulk actions
const bulkActions = [
  {
    key: 'delete',
    label: 'Smazat vybrané',
    icon: 'trash',
    variant: 'danger',
    confirm: true,
    confirmMessage: 'Opravdu chcete smazat vybrané uživatele?'
  }
]

// Handlers
const handleCreate = (formData) => {
  isLoading.value = true
  loadingAction.value = 'create'

  router.post('/dashboard/users', formData, {
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false
      loadingAction.value = null
    }
  })
}

const handleUpdate = ({ id, data }) => {
  isLoading.value = true
  loadingAction.value = 'update'
  loadingItemId.value = id

  router.put(`/dashboard/users/${id}`, data, {
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false
      loadingAction.value = null
      loadingItemId.value = null
    }
  })
}

const handleDelete = (user) => {
  if (confirm(`Opravdu chcete smazat uživatele "${user.name}"?`)) {
    isLoading.value = true
    loadingAction.value = 'delete'
    loadingItemId.value = user.id

    router.delete(`/dashboard/users/${user.id}`, {
      preserveScroll: true,
      onFinish: () => {
        isLoading.value = false
        loadingAction.value = null
        loadingItemId.value = null
      }
    })
  }
}

const handleBulkAction = ({ action, items }) => {
  if (action === 'delete') {
    // items jsou pole ID (ne objektů)
    const ids = Array.isArray(items) ? items : []

    if (ids.length === 0) {
      alert('Nejsou vybrány žádní uživatelé.')
      return
    }

    // Confirm dialog před smazáním
    if (!confirm(`Opravdu chcete smazat ${ids.length} vybraných uživatelů? Tato akce je nevratná!`)) {
      return
    }

    isLoading.value = true
    loadingAction.value = 'bulk'

    let completed = 0
    const totalCount = ids.length

    ids.forEach(id => {
      router.delete(`/dashboard/users/${id}`, {
        preserveScroll: true,
        onFinish: () => {
          completed++
          if (completed === totalCount) {
            isLoading.value = false
            loadingAction.value = null
            // Vyčistit uložený výběr po hromadném smazání
            clearSelectionStorage()
          }
        }
      })
    })
  }
}

// Pomocná funkce pro vyčištění výběru z localStorage
const clearSelectionStorage = () => {
  try {
    localStorage.removeItem('users-table_selectedItems')
    localStorage.removeItem('users-table_selectionMode')
  } catch (e) {
    console.warn('Failed to clear selection storage:', e)
  }
}
</script>
