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
        class="fixed inset-0 bg-black/90 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
        @click.self="close"
      >
        <!-- Modal box -->
        <div class="relative max-w-4xl w-full">
          <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
            mode="out-in"
          >
            <!-- Obrazovka před otevřením -->
            <div
              v-if="!isRolling && !showResult"
              key="preview"
              class="bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-2xl shadow-2xl p-10 w-full border-4 border-gray-700 relative overflow-hidden"
            >
              <!-- Zavírací tlačítko -->
              <button
                @click="close"
                class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors z-10"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <!-- Obsah -->
              <div class="relative z-10 text-center">
                <!-- Ikona bedny -->
                <div class="mb-6 flex justify-center">
                  <div class="relative">
                    <div class="text-8xl animate-pulse">📦</div>
                    <div class="absolute inset-0 flex items-center justify-center">
                      <div class="text-4xl animate-spin">✨</div>
                    </div>
                  </div>
                </div>

                <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 bg-clip-text text-transparent">
                  Otevřít bednu s odměnami
                </h2>
                <p class="text-gray-300 mb-8 text-lg">
                  Klikněte na tlačítko a zjistěte, jakou odměnu jste vyhráli!
                </p>

                <!-- Tlačítko pro otevření -->
                <button
                  @click="startRolling"
                  class="px-12 py-4 bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 text-white font-bold text-xl rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                >
                  <span class="relative z-10 flex items-center gap-3">
                    <span>Otevřít bednu</span>
                    <span class="text-2xl">🎁</span>
                  </span>
                  <div class="absolute inset-0 bg-gradient-to-r from-yellow-600 via-orange-600 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
              </div>
            </div>

            <!-- Obrazovka s rolováním -->
            <div
              v-else-if="isRolling"
              key="rolling"
              class="bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-2xl shadow-2xl p-8 w-full border-4 border-gray-700 relative overflow-hidden"
            >
              <!-- Zavírací tlačítko (skryté během rolování) -->
              <button
                @click="close"
                class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors z-10 opacity-50 cursor-not-allowed"
                disabled
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <!-- Nadpis -->
              <div class="text-center mb-6">
                <h2 class="text-3xl font-bold mb-2 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 bg-clip-text text-transparent">
                  Otevírání bedny...
                </h2>
                <div class="w-32 h-1 mx-auto rounded-full bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500"></div>
              </div>

              <!-- Kontejner s odměnami -->
              <div ref="containerRef" class="relative h-80 overflow-hidden rounded-xl bg-black/50 border-2 border-gray-700">
                <!-- Zarážka uprostřed - vypadá jako skutečná zarážka -->
                <div class="absolute top-0 bottom-0 left-1/2 transform -translate-x-1/2 z-30 pointer-events-none">
                  <!-- Hlavní zarážka - vertikální čára s šipkami -->
                  <div class="absolute top-0 bottom-0 left-1/2 transform -translate-x-1/2 w-4 bg-gradient-to-b from-yellow-400 via-orange-500 to-red-500 shadow-2xl shadow-yellow-500/70">
                    <!-- Horní šipka/indikátor -->
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                      <div class="w-0 h-0 border-l-[12px] border-r-[12px] border-b-[20px] border-l-transparent border-r-transparent border-b-yellow-400 drop-shadow-lg"></div>
                      <div class="absolute top-5 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full border-4 border-white shadow-xl animate-pulse"></div>
                    </div>
                    <!-- Dolní šipka/indikátor -->
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2">
                      <div class="w-0 h-0 border-l-[12px] border-r-[12px] border-t-[20px] border-l-transparent border-r-transparent border-t-red-500 drop-shadow-lg"></div>
                      <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-gradient-to-br from-red-500 to-orange-500 rounded-full border-4 border-white shadow-xl animate-pulse"></div>
                    </div>
                  </div>
                  <!-- Glow efekt kolem zarážky -->
                  <div class="absolute top-0 bottom-0 left-1/2 transform -translate-x-1/2 w-8 bg-gradient-to-b from-yellow-400/40 via-orange-500/40 to-red-500/40 blur-md"></div>
                  <!-- Světelné paprsky -->
                  <div class="absolute top-0 bottom-0 left-1/2 transform -translate-x-1/2 w-2 bg-gradient-to-b from-yellow-300 via-orange-400 to-red-400 opacity-60"></div>
                </div>

                <!-- Gradient overlay pro efekt (horizontální) -->
                <div class="absolute inset-0 z-10 pointer-events-none">
                  <div class="absolute top-0 bottom-0 left-0 w-32 bg-gradient-to-r from-gray-900 to-transparent"></div>
                  <div class="absolute top-0 bottom-0 right-0 w-32 bg-gradient-to-l from-gray-900 to-transparent"></div>
                </div>

                <!-- Seznam odměn s animací (horizontální) -->
                <div
                  ref="rewardsContainer"
                  class="absolute top-0 bottom-0 flex transition-transform duration-75 ease-linear"
                  :style="{ transform: `translateX(${scrollOffset}px)` }"
                >
                  <div
                    v-for="(reward, index) in displayedRewards"
                    :key="`${reward.id}-${index}`"
                    class="flex items-center justify-center w-80 h-80 flex-shrink-0 px-4"
                  >
                    <div
                      class="w-full max-w-xs mx-auto bg-gradient-to-br rounded-2xl p-6 border-4 transition-all duration-200"
                      :class="[
                        reward.rarity === 'common' ? 'from-gray-600 to-gray-800 border-gray-500' :
                        reward.rarity === 'uncommon' ? 'from-green-600 to-green-800 border-green-500' :
                        reward.rarity === 'rare' ? 'from-blue-600 to-blue-800 border-blue-500' :
                        reward.rarity === 'epic' ? 'from-purple-600 to-purple-800 border-purple-500' :
                        reward.rarity === 'legendary' ? 'from-yellow-500 to-orange-600 border-yellow-400' :
                        'from-gray-600 to-gray-800 border-gray-500'
                      ]"
                    >
                      <div class="text-center">
                        <div class="text-6xl mb-4">{{ reward.icon }}</div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ reward.title }}</h3>
                        <p class="text-gray-200 text-sm">{{ reward.description }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Informace o rolování -->
              <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm animate-pulse">
                  Bedna se otevírá...
                </p>
              </div>
            </div>

            <!-- Obrazovka s výsledkem -->
            <div
              v-else
              key="result"
              class="bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-2xl shadow-2xl p-10 w-full border-4 relative overflow-hidden text-center result-container"
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
              <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-500/10 rounded-full -mr-20 -mt-20"></div>
              <div class="absolute bottom-0 left-0 w-32 h-32 bg-orange-500/10 rounded-full -ml-16 -mb-16"></div>

              <!-- Zavírací tlačítko -->
              <button
                @click="close"
                class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors z-20"
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
                      class="w-48 h-48 rounded-2xl flex items-center justify-center text-8xl prize-icon border-4"
                      :class="resultBgClass"
                    >
                      {{ resultIcon }}
                    </div>
                    <!-- Prstencový efekt -->
                    <div class="absolute inset-0 rounded-2xl animate-ping opacity-20" :class="resultBgClass"></div>
                    <!-- Světelné paprsky -->
                    <div class="absolute inset-0 rounded-2xl animate-spin-slow opacity-30" :class="resultBgClass" style="filter: blur(20px);"></div>
                  </div>
                </div>

                <!-- Nadpis -->
                <div class="mb-6">
                  <h2 class="text-5xl font-extrabold mb-3 animate-fade-in-up text-white">
                    Gratulujeme! 🎉
                  </h2>
                  <div class="w-32 h-1 mx-auto rounded-full bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500"></div>
                </div>

                <!-- Výhra -->
                <div class="mb-6 animate-fade-in-up-delay">
                  <p class="text-4xl font-bold mb-4 text-white">
                    {{ resultTitle }}
                  </p>
                  <p class="text-xl text-gray-300 leading-relaxed max-w-2xl mx-auto">
                    {{ resultDescription }}
                  </p>
                </div>

                <!-- Tlačítko -->
                <div class="animate-fade-in-up-delay-2">
                  <button
                    @click="close"
                    class="px-10 py-4 bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300"
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
const isRolling = ref(false)
const showResult = ref(false)
const scrollOffset = ref(0)
const rewardsContainer = ref(null)
const containerRef = ref(null)

// Definice odměn s pravděpodobnostmi
const rewards = [
  {
    id: 'free-shipping',
    title: 'Doprava zdarma',
    description: 'Vaše příští objednávka bude doručena zdarma!',
    icon: '🚚',
    rarity: 'common',
    probability: 30, // 30%
    bgClass: 'bg-gradient-to-br from-gray-600 to-gray-800 border-gray-500',
    borderClass: 'border-gray-500'
  },
  {
    id: 'discount-10',
    title: 'Sleva 10%',
    description: 'Získejte slevu 10% na celý nákup!',
    icon: '💰',
    rarity: 'common',
    probability: 25, // 25%
    bgClass: 'bg-gradient-to-br from-gray-600 to-gray-800 border-gray-500',
    borderClass: 'border-gray-500'
  },
  {
    id: 'gift',
    title: 'Dárek zdarma',
    description: 'Získejte malý dárek k vaší objednávce zdarma!',
    icon: '🎁',
    rarity: 'uncommon',
    probability: 20, // 20%
    bgClass: 'bg-gradient-to-br from-green-600 to-green-800 border-green-500',
    borderClass: 'border-green-500'
  },
  {
    id: 'two-plus-one',
    title: '2+1 zdarma',
    description: 'Při nákupu 2 produktů získáte třetí zdarma!',
    icon: '🎯',
    rarity: 'rare',
    probability: 15, // 15%
    bgClass: 'bg-gradient-to-br from-blue-600 to-blue-800 border-blue-500',
    borderClass: 'border-blue-500'
  },
  {
    id: 'three-for-two',
    title: '3 za cenu 2',
    description: 'Kupte 3 produkty a zaplaťte pouze za 2!',
    icon: '💎',
    rarity: 'epic',
    probability: 5, // 5%
    bgClass: 'bg-gradient-to-br from-purple-600 to-purple-800 border-purple-500',
    borderClass: 'border-purple-500'
  },
  {
    id: 'discount-50',
    title: 'Sleva 50%',
    description: 'Získejte obrovskou slevu 50% na celý nákup!',
    icon: '🔥',
    rarity: 'epic',
    probability: 3, // 3%
    bgClass: 'bg-gradient-to-br from-purple-600 to-purple-800 border-purple-500',
    borderClass: 'border-purple-500'
  },
  {
    id: 'golden-reward',
    title: 'Zlatá odměna',
    description: 'VIP status + doprava zdarma na 3 měsíce + sleva 20%!',
    icon: '⭐',
    rarity: 'legendary',
    probability: 2, // 2%
    bgClass: 'bg-gradient-to-br from-yellow-500 to-orange-600 border-yellow-400',
    borderClass: 'border-yellow-400'
  }
]

// Vytvoření pole pro výběr odměny podle pravděpodobnosti
const createWeightedRewards = () => {
  const weightedRewards = []
  rewards.forEach(reward => {
    for (let i = 0; i < reward.probability; i++) {
      weightedRewards.push(reward)
    }
  })
  return weightedRewards
}

const weightedRewards = createWeightedRewards()

// Funkce pro výběr náhodné odměny podle pravděpodobnosti
const selectRandomReward = () => {
  const randomIndex = Math.floor(Math.random() * weightedRewards.length)
  return weightedRewards[randomIndex]
}

// Zobrazené odměny pro animaci (duplikujeme pro plynulé rolování)
const displayedRewards = ref([])

// Výsledek
const result = ref(null)

const resultTitle = computed(() => result.value?.title || '')
const resultDescription = computed(() => result.value?.description || '')
const resultIcon = computed(() => result.value?.icon || '')
const resultBgClass = computed(() => result.value?.bgClass || '')
const resultBorderClass = computed(() => result.value?.borderClass || '')

// Funkce pro spuštění rolování
const startRolling = async () => {
  isRolling.value = true
  showResult.value = false
  
  // Vytvoření seznamu odměn pro animaci (duplikujeme pro plynulé procházení)
  const allRewards = []
  for (let i = 0; i < 50; i++) {
    allRewards.push(...rewards)
  }
  displayedRewards.value = allRewards
  
  // Výběr náhodné odměny
  const selectedReward = selectRandomReward()
  
  // Najít pozici vybrané odměny v seznamu
  let targetIndex = 0
  for (let i = 0; i < displayedRewards.value.length; i++) {
    if (displayedRewards.value[i].id === selectedReward.id) {
      // Najít pozici blízko konce (pro efekt dlouhého rolování)
      targetIndex = Math.floor(displayedRewards.value.length * 0.7) + Math.floor(Math.random() * 10)
      // Ujistit se, že na této pozici je správná odměna
      displayedRewards.value[targetIndex] = { ...selectedReward }
      break
    }
  }
  
  // Animace rolování (horizontální)
  const itemWidth = 320 // w-80 = 320px (80 * 4 = 320px)
  const startOffset = -itemWidth * 5 // Začneme trochu vlevo
  
  // Pro vycentrování: střed položky má být uprostřed kontejneru
  // Střed položky na indexu targetIndex je na pozici: targetIndex * itemWidth + itemWidth/2
  // Pro vycentrování potřebujeme: scrollOffset = -(targetIndex * itemWidth + itemWidth/2 - containerWidth/2)
  // Ale protože zarážka je na 50% kontejneru, potřebujeme: scrollOffset = -(targetIndex * itemWidth + itemWidth/2 - containerWidth/2)
  // Zjednodušeně: pokud je kontejner široký 100%, pak střed je na 50%, takže:
  // scrollOffset = -(targetIndex * itemWidth + itemWidth/2 - 50% šířky kontejneru)
  // Pro jednoduchost: použijeme offset tak, aby střed položky byl na pozici 0 (což je střed kontejneru)
  // Střed položky = targetIndex * itemWidth + itemWidth/2
  // Pro vycentrování: scrollOffset = -(targetIndex * itemWidth + itemWidth/2 - containerCenter)
  // Pokud je containerCenter = 0 (relativně), pak: scrollOffset = -(targetIndex * itemWidth + itemWidth/2)
  
  // Získat šířku kontejneru a vycentrovat výherní položku
  // Počkáme na další frame, aby DOM byl připraven
  await new Promise(resolve => requestAnimationFrame(resolve))
  
  const containerWidth = containerRef.value?.offsetWidth || 800
  const containerCenter = containerWidth / 2
  const itemCenter = targetIndex * itemWidth + itemWidth / 2
  const centeredOffset = -(itemCenter - containerCenter)
  
  scrollOffset.value = startOffset
  
  // Rychlé rolování s výrazným zpomalováním ke konci
  const fastDuration = 2000 // 2 sekundy rychlého rolování
  const slowDuration = 2000 // 2 sekundy zpomalování (delší pro lepší efekt)
  const totalDuration = fastDuration + slowDuration
  
  let startTime = Date.now()
  let animationFrameId = null
  
  const animate = () => {
    const elapsed = Date.now() - startTime
    const progress = Math.min(elapsed / totalDuration, 1)
    
    if (progress < 0.5) {
      // Rychlé rolování (50% času) - konstantní rychlost
      const fastProgress = progress / 0.5
      scrollOffset.value = startOffset + (centeredOffset - startOffset) * fastProgress * 0.8
    } else {
      // Zpomalování (50% času) - výrazné zpomalení ke konci
      const slowProgress = (progress - 0.5) / 0.5
      // Ease-out s vyšším exponentem pro výraznější zpomalení
      const easeOutStrong = 1 - Math.pow(1 - slowProgress, 4)
      scrollOffset.value = startOffset + (centeredOffset - startOffset) * (0.8 + easeOutStrong * 0.2)
    }
    
    if (progress < 1) {
      animationFrameId = requestAnimationFrame(animate)
    } else {
      // Vycentrování výherní položky přesně uprostřed
      scrollOffset.value = centeredOffset
      
      // Zobrazení výsledku po krátké pauze
      setTimeout(() => {
        result.value = selectedReward
        isRolling.value = false
        showResult.value = true
      }, 600)
    }
  }
  
  animationFrameId = requestAnimationFrame(animate)
}

const close = () => {
  isVisible.value = false
  emit('update:modelValue', false)

  // Reset po zavření
  setTimeout(() => {
    isRolling.value = false
    showResult.value = false
    result.value = null
    scrollOffset.value = 0
  }, 300)
}

// Konfety animace
const getConfettiStyle = (index) => {
  const colors = ['#fbbf24', '#f97316', '#ef4444', '#8b5cf6', '#3b82f6', '#10b981']
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
  // Přidat CSS pro animace
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
        transform: scale(0) rotate(0deg);
      }
      50% {
        transform: scale(1.15) rotate(180deg);
      }
      100% {
        transform: scale(1) rotate(360deg);
      }
    }
    
    @keyframes spin-slow {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
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
    
    .animate-spin-slow {
      animation: spin-slow 3s linear infinite;
    }
    
    .prize-icon {
      animation: prize-pop 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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

