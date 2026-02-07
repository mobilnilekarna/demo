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
              class="bg-gradient-to-br from-red-50 via-white to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 rounded-2xl shadow-2xl p-10 w-full border-4 border-red-200 dark:border-gray-700 relative overflow-hidden"
            >
              <!-- Mikulášské dekorace -->
              <div class="absolute top-0 right-0 w-32 h-32 bg-red-200/20 dark:bg-red-900/20 rounded-full -mr-16 -mt-16"></div>
              <div class="absolute bottom-0 left-0 w-24 h-24 bg-red-200/20 dark:bg-red-900/20 rounded-full -ml-12 -mb-12"></div>
              <div class="absolute top-1/2 right-4 w-16 h-16 bg-red-200/20 dark:bg-red-900/20 rounded-full"></div>

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
                <!-- Mikulášská ikona -->
                <div class="mb-6 flex justify-center">
                  <div class="relative">
                    <div class="text-6xl animate-pulse">🎅</div>
                    <div class="absolute -top-2 -right-2 text-3xl animate-bounce">❄️</div>
                  </div>
                </div>

                <h2 class="text-3xl font-bold mb-2 bg-gradient-to-r from-red-600 via-red-700 to-red-800 bg-clip-text text-transparent dark:from-red-400 dark:via-red-500 dark:to-red-600">
                  Mikulášská odměna!
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8">
                  Vyberte si jednoho z Mikulášské trojice a zjistěte, co jste vyhráli!
                </p>

                <!-- Tři postavy -->
                <div class="flex justify-center gap-6 mb-6">
                  <!-- Mikuláš -->
                  <button
                    @click="selectItem('saint')"
                    :disabled="selectedItem !== null"
                    class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gradient-to-br from-red-500 to-red-700 shadow-lg border-4 border-red-300 dark:border-red-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-red-400"
                      :class="selectedItem === 'saint' ? 'ring-4 ring-red-300 ring-offset-4' : ''"
                    >
                      <div class="text-4xl transform transition-transform duration-300 group-hover:scale-110">🎅</div>
                    </div>
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                      Mikuláš
                    </div>
                  </button>

                  <!-- Anděl -->
                  <button
                    @click="selectItem('angel')"
                    :disabled="selectedItem !== null"
                    class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gradient-to-br from-white to-gray-100 shadow-lg border-4 border-gray-300 dark:border-gray-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-gray-400"
                      :class="selectedItem === 'angel' ? 'ring-4 ring-gray-300 ring-offset-4' : ''"
                    >
                      <div class="text-4xl transform transition-transform duration-300 group-hover:scale-110">😇</div>
                    </div>
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                      Anděl
                    </div>
                  </button>

                  <!-- Čert -->
                  <button
                    @click="selectItem('devil')"
                    :disabled="selectedItem !== null"
                    class="group relative transform transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <div
                      class="w-24 h-24 rounded-full bg-gradient-to-br from-black to-gray-800 shadow-lg border-4 border-gray-700 dark:border-gray-600 flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:border-gray-500"
                      :class="selectedItem === 'devil' ? 'ring-4 ring-gray-400 ring-offset-4' : ''"
                    >
                      <div class="text-4xl transform transition-transform duration-300 group-hover:scale-110">😈</div>
                    </div>
                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                      Čert
                    </div>
                  </button>
                </div>
              </div>
            </div>
            <div
              v-else
              key="result"
              class="bg-gradient-to-br from-red-50 via-white to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800 rounded-2xl shadow-2xl p-10 w-full border-4 relative overflow-hidden text-center result-container"
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
              <div class="absolute top-0 right-0 w-40 h-40 bg-red-200/20 dark:bg-red-900/20 rounded-full -mr-20 -mt-20"></div>
              <div class="absolute bottom-0 left-0 w-32 h-32 bg-red-200/20 dark:bg-red-900/20 rounded-full -ml-16 -mb-16"></div>

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
                  <div class="w-24 h-1 mx-auto rounded-full bg-gradient-to-r from-red-400 via-red-500 to-red-600"></div>
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
                    class="px-10 py-4 bg-gradient-to-r from-red-500 via-red-600 to-red-700 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300"
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

// Výhry pro každou postavu
const prizes = {
  saint: {
    title: 'Sleva 30%!',
    description: 'Mikuláš vám nadělil slevu 30% na celý nákup!',
    icon: '🎁',
    bgClass: 'bg-gradient-to-br from-red-400 to-red-600',
    textClass: 'text-red-600 dark:text-red-400',
    borderClass: 'border-red-400 dark:border-red-600'
  },
  angel: {
    title: 'Doprava zdarma!',
    description: 'Anděl vám nadělil dopravu zdarma na příští objednávku!',
    icon: '🚚',
    bgClass: 'bg-gradient-to-br from-gray-200 to-gray-400',
    textClass: 'text-gray-700 dark:text-gray-300',
    borderClass: 'border-gray-300 dark:border-gray-600'
  },
  devil: {
    title: 'Čertovský balíček!',
    description: 'Čert vám nadělil speciální balíček produktů zdarma!',
    icon: '😈',
    bgClass: 'bg-gradient-to-br from-gray-800 to-black',
    textClass: 'text-gray-800 dark:text-gray-200',
    borderClass: 'border-gray-700 dark:border-gray-600'
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
  const colors = ['#dc2626', '#991b1b', '#7f1d1d', '#ef4444', '#b91c1c', '#dc2626']
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


