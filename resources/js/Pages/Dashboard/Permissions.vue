<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Správa oprávnění</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Spravujte oprávnění systému
          </p>
        </div>
        <button
          @click="openPermissionModal()"
          class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Přidat oprávnění</span>
        </button>
      </div>

      <!-- Permissions List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <div v-if="Object.keys(permissions).length === 0" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Žádná oprávnění k zobrazení</p>
          </div>

          <div v-else class="space-y-6">
            <div
              v-for="(groupPermissions, groupName) in permissions"
              :key="groupName"
              class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
            >
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 uppercase tracking-wider">
                {{ groupName }}
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div
                  v-for="permission in groupPermissions"
                  :key="permission.id"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                  <div class="flex items-center space-x-2 flex-1">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-gray-700 dark:text-gray-300 font-mono">{{ permission.name }}</span>
                  </div>
                  <div class="flex items-center space-x-2 ml-2">
                    <button
                      @click="openPermissionModal(permission)"
                      class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors"
                      title="Upravit"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deletePermission(permission)"
                      class="p-1.5 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition-colors"
                      title="Smazat"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Permission Modal -->
      <Transition name="modal">
        <div
          v-if="showPermissionModal"
          class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
          @click.self="closePermissionModal"
        >
          <Transition name="modal-content">
            <div
              v-if="showPermissionModal"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full"
            >
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                {{ editingPermission ? 'Upravit oprávnění' : 'Nové oprávnění' }}
              </h2>
              <button
                @click="closePermissionModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="savePermission" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Název oprávnění <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="permissionForm.name"
                  type="text"
                  required
                  placeholder="např. view-products, create-orders"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-mono text-sm"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  Formát: akce-zdroj (např. view-products, create-orders, edit-users)
                </p>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closePermissionModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                >
                  Zrušit
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
                >
                  Uložit
                </button>
              </div>
            </form>
            </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </div>
  </DashboardLayout>
</template>

<style scoped>
/* Modal overlay transition */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Modal content transition */
.modal-content-enter-active {
  transition: all 0.3s ease;
}

.modal-content-leave-active {
  transition: all 0.25s ease;
}

.modal-content-enter-from {
  opacity: 0;
  transform: scale(0.9) translateY(-20px);
}

.modal-content-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-10px);
}
</style>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
  permissions: {
    type: Object,
    default: () => ({})
  },
  allPermissions: {
    type: Array,
    default: () => []
  }
})

const showPermissionModal = ref(false)
const editingPermission = ref(null)

const permissionForm = reactive({
  name: ''
})

const openPermissionModal = (permission = null) => {
  editingPermission.value = permission
  if (permission) {
    permissionForm.name = permission.name
  } else {
    permissionForm.name = ''
  }
  showPermissionModal.value = true
}

const closePermissionModal = () => {
  showPermissionModal.value = false
  editingPermission.value = null
  permissionForm.name = ''
}

const savePermission = () => {
  if (editingPermission.value) {
    router.put(`/dashboard/permissions/${editingPermission.value.id}`, permissionForm, {
      preserveScroll: true,
      onSuccess: () => {
        closePermissionModal()
      }
    })
  } else {
    router.post('/dashboard/permissions', permissionForm, {
      preserveScroll: true,
      onSuccess: () => {
        closePermissionModal()
      }
    })
  }
}

const deletePermission = (permission) => {
  if (confirm(`Opravdu chcete smazat oprávnění "${permission.name}"?`)) {
    router.delete(`/dashboard/permissions/${permission.id}`, {
      preserveScroll: true
    })
  }
}
</script>

