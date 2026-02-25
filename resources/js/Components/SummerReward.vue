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
        <div class="relative max-w-lg w-full">
          <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            mode="out-in"
          >
            <div
              v-if="!showResult"
              key="selection"
              class="bg-gradient-to-br from-yellow-50 via-orange-50 to-blue-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 rounded-2xl shadow-2xl p-10 w-full border-4 border-yellow-200 dark:border-gray-700 relative overflow-hidden"
            >
              <!-- Letní dekorace -->
              <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-200/20 dark:bg-yellow-900/20 rounded-full -mr-16 -mt-16"></div>
              <div class="absolute bottom-0 left-0 w-24 h-24 bg-orange-200/20 dark:bg-orange-900/20 rounded-full -ml-12 -mb-12"></div>
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
                <!-- Letní ikona -->
                <div class="mb-6 flex justify-center">
                  <div class="relative">
                    <div class="text-6xl animate-pulse">☀️</div>
                    <div class="absolute -top-2 -right-2 text-3xl animate-bounce">🌴</div>
                  </div>
                </div>

                <h2 class="text-3xl font-bold mb-2 bg-gradient-to-r from-yellow-600 via-orange-600 to-blue-600 bg-clip-text text-transparent dark:from-yellow-400 dark:via-orange-400 dark:to-blue-400">
                  Letní odměna!
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8">
                  Vyberte si jeden letní předmět a zjistěte, co jste vyhráli!
                </p>

                <!-- Tři letní předměty -->
                <div class="flex justify-center gap-6 mb-6">
                  <!-- Slunečník -->
                  <button
                    @click="selectItem('umbrella')"
                    :disabled="selectedItem !== null"
                    class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 shadow-lg border-4 border-yellow-300 dark:border-yellow-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-yellow-400"
                      :class="selectedItem === 'umbrella' ? 'ring-4 ring-yellow-300 ring-offset-4' : ''"
                    >
                      <div class="text-4xl transform transition-transform duration-300 group-hover:scale-110">🌂</div>
                    </div>
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                      Slunečník
                    </div>
                  </button>

                  <!-- Opalovák -->
                  <button
                    @click="selectItem('flipflop')"
                    :disabled="selectedItem !== null"
                    class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 shadow-lg border-4 border-orange-300 dark:border-orange-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-orange-400"
                      :class="selectedItem === 'flipflop' ? 'ring-4 ring-orange-300 ring-offset-4' : ''"
                    >
                      <div class="text-4xl transform transition-transform duration-300 group-hover:scale-110">🩴</div>
                    </div>
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                      Opalovák
                    </div>
                  </button>

                  <!-- Koktejl -->
                  <button
                    @click="selectItem('cocktail')"
                    :disabled="selectedItem !== null"
                    class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 shadow-lg border-4 border-blue-300 dark:border-blue-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-blue-400"
                      :class="selectedItem === 'cocktail' ? 'ring-4 ring-blue-300 ring-offset-4' : ''"
                    >
                      <div class="text-4xl transform transition-transform duration-300 group-hover:scale-110">🍹</div>
                    </div>
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                      Koktejl
                    </div>
                  </button>
                </div>
              </div>
            </div>
            <div
              v-else
              key="result"
              class="bg-gradient-to-br from-yellow-50 via-orange-50 to-blue-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 rounded-2xl shadow-2xl p-10 w-full border-4 relative overflow-hidden text-center result-container"
              :class="resultBorderClass"
            >
              <!-- Konfety animace -->
              <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                  v-for="i in 50"
                  :key="i"
                  class="confetti"
                  :style="getConfettiStyle(i)"
                ></div>
              </div>

              <!-- Dekorativní pozadí -->
              <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-200/20 dark:bg-yellow-900/20 rounded-full -mr-20 -mt-20"></div>
              <div class="absolute bottom-0 left-0 w-32 h-32 bg-orange-200/20 dark:bg-orange-900/20 rounded-full -ml-16 -mb-16"></div>

              <!-- Zavírací tlačítko -->
              <button
                @click="close"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors z-20"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <!-- Obsah výsledku -->
              <div class="relative z-10">
                <!-- Ikona výhry s animací -->
                <div class="mb-8 flex justify-center">
                  <div class="relative">
                    <div
                      class="w-40 h-40 rounded-full flex items-center justify-center text-7xl prize-icon"
                      :class="resultBgClass"
                    >
                      {{ resultIcon }}
                    </div>
                    <!-- Prstencový efekt -->
                    <div class="absolute inset-0 rounded-full animate-ping opacity-20" :class="resultBgClass"></div>
                  </div>
                </div>

                <!-- Nadpis -->
                <div class="mb-6">
                  <h2 class="text-4xl font-extrabold mb-3 animate-fade-in-up" :class="resultTextClass">
                    Gratulujeme! 🎉
                  </h2>
                  <div class="w-24 h-1 mx-auto rounded-full bg-gradient-to-r from-yellow-400 via-orange-400 to-blue-400"></div>
                </div>

                <!-- Výhra -->
                <div class="mb-6 animate-fade-in-up-delay">
                  <p class="text-3xl font-bold mb-3" :class="resultTextClass">
                    {{ resultTitle }}
                  </p>
                  <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ resultDescription }}
                  </p>
                </div>

                <!-- Tlačítko -->
                <div class="animate-fade-in-up-delay-2">
                  <button
                    @click="close"
                    class="px-10 py-4 bg-gradient-to-r from-yellow-500 via-orange-500 to-blue-500 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300"
                  >
                    Skvělé! 🎁
                  </button>
                </div>
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
const selectedItem = ref(null)
const showResult = ref(false)

// Výhry pro každý předmět
const prizes = {
  umbrella: {
    title: 'Sleva 35%!',
    description: 'Získejte slevu 35% na celý nákup!',
    icon: '🎁',
    bgClass: 'bg-gradient-to-br from-yellow-400 to-yellow-600',
    textClass: 'text-yellow-600 dark:text-yellow-400',
    borderClass: 'border-yellow-400 dark:border-yellow-600'
  },
  flipflop: {
    title: 'Doprava zdarma!',
    description: 'Vaše příští objednávka bude doručena zdarma!',
    icon: '🚚',
    bgClass: 'bg-gradient-to-br from-orange-400 to-orange-600',
    textClass: 'text-orange-600 dark:text-orange-400',
    borderClass: 'border-orange-400 dark:border-orange-600'
  },
  cocktail: {
    title: 'Letní balíček!',
    description: 'Získejte speciální letní balíček produktů zdarma!',
    icon: '🏖️',
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

const selectItem = (item) => {
  if (selectedItem.value !== null) return

  selectedItem.value = item

  // Animace výběru - zobrazení výsledku s plynulejším přechodem
  setTimeout(() => {
    result.value = prizes[item]
    showResult.value = true
  }, 400)
}

const close = () => {
  isVisible.value = false
  emit('update:modelValue', false)

  // Reset po zavření
  setTimeout(() => {
    selectedItem.value = null
    showResult.value = false
    result.value = null
  }, 300)
}

// Konfety animace
const getConfettiStyle = (index) => {
  const colors = ['#eab308', '#f97316', '#3b82f6', '#f59e0b', '#06b6d4', '#84cc16']
  const color = colors[index % colors.length]
  const angle = (index * 7.2) % 360
  const delay = index * 0.03
  const duration = 2.5 + (index % 4) * 0.5
  const distance = 50 + (index % 3) * 15

  return {
    position: 'absolute',
    width: index % 3 === 0 ? '14px' : '10px',
    height: index % 3 === 0 ? '14px' : '10px',
    backgroundColor: color,
    left: `${50 + Math.sin(angle * Math.PI / 180) * distance}%`,
    top: `${50 + Math.cos(angle * Math.PI / 180) * distance}%`,
    animation: `confetti-fall ${duration}s ease-out ${delay}s`,
    transform: `rotate(${angle}deg)`,
    borderRadius: index % 2 === 0 ? '50%' : '20%'
  }
}

// Sledování změn modelValue
watch(() => props.modelValue, (newVal) => {
  isVisible.value = newVal
})

onMounted(() => {
  // Přidat CSS pro konfety animaci a další efekty
  const style = document.createElement('style')
  style.textContent = `
    @keyframes confetti-fall {
      0% {
        transform: translateY(-100px) rotate(0deg) translateX(0);
        opacity: 1;
      }
      50% {
        opacity: 1;
      }
      100% {
        transform: translateY(400px) rotate(720deg) translateX(50px);
        opacity: 0;
      }
    }
    
    @keyframes fade-in-up {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes prize-pop {
      0% {
        transform: scale(0);
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }
    
    .animate-fade-in-up {
      animation: fade-in-up 0.6s ease-out;
    }
    
    .animate-fade-in-up-delay {
      animation: fade-in-up 0.6s ease-out 0.2s both;
    }
    
    .animate-fade-in-up-delay-2 {
      animation: fade-in-up 0.6s ease-out 0.4s both;
    }
    
    .prize-icon {
      animation: prize-pop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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


