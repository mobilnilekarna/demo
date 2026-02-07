<template>
  <div>
    <!-- Overlay -->
    <div
      v-if="isMenuOpen && isMobile"
      @click="$emit('close-menu')"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <aside
      data-tour-guide="step-1"
      :class="[
        'fixed top-14 left-0 h-[calc(100vh-3.5rem)] bg-white dark:bg-gray-900 shadow-lg border-r border-gray-200 dark:border-gray-700 z-30 transition-all duration-300 overflow-hidden',
        isMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]"
      :style="{ width: isExpanded ? '350px' : '80px' }"
    >
      <div class="flex h-full">
        <!-- Main Icons Section -->
        <div class="w-[80px] bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 flex flex-col items-center py-5 space-y-1.5 overflow-y-auto flex-shrink-0">
          <!-- User Avatar -->
          <div class="relative mb-4">
            <div class="w-11 h-11 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
              <span class="text-gray-500 dark:text-gray-400 text-base font-medium">U</span>
            </div>
            <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-white dark:border-gray-900"></div>
          </div>

          <!-- Menu Items -->
          <nav class="flex flex-col space-y-2 w-full items-center">
            <!-- Statická sekce Přehled -->
            <Link
              href="/dashboard"
              :class="[
                'flex flex-col items-center justify-center py-3 px-1 w-full transition-all relative group',
                activeSection === 'prehled'
                  ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20'
                  : 'text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800'
              ]"
              title="Přehled"
              @click="handleStaticLinkClick('prehled')"
            >
              <svg class="w-6 h-6 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span class="text-[10px] font-medium text-center leading-tight">Přehled</span>
              <div v-if="activeSection === 'prehled'" class="absolute left-0 top-0 bottom-0 w-1 bg-primary-600 dark:bg-primary-400"></div>
            </Link>

            <!-- Statická sekce Moduly - pouze pro Super Admina -->
            <button
              v-if="isSuperAdmin"
              @click="toggleSection('moduly')"
              :class="[
                'flex flex-col items-center justify-center py-3 px-1 w-full transition-all relative group',
                activeSection === 'moduly'
                  ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20'
                  : 'text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800'
              ]"
              title="Moduly"
            >
              <svg class="w-6 h-6 mb-1.5" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
              </svg>
              <span class="text-[10px] font-medium text-center leading-tight">Moduly</span>
              <div v-if="activeSection === 'moduly'" class="absolute left-0 top-0 bottom-0 w-1 bg-primary-600 dark:bg-primary-400"></div>
            </button>

            <button
              v-for="item in dashboardModules"
              :key="item.id"
              @click="toggleSection(item.slug)"
              :class="[
                'flex flex-col items-center justify-center py-3 px-1 w-full transition-all relative group',
                activeSection === item.slug
                  ? 'text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20'
                  : 'text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800'
              ]"
              :title="item.name"
            >
              <div v-html="item.icon" class="w-6 h-6 mb-1.5"></div>
              <span class="text-[10px] font-medium text-center leading-tight">{{ item.name }}</span>
              <div v-if="activeSection === item.slug" class="absolute left-0 top-0 bottom-0 w-1 bg-primary-600 dark:bg-primary-400"></div>
            </button>
          </nav>
        </div>

        <!-- Submenu Section -->
        <div
          :class="[
            'flex-1 overflow-y-auto transition-all duration-300 bg-gray-50 dark:bg-gray-800',
            isExpanded ? 'opacity-100 w-[350px]' : 'opacity-0 w-0'
          ]"
        >
          <div v-if="isExpanded" class="p-5">
            <!-- Corporate Label -->
            <div class="flex items-center space-x-2 mb-5 pb-3 border-b border-gray-200 dark:border-gray-700">
              <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>
                <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"/>
              </svg>
              <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Corporate</span>
            </div>

            <!-- Statická sekce Přehled -->
            <div v-if="activeSection === 'prehled'" class="space-y-3">
              <h3 class="text-[11px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-3 px-2">PŘEHLED</h3>
              <Link
                href="/dashboard"
                :preserve-state="true"
                :preserve-scroll="true"
                :class="[
                  'flex items-center space-x-2.5 px-3 py-2 rounded transition-colors',
                  page.url === '/dashboard'
                    ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400'
                ]"
              >
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-sm">Hlavní přehled</span>
              </Link>
            </div>

            <!-- Statická sekce Moduly - pouze pro Super Admina -->
            <div v-if="activeSection === 'moduly' && isSuperAdmin" class="space-y-3">
              <h3 class="text-[11px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-3 px-2">MODULY</h3>
              <Link
                href="/dashboard/modules"
                :preserve-state="true"
                :preserve-scroll="true"
                :class="[
                  'flex items-center space-x-2.5 px-3 py-2 rounded transition-colors',
                  page.url === '/dashboard/modules'
                    ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400'
                ]"
              >
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                </svg>
                <span class="text-sm">Správa modulů</span>
              </Link>
            </div>

            <!-- Dynamic Section based on activeSection -->
            <div v-for="module in dashboardModules" :key="module.slug">
              <div v-if="activeSection === module.slug && module.children && module.children.length > 0" class="space-y-3">
                <h3 class="text-[11px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-3 px-2">{{ module.name.toUpperCase() }}</h3>

                <!-- Level 2: Children (podmoduly) -->
                <div v-for="child in module.children" :key="child.id" class="space-y-1">
                  <!-- If child has children (3rd level), show as group -->
                  <div v-if="child.children && child.children.length > 0">
                    <div class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">
                      {{ child.name }}
                    </div>
                    <!-- Level 3: Grandchildren -->
                    <Link
                      v-for="grandchild in child.children"
                      :key="grandchild.id"
                      :href="`/dashboard/module/${grandchild.slug}`"
                      :preserve-state="true"
                      :preserve-scroll="true"
                      :class="[
                        'flex items-center space-x-2.5 px-3 py-2 pl-6 rounded transition-colors',
                        page.url === `/dashboard/module/${grandchild.slug}`
                          ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                          : 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400'
                      ]"
                    >
                      <div v-if="grandchild.icon" v-html="grandchild.icon" class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0"></div>
                      <span class="text-sm">{{ grandchild.name }}</span>
                    </Link>
                  </div>
                  <!-- If child has no children, show as single item -->
                  <Link
                    v-else
                    :href="`/dashboard/module/${child.slug}`"
                    :preserve-state="true"
                    :preserve-scroll="true"
                    :class="[
                      'flex items-center space-x-2.5 px-3 py-2 rounded transition-colors',
                      page.url === `/dashboard/module/${child.slug}`
                        ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                        : 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400'
                    ]"
                  >
                    <div v-if="child.icon" v-html="child.icon" class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0"></div>
                    <span class="text-sm">{{ child.name }}</span>
                  </Link>
                </div>
              </div>
              <!-- Single module without children -->
              <div v-else-if="activeSection === module.slug && (!module.children || module.children.length === 0)" class="space-y-1">
                <h3 class="text-[11px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-3 px-2">{{ module.name.toUpperCase() }}</h3>
                <Link
                  :href="`/dashboard/module/${module.slug}`"
                  :preserve-state="true"
                  :preserve-scroll="true"
                  :class="[
                    'flex items-center space-x-2.5 px-3 py-2 rounded transition-colors',
                    page.url === `/dashboard/module/${module.slug}`
                      ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400'
                      : 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400'
                  ]"
                >
                  <span class="text-sm">Přejít na {{ module.name }}</span>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  isMenuOpen: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['close-menu', 'menu-expanded'])

const page = usePage()
const isMobile = ref(false)

// Kontrola, zda je uživatel Super Admin
const isSuperAdmin = computed(() => {
  const user = page.props.auth?.user
  return user && user.roles && user.roles.includes('Super Admin')
})

// Použít sessionStorage pro zachování activeSection při navigaci
const getStoredActiveSection = () => {
  if (typeof window !== 'undefined') {
    return sessionStorage.getItem('dashboard-active-section') || null
  }
  return null
}

const setStoredActiveSection = (value) => {
  if (typeof window !== 'undefined') {
    if (value) {
      sessionStorage.setItem('dashboard-active-section', value)
    } else {
      sessionStorage.removeItem('dashboard-active-section')
    }
  }
}

// Získání modulů z Inertia
const dashboardModules = computed(() => page.props.dashboardModules || [])

// Automaticky nastavit aktivní sekci podle URL
const getActiveSectionFromUrl = () => {
  const url = page.url

  // Statická sekce Přehled
  if (url === '/dashboard' || url === '/dashboard/') {
    return 'prehled'
  }

  // Statická sekce Moduly - pouze pro Super Admina
  if (url.startsWith('/dashboard/modules') && isSuperAdmin.value) {
    return 'moduly'
  }

  // Dynamické moduly - zkontrolovat URL pattern /dashboard/module/{slug}
  const moduleMatch = url.match(/^\/dashboard\/module\/([^\/]+)/)
  if (moduleMatch) {
    const moduleSlug = moduleMatch[1]

    // Najít modul v hierarchii
    const findModuleInTree = (modules, targetSlug) => {
      for (const module of modules) {
        if (module.slug === targetSlug) {
          // Pokud má rodiče, vrátit rodiče, jinak vrátit tento modul
          return module
        }
        if (module.children && module.children.length > 0) {
          const found = findModuleInTree(module.children, targetSlug)
          if (found) {
            // Pokud jsme našli child, vrátit rodiče (aktuální modul)
            return module
          }
        }
      }
      return null
    }

    const foundModule = findModuleInTree(dashboardModules.value, moduleSlug)
    if (foundModule) {
      return foundModule.slug
    }
  }

  return null
}

// Inicializace activeSection - nejprve zkontrolovat URL, pak sessionStorage
// Ale počkáme až se načtou dashboardModules, takže inicializujeme jako null
const activeSection = ref(null)

const isExpanded = computed(() => activeSection.value !== null)

const toggleSection = (sectionId) => {
  if (activeSection.value === sectionId) {
    // Kliknutí na aktivní sekci ji zavře
    activeSection.value = null
    setStoredActiveSection(null)
  } else {
    // Kliknutí na jinou sekci ji otevře
    activeSection.value = sectionId
    setStoredActiveSection(sectionId)
  }

  // Emit změny pro parent komponentu
  emit('menu-expanded', activeSection.value !== null)
}

const handleStaticLinkClick = (sectionId) => {
  activeSection.value = sectionId
  setStoredActiveSection(sectionId)
  emit('menu-expanded', true)
}

const checkMobile = () => {
  isMobile.value = window.innerWidth < 1024
}

// Funkce pro aktualizaci aktivní sekce z URL
const updateActiveSectionFromUrl = () => {
  const urlBasedSection = getActiveSectionFromUrl()
  if (urlBasedSection) {
    if (urlBasedSection !== activeSection.value) {
      activeSection.value = urlBasedSection
      setStoredActiveSection(urlBasedSection)
      if (props.isMenuOpen) {
        emit('menu-expanded', true)
      }
    }
  }
}

// Sledovat změny URL a automaticky nastavit aktivní sekci
watch(() => page.url, () => {
  updateActiveSectionFromUrl()
}, { immediate: true })

// Sledovat změny dashboardModules (mohou se načíst později)
watch(() => dashboardModules.value, () => {
  if (dashboardModules.value.length > 0 && !activeSection.value) {
    // Pokud ještě není nastavena aktivní sekce, zkusit z URL nebo sessionStorage
    const urlBasedSection = getActiveSectionFromUrl()
    if (urlBasedSection) {
      activeSection.value = urlBasedSection
      setStoredActiveSection(urlBasedSection)
    } else {
      const stored = getStoredActiveSection()
      if (stored) {
        activeSection.value = stored
      }
    }
    if (activeSection.value && props.isMenuOpen) {
      emit('menu-expanded', true)
    }
  } else if (dashboardModules.value.length > 0) {
    // Pokud už máme aktivní sekci, zkontrolovat zda je stále platná z URL
    updateActiveSectionFromUrl()
  }
}, { immediate: true })

// Sledovat změny isMenuOpen a zavřít submenu když se menu zavře
watch(() => props.isMenuOpen, (newValue) => {
  if (!newValue) {
    // Nezavírat activeSection, jen emitovat změnu
    emit('menu-expanded', false)
  } else if (newValue && activeSection.value === null) {
    // Když se menu otevře a není aktivní žádná sekce, zkontrolovat URL nebo sessionStorage
    const urlBasedSection = getActiveSectionFromUrl()
    if (urlBasedSection) {
      activeSection.value = urlBasedSection
      setStoredActiveSection(urlBasedSection)
    } else {
      const stored = getStoredActiveSection()
      if (stored) {
        activeSection.value = stored
      } else {
        activeSection.value = dashboardModules.value.length > 0 ? dashboardModules.value[0].slug : null
        if (activeSection.value) {
          setStoredActiveSection(activeSection.value)
        }
      }
    }
    emit('menu-expanded', true)
  } else if (newValue && activeSection.value) {
    // Menu se otevřelo a máme aktivní sekci - obnovit ji
    emit('menu-expanded', true)
  }
})

onMounted(() => {
  checkMobile()
  window.addEventListener('resize', checkMobile)

  // Zkontrolovat URL při mountování a nastavit aktivní sekci
  const urlBasedSection = getActiveSectionFromUrl()
  if (urlBasedSection && urlBasedSection !== activeSection.value) {
    activeSection.value = urlBasedSection
    setStoredActiveSection(urlBasedSection)
    if (props.isMenuOpen) {
      emit('menu-expanded', true)
    }
  }
})

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile)
})
</script>

