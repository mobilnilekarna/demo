<template>
  <Teleport to="body">
    <!-- Overlay -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-300 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isVisible"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
        @click.self="close"
      >
        <!-- Modal box -->
        <div class="relative">
          <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 scale-50 rotate-12"
            enter-to-class="opacity-100 scale-100 rotate-0"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div
              v-if="!showResult"
              class="bg-gradient-to-br from-red-50 via-green-50 to-blue-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full border-4 border-red-200 dark:border-gray-700 relative overflow-hidden"
            >
            <!-- Vánoční dekorace -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-200/20 dark:bg-red-900/20 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-green-200/20 dark:bg-green-900/20 rounded-full -ml-12 -mb-12"></div>
            <div class="absolute top-1/2 right-4 w-16 h-16 bg-blue-200/20 dark:bg-blue-900/20 rounded-full"></div>

            <!-- Zavírací tlačítko -->
            <button
              @click="close"
              class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors z-10"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Obsah -->
            <div class="relative z-10 text-center">
              <!-- Vánoční ikona -->
              <div class="mb-6 flex justify-center">
                <div class="relative">
                  <svg class="w-20 h-20 text-red-500 dark:text-red-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                  </svg>
                  <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full animate-bounce"></div>
                </div>
              </div>

              <h2 class="text-3xl font-bold mb-2 bg-gradient-to-r from-red-600 via-green-600 to-blue-600 bg-clip-text text-transparent dark:from-red-400 dark:via-green-400 dark:to-blue-400">
                Vánoční Losování!
              </h2>
              <p class="text-gray-600 dark:text-gray-300 mb-8">
                Vyberte si jednu pilulku a zjistěte, co jste vyhráli!
              </p>

              <!-- Tři pilulky -->
              <div class="flex justify-center gap-6 mb-6">
                <!-- Červená pilulka -->
                <button
                  @click="selectPill('red')"
                  :disabled="selectedPill !== null"
                  class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <div
                    class="w-20 h-20 rounded-full bg-gradient-to-br from-red-500 to-red-700 shadow-lg border-4 border-red-300 dark:border-red-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-red-400"
                    :class="selectedPill === 'red' ? 'ring-4 ring-red-300 ring-offset-4' : ''"
                  >
                    <span class="text-white font-bold text-xl">R</span>
                  </div>
                  <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                    Červená
                  </div>
                </button>

                <!-- Zelená pilulka -->
                <button
                  @click="selectPill('green')"
                  :disabled="selectedPill !== null"
                  class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <div
                    class="w-20 h-20 rounded-full bg-gradient-to-br from-green-500 to-green-700 shadow-lg border-4 border-green-300 dark:border-green-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-green-400"
                    :class="selectedPill === 'green' ? 'ring-4 ring-green-300 ring-offset-4' : ''"
                  >
                    <span class="text-white font-bold text-xl">G</span>
                  </div>
                  <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                    Zelená
                  </div>
                </button>

                <!-- Modrá pilulka -->
                <button
                  @click="selectPill('blue')"
                  :disabled="selectedPill !== null"
                  class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <div
                    class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg border-4 border-blue-300 dark:border-blue-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-blue-400"
                    :class="selectedPill === 'blue' ? 'ring-4 ring-blue-300 ring-offset-4' : ''"
                  >
                    <span class="text-white font-bold text-xl">B</span>
                  </div>
                  <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                    Modrá
                  </div>
                </button>
              </div>
            </div>
          </div>
          </Transition>

          <!-- Výsledek animace -->
          <Transition
            enter-active-class="transition duration-700 ease-out"
            enter-from-class="opacity-0 scale-0 rotate-180"
            enter-to-class="opacity-100 scale-100 rotate-0"
          >
            <div
              v-if="showResult"
              class="bg-gradient-to-br from-red-50 via-green-50 to-blue-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full border-4 dark:border-gray-700 relative overflow-hidden text-center"
              :class="resultBorderClass"
            >
              <!-- Konfety animace -->
              <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                  v-for="i in 20"
                  :key="i"
                  class="confetti"
                  :style="getConfettiStyle(i)"
                ></div>
              </div>

              <!-- Obsah výsledku -->
              <div class="relative z-10">
                <!-- Ikona výhry -->
                <div class="mb-6 flex justify-center">
                  <div
                    class="w-32 h-32 rounded-full flex items-center justify-center text-6xl animate-bounce"
                    :class="resultBgClass"
                  >
                    {{ resultIcon }}
                  </div>
                </div>

                <h2 class="text-3xl font-bold mb-4" :class="resultTextClass">
                  Gratulujeme!
                </h2>
                <p class="text-2xl font-semibold mb-2" :class="resultTextClass">
                  {{ resultTitle }}
                </p>
                <p class="text-gray-600 dark:text-gray-300 mb-8">
                  {{ resultDescription }}
                </p>

                <button
                  @click="close"
                  class="px-8 py-3 bg-gradient-to-r from-red-500 via-green-500 to-blue-500 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                >
                  Skvělé!
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue'])

const isVisible = ref(props.modelValue)
const selectedPill = ref(null)
const showResult = ref(false)

// Výhry pro každou pilulku
const prizes = {
  red: {
    title: 'Sleva 20%!',
    description: 'Získejte slevu 20% na celý nákup!',
    icon: '🎁',
    bgClass: 'bg-gradient-to-br from-red-400 to-red-600',
    textClass: 'text-red-600 dark:text-red-400',
    borderClass: 'border-red-400 dark:border-red-600'
  },
  green: {
    title: 'Doprava zdarma!',
    description: 'Vaše příští objednávka bude doručena zdarma!',
    icon: '🚚',
    bgClass: 'bg-gradient-to-br from-green-400 to-green-600',
    textClass: 'text-green-600 dark:text-green-400',
    borderClass: 'border-green-400 dark:border-green-600'
  },
  blue: {
    title: 'Bonusový balíček!',
    description: 'Získejte bonusový balíček produktů zdarma!',
    icon: '🎉',
    bgClass: 'bg-gradient-to-br from-blue-400 to-blue-600',
    textClass: 'text-blue-600 dark:text-blue-400',
    borderClass: 'border-blue-400 dark:border-blue-600'
  }
}

const result = ref(null)

const resultTitle = computed(() => result.value?.title || '')
const resultDescription = computed(() => result.value?.description || '')
const resultIcon = computed(() => result.value?.icon || '')
const resultBgClass = computed(() => result.value?.bgClass || '')
const resultTextClass = computed(() => result.value?.textClass || '')
const resultBorderClass = computed(() => result.value?.borderClass || '')

const selectPill = (color) => {
  if (selectedPill.value !== null) return

  selectedPill.value = color

  // Animace výběru
  setTimeout(() => {
    result.value = prizes[color]
    showResult.value = true
  }, 500)
}

const close = () => {
  isVisible.value = false
  emit('update:modelValue', false)

  // Reset po zavření
  setTimeout(() => {
    selectedPill.value = null
    showResult.value = false
    result.value = null
  }, 300)
}

// Konfety animace
const getConfettiStyle = (index) => {
  const colors = ['#ef4444', '#22c55e', '#3b82f6', '#f59e0b', '#8b5cf6']
  const color = colors[index % colors.length]
  const angle = (index * 18) % 360
  const delay = index * 0.1
  const duration = 2 + (index % 3)

  return {
    position: 'absolute',
    width: '10px',
    height: '10px',
    backgroundColor: color,
    left: `${50 + Math.sin(angle * Math.PI / 180) * 30}%`,
    top: `${50 + Math.cos(angle * Math.PI / 180) * 30}%`,
    animation: `confetti-fall ${duration}s ease-out ${delay}s infinite`,
    transform: `rotate(${angle}deg)`
  }
}

// Sledování změn modelValue
watch(() => props.modelValue, (newVal) => {
  isVisible.value = newVal
})

onMounted(() => {
  // Přidat CSS pro konfety animaci
  const style = document.createElement('style')
  style.textContent = `
    @keyframes confetti-fall {
      0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(200px) rotate(720deg);
        opacity: 0;
      }
    }
  `
  document.head.appendChild(style)
})
</script>

<style scoped>
.confetti {
  border-radius: 50%;
}
</style>

