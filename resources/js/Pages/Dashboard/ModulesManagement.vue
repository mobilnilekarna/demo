<template>
  <DashboardLayout>
    <div class="p-4">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Správa modulů</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Spravujte moduly dashboardu - upravujte jejich vlastnosti a pořadí
          </p>
        </div>
        <button
          v-if="activeTab === 'modules'"
          @click="openCreateModal()"
          class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span>Přidat modul</span>
        </button>
      </div>

      <!-- Tabs -->
      <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8">
          <button
            @click="activeTab = 'modules'"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
              activeTab === 'modules'
                ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
            ]"
          >
            Moduly
          </button>
          <button
            @click="activeTab = 'access'"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
              activeTab === 'access'
                ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
            ]"
          >
            Přístupy k modulům
          </button>
        </nav>
      </div>

      <!-- Modules Tab -->
      <div v-if="activeTab === 'modules'" class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <div v-if="modules.length === 0" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Žádné moduly k zobrazení</p>
          </div>

          <div v-else ref="modulesContainerRef" class="space-y-2">
            <div
              v-for="(module, index) in modules"
              :key="`module-${module.id}`"
              class="module-item-wrapper"
            >
              <module-item
                :module="module"
                :index="index"
                :parent-path="[]"
                :siblings="modules"
                :editing-module="editingModule"
                @edit="editModule"
                @update-rank="handleUpdateRank"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Edit/Create Modal -->
      <Transition name="modal">
        <div
          v-if="editingModule || isCreating"
          class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
          @click.self="closeEditModal"
        >
          <Transition name="modal-content">
            <div
              v-if="editingModule || isCreating"
              class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
            >
              <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                {{ isCreating ? 'Nový modul' : 'Upravit modul' }}
              </h2>
              <button
                @click="closeEditModal"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="saveModule" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Název <span class="text-secondary-500">*</span>
                </label>
                <input
                  v-model="editForm.name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Slug
                  <span v-if="!isCreating" class="text-secondary-500">*</span>
                  <span v-if="isCreating" class="text-xs text-gray-500 ml-2">(automaticky generováno z názvu)</span>
                </label>
                <input
                  v-model="editForm.slug"
                  type="text"
                  :required="!isCreating"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  placeholder="slug-se-automaticky-vygeneruje"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Popis
                </label>
                <textarea
                  v-model="editForm.description"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Ikona (HTML)
                </label>
                <textarea
                  v-model="editForm.icon"
                  rows="2"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-mono text-sm"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Route
                  <span v-if="isCreating" class="text-xs text-gray-500 ml-2">(automaticky generováno ze slug)</span>
                </label>
                <input
                  v-model="editForm.route"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                  placeholder="např. dashboard.module.slug"
                />
              </div>

              <div v-if="isCreating">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Nadřazený modul (volitelné)
                </label>
                <select
                  v-model.number="editForm.parent_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
                  <option :value="null">Žádný (hlavní modul)</option>
                  <option
                    v-for="module in getAllModules(modules)"
                    :key="module.id"
                    :value="module.id"
                  >
                    {{ module.name }} ({{ module.slug }})
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Rank (pořadí)
                </label>
                <input
                  v-model.number="editForm.rank"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
              </div>

              <div class="flex items-center">
                <input
                  v-model="editForm.active"
                  type="checkbox"
                  id="active"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
                <label for="active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                  Aktivní
                </label>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closeEditModal"
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

      <!-- Access Management Tab -->
      <div v-if="activeTab === 'access'" class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
          <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
              Přiřazení modulů k rolím
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Vyberte moduly, které budou dostupné pro jednotlivé role. Všichni uživatelé s danou rolí automaticky získají přístup k vybraným modulům.
            </p>
          </div>

          <div v-if="roles.length === 0" class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Žádné role k zobrazení</p>
          </div>

          <div v-else class="space-y-2">
            <!-- Accordion pro každou roli -->
            <div
              v-for="role in roles"
              :key="role.id"
              class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden"
            >
              <!-- Role Header -->
              <button
                @click="toggleRoleAccordion(role.id)"
                class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
              >
                <div class="flex items-center space-x-3">
                  <svg
                    :class="[
                      'w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform',
                      expandedRoles[role.id] ? 'rotate-90' : ''
                    ]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                  <div class="text-left">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                      {{ role.name }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ role.users_count }} {{ role.users_count === 1 ? 'uživatel' : role.users_count < 5 ? 'uživatelé' : 'uživatelů' }}
                    </p>
                  </div>
                </div>
                <button
                  v-if="expandedRoles[role.id]"
                  @click.stop="saveModulesForRole(role)"
                  :disabled="savingRole === role.id"
                  class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1"
                >
                  <span v-if="savingRole === role.id" class="animate-spin">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                  </span>
                  <span>{{ savingRole === role.id ? 'Ukládání...' : 'Uložit' }}</span>
                </button>
              </button>

              <!-- Role Content - Accordion -->
              <div
                v-show="expandedRoles[role.id]"
                class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50"
              >
                <div class="p-4 space-y-2">
                  <!-- Sekce modulů podle parent_id -->
                  <div
                    v-for="section in getModuleSections()"
                    :key="section.id"
                    class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden"
                  >
                    <!-- Section Header -->
                    <button
                      @click="toggleModuleSection(role.id, section.id)"
                      class="w-full px-3 py-2 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-left"
                    >
                      <div class="flex items-center space-x-2">
                        <svg
                          :class="[
                            'w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform',
                            expandedSections[`${role.id}-${section.id}`] ? 'rotate-90' : ''
                          ]"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                          {{ section.name }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                          ({{ getModulesInSection(section.id).length }})
                        </span>
                      </div>
                    </button>

                    <!-- Section Content -->
                    <div
                      v-show="expandedSections[`${role.id}-${section.id}`]"
                      class="px-3 py-2 bg-white dark:bg-gray-800 space-y-1.5 border-t border-gray-200 dark:border-gray-700"
                    >
                      <div
                        v-for="module in getModulesInSection(section.id)"
                        :key="module.id"
                        class="flex items-center justify-between py-1"
                      >
                        <label
                          :for="`role-${role.id}-module-${module.id}`"
                          class="flex-1 text-xs text-gray-700 dark:text-gray-300 cursor-pointer"
                        >
                          {{ module.name }}
                        </label>
                        <!-- Toggle Switch -->
                        <label
                          :for="`role-${role.id}-module-${module.id}`"
                          class="relative inline-flex items-center cursor-pointer"
                        >
                          <input
                            :id="`role-${role.id}-module-${module.id}`"
                            type="checkbox"
                            :checked="isModuleAssignedToRole(role.id, module.id)"
                            @change="toggleModuleForRole(role.id, module.id, $event.target.checked)"
                            class="sr-only peer"
                          />
                          <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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
import { ref, reactive, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
import Sortable from 'sortablejs'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import ModuleItem from './Components/ModuleItem.vue'

const props = defineProps({
  modules: {
    type: Array,
    default: () => []
  },
  roles: {
    type: Array,
    default: () => []
  },
  allModules: {
    type: Array,
    default: () => []
  },
  modulesByRole: {
    type: Object,
    default: () => ({})
  }
})

const modulesContainerRef = ref(null)
let sortableInstance = null

const activeTab = ref('modules')
const editingModule = ref(null)
const isCreating = ref(false)
const savingRole = ref(null)
const roleModuleSelections = ref({})
const expandedRoles = ref({})
const expandedSections = ref({})
const autoGeneratedSlug = ref('')
const editForm = reactive({
  name: '',
  slug: '',
  description: '',
  icon: '',
  route: '',
  parent_id: null,
  active: true,
  rank: 0
})

// Funkce pro převod názvu na slug
const generateSlug = (text) => {
  return text
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '') // odstranit diakritiku
    .replace(/[^a-z0-9]+/g, '-') // nahradit nealfanumerické znaky pomlčkou
    .replace(/^-+|-+$/g, '') // odstranit pomlčky na začátku a konci
}

// Funkce pro generování route z slug
const generateRoute = (slug) => {
  if (!slug) return ''
  return `dashboard.module.${slug}`
}

// Sledovat změny názvu a automaticky generovat slug při vytváření
watch(() => editForm.name, (newName) => {
  if (isCreating.value && newName) {
    const generated = generateSlug(newName)
    autoGeneratedSlug.value = generated
    // Aktualizovat slug pouze pokud uživatel nezměnil slug ručně
    if (editForm.slug === '' || editForm.slug === autoGeneratedSlug.value) {
      editForm.slug = generated
      editForm.route = generateRoute(generated)
    }
  }
})

// Sledovat změny slug a automaticky generovat route
watch(() => editForm.slug, (newSlug) => {
  if (isCreating.value && newSlug && (!editForm.route || editForm.route === generateRoute(autoGeneratedSlug.value))) {
    editForm.route = generateRoute(newSlug)
  }
})

const openCreateModal = (parentModule = null) => {
  isCreating.value = true
  editingModule.value = null
  autoGeneratedSlug.value = ''
  Object.assign(editForm, {
    name: '',
    slug: '',
    description: '',
    icon: '',
    route: '',
    parent_id: parentModule?.id || null,
    active: true,
    rank: 0
  })
}

const editModule = (module) => {
  isCreating.value = false
  editingModule.value = module
  editForm.name = module.name || ''
  editForm.slug = module.slug || ''
  editForm.description = module.description || ''
  editForm.icon = module.icon || ''
  editForm.route = module.route || ''
  editForm.parent_id = module.parent_id || null
  editForm.active = module.active ?? true
  editForm.rank = module.rank ?? 0
}

const closeEditModal = () => {
  isCreating.value = false
  editingModule.value = null
  autoGeneratedSlug.value = ''
  Object.assign(editForm, {
    name: '',
    slug: '',
    description: '',
    icon: '',
    route: '',
    parent_id: null,
    active: true,
    rank: 0
  })
}

const saveModule = () => {
  // Při vytváření automaticky vygenerovat slug a route, pokud jsou prázdné
  if (isCreating.value) {
    const formData = { ...editForm }
    
    // Pokud není slug, vygenerovat z názvu
    if (!formData.slug && formData.name) {
      formData.slug = generateSlug(formData.name)
    }
    
    // Pokud není route, vygenerovat ze slug
    if (!formData.route && formData.slug) {
      formData.route = generateRoute(formData.slug)
    }
    
    router.post('/dashboard/modules', formData, {
      preserveScroll: true,
      onSuccess: () => {
        closeEditModal()
      }
    })
  } else if (editingModule.value) {
    router.put(`/dashboard/modules/${editingModule.value.id}`, editForm, {
      preserveScroll: true,
      onSuccess: () => {
        closeEditModal()
      }
    })
  }
}

const handleUpdateRank = (modulesData) => {
  router.post('/dashboard/modules/update-rank', { modules: modulesData }, {
    preserveScroll: true
  })
}

const getAllModules = (modules, result = []) => {
  modules.forEach(module => {
    result.push(module)
    if (module.children && module.children.length > 0) {
      getAllModules(module.children, result)
    }
  })
  return result
}

const initSortable = () => {
  if (sortableInstance) {
    sortableInstance.destroy()
    sortableInstance = null
  }

  nextTick(() => {
    if (modulesContainerRef.value && props.modules.length > 0) {
      sortableInstance = Sortable.create(modulesContainerRef.value, {
        animation: 150,
        handle: '.cursor-move',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        draggable: '.module-item-wrapper',
        onEnd: (evt) => {
          const fromIndex = evt.oldIndex
          const toIndex = evt.newIndex

          if (fromIndex !== toIndex && fromIndex !== null && toIndex !== null) {
            const updated = [...props.modules]
            const [moved] = updated.splice(fromIndex, 1)
            updated.splice(toIndex, 0, moved)

            const modulesData = updated.map((m, i) => ({
              id: m.id,
              rank: i
            }))

            handleUpdateRank(modulesData)
          }
        }
      })
    }
  })
}

onMounted(() => {
  initSortable()
})

watch(() => props.modules, () => {
  initSortable()
}, { deep: true })

onUnmounted(() => {
  if (sortableInstance) {
    sortableInstance.destroy()
  }
})

// Inicializace výběrů modulů pro role
watch(() => props.modulesByRole, (newValue) => {
  Object.keys(newValue).forEach(roleId => {
    if (!roleModuleSelections.value[roleId]) {
      roleModuleSelections.value[roleId] = []
    }
    roleModuleSelections.value[roleId] = [...newValue[roleId]]
  })
}, { immediate: true })

// Kontrola, zda je modul přiřazen k roli
const isModuleAssignedToRole = (roleId, moduleId) => {
  const selections = roleModuleSelections.value[roleId] || []
  return selections.includes(moduleId)
}

// Přepnutí modulu pro roli
const toggleModuleForRole = (roleId, moduleId, checked) => {
  if (!roleModuleSelections.value[roleId]) {
    roleModuleSelections.value[roleId] = []
  }
  
  if (checked) {
    if (!roleModuleSelections.value[roleId].includes(moduleId)) {
      roleModuleSelections.value[roleId].push(moduleId)
    }
  } else {
    roleModuleSelections.value[roleId] = roleModuleSelections.value[roleId].filter(id => id !== moduleId)
  }
}

// Uložení modulů pro roli
const saveModulesForRole = (role) => {
  savingRole.value = role.id
  
  const moduleIds = roleModuleSelections.value[role.id] || []
  
  router.post(`/dashboard/modules/roles/${role.id}/assign`, {
    module_ids: moduleIds
  }, {
    preserveScroll: true,
    onSuccess: () => {
      savingRole.value = null
    },
    onError: () => {
      savingRole.value = null
    }
  })
}

// Accordion pro role
const toggleRoleAccordion = (roleId) => {
  expandedRoles.value[roleId] = !expandedRoles.value[roleId]
}

// Accordion pro sekce modulů
const toggleModuleSection = (roleId, sectionId) => {
  const key = `${roleId}-${sectionId}`
  expandedSections.value[key] = !expandedSections.value[key]
}

// Získat sekce modulů (skupiny podle parent_id) - použít hierarchické moduly
const getModuleSections = () => {
  const sections = []
  
  // Použít hlavní moduly z props.modules (hierarchická struktura)
  if (props.modules && props.modules.length > 0) {
    props.modules.forEach(module => {
      sections.push({
        id: module.id,
        name: module.name,
        slug: module.slug
      })
    })
  } else {
    // Fallback: použít allModules a seskupit podle parent_id
    const mainModules = props.allModules.filter(m => !m.parent_id)
    mainModules.forEach(module => {
      sections.push({
        id: module.id,
        name: module.name,
        slug: module.slug
      })
    })
    
    // Pokud jsou moduly s parent_id, které nejsou v hlavních, přidat sekci "Ostatní"
    const modulesWithParent = props.allModules.filter(m => m.parent_id && !mainModules.find(p => p.id === m.parent_id))
    if (modulesWithParent.length > 0) {
      sections.push({
        id: 'other',
        name: 'Ostatní moduly',
        slug: 'other'
      })
    }
  }
  
  return sections
}

// Získat moduly v sekci - získat všechny moduly včetně podmodulů pomocí parent_id
const getModulesInSection = (sectionId) => {
  if (sectionId === 'other') {
    // Všechny moduly s parent_id, které nepatří do žádné sekce
    const mainModuleIds = props.modules?.map(m => m.id) || []
    return props.allModules.filter(m => m.parent_id && !mainModuleIds.includes(m.parent_id))
  }
  
  // Rekurzivně najít všechny moduly, které patří do této sekce (včetně podmodulů)
  const getModuleIdsRecursive = (parentId, result = []) => {
    const directChildren = props.allModules.filter(m => m.parent_id === parentId)
    directChildren.forEach(child => {
      result.push(child.id)
      getModuleIdsRecursive(child.id, result)
    })
    return result
  }
  
  // Přidat hlavní modul sekce
  const sectionModule = props.allModules.find(m => m.id === sectionId)
  const allModuleIds = sectionModule ? [sectionId, ...getModuleIdsRecursive(sectionId)] : [sectionId]
  
  // Vrátit všechny moduly, které patří do této sekce
  return props.allModules.filter(m => allModuleIds.includes(m.id))
}
</script>
