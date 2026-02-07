<template>
  <DashboardLayout>
    <TourGuideManager
      ref="tourManagerRef"
      :steps="tourSteps"
      :auto-start="false"
      :show-overlay="true"
      :allow-interactions="true"
      @complete="onTourComplete"
    />
    <div class="p-3 md:p-4 space-y-4">
      <!-- Rychlé statistiky dnes -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" data-tour-guide="step-3">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="p-2.5 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
              <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">24</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Objednávky dnes</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="p-2.5 bg-warning/20 rounded-lg">
              <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <div>
              <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">7</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Čekají na odeslání</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="p-2.5 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg">
              <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <div>
              <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">12</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Nízké zásoby</p>
            </div>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border border-gray-100 dark:border-gray-700">
          <div class="flex items-center gap-3">
            <div class="p-2.5 bg-info/20 rounded-lg">
              <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">5</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">Noví zákazníci</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Distributoři -->
      <div data-tour-guide="step-4">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Distributoři</h3>
            <div class="flex items-center gap-1 ml-1">
              <button
                @click="distributorsSwiperPrev"
                class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors disabled:opacity-40"
                :disabled="distributorsIsBeginning"
              >
                <svg class="w-3.5 h-3.5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
              </button>
              <button
                @click="distributorsSwiperNext"
                class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors disabled:opacity-40"
                :disabled="distributorsIsEnd"
              >
                <svg class="w-3.5 h-3.5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </div>
          <span class="text-sm text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">Spravovat</span>
        </div>
        <div class="distributors-swiper-container">
          <swiper
            :slides-per-view="6.2"
            :space-between="8"
            :modules="swiperModules"
            :breakpoints="{
              320: { slidesPerView: 2.2 },
              640: { slidesPerView: 3.2 },
              768: { slidesPerView: 4.2 },
              1024: { slidesPerView: 5.2 },
              1280: { slidesPerView: 6.2 }
            }"
            class="distributors-swiper"
            @swiper="onDistributorsSwiper"
            @slideChange="onDistributorsSlideChange"
          >
            <swiper-slide
              v-for="(distributor, index) in distributors"
              :key="index"
            >
              <div
                class="rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow cursor-pointer border h-full"
                :class="distributor.bgClass"
              >
                <h4 class="text-base font-bold truncate mb-2" :class="distributor.textClass">{{ distributor.name }}</h4>
                <div class="flex items-center gap-1.5 mb-1">
                  <svg class="w-3.5 h-3.5" :class="distributor.iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                  </svg>
                  <span class="text-sm font-medium" :class="distributor.subTextClass">{{ distributor.products }} produktů</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5" :class="distributor.iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm" :class="distributor.subTextClass">{{ distributor.lastUpdate }}</span>
                </div>
                <div class="mt-2">
                  <span
                    class="inline-block px-2 py-0.5 text-xs font-medium rounded"
                    :class="distributor.badgeClass"
                  >
                    {{ distributor.status }}
                  </span>
                </div>
              </div>
            </swiper-slide>
          </swiper>
        </div>
      </div>

      <!-- Orders Charts - Line + Bar -->
      <div data-tour-guide="step-5">
        <OrdersChart />
      </div>

      <!-- Performance Chart and Achievement Target -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="xl:col-span-2">
          <PerformanceChart />
        </div>
        <div>
          <AchievementTarget />
        </div>
      </div>

      <!-- Informační bloky pro lékárny -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Nejprodávanější produkty -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-transparent dark:border-gray-700" data-tour-guide="step-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Nejprodávanější produkty</h3>
            </div>
            <span class="text-sm text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">Zobrazit vše</span>
          </div>
          <div class="space-y-2.5">
            <div v-for="(product, index) in topProducts" :key="index" class="flex items-center gap-2.5">
              <span class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                    :class="index === 0 ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'">
                {{ index + 1 }}
              </span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ product.name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ product.sold }} prodáno</p>
              </div>
              <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ product.price }} Kč</span>
            </div>
          </div>
        </div>

        <!-- Poslední objednávky -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-transparent dark:border-gray-700" data-tour-guide="step-7">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Poslední objednávky</h3>
            </div>
            <span class="text-sm text-primary-600 dark:text-primary-400 hover:underline cursor-pointer">Zobrazit vše</span>
          </div>
          <div class="space-y-2.5">
            <div v-for="(order, index) in recentOrders" :key="index" class="flex items-center gap-2.5">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ order.customer }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ order.date }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ order.total }} Kč</p>
                <p class="text-xs" :class="order.statusTextClass">{{ order.status }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Upozornění a důležité informace -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-transparent dark:border-gray-700">
          <div class="flex items-center gap-2 mb-4">
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Upozornění</h3>
          </div>
          <div class="space-y-3">
            <div v-for="(alert, index) in alerts" :key="index" class="flex items-start gap-3 p-3 rounded-lg" :class="alert.bgClass">
              <svg class="w-5 h-5 mt-0.5 flex-shrink-0" :class="alert.iconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="alert.icon"/>
              </svg>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium" :class="alert.textClass">{{ alert.title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ alert.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Rychlé akce -->
      <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow p-5 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex items-start gap-3">
            <div class="p-1.5 bg-white/20 rounded-lg">
              <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <div>
              <h3 class="text-base font-semibold mb-1">Rychlé akce</h3>
              <p class="text-base text-primary-100">Často používané funkce pro správu lékárny</p>
            </div>
          </div>
          <div class="flex flex-wrap gap-2">
            <button class="px-5 py-2.5 bg-white/20 hover:bg-white/30 rounded-lg text-base font-medium transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              Nový produkt
            </button>
            <button class="px-5 py-2.5 bg-white/20 hover:bg-white/30 rounded-lg text-base font-medium transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Nová objednávka
            </button>
            <button class="px-5 py-2.5 bg-white/20 hover:bg-white/30 rounded-lg text-base font-medium transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Exportovat report
            </button>
            <button class="px-5 py-2.5 bg-white/20 hover:bg-white/30 rounded-lg text-base font-medium transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Nastavení
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick, provide, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import StatCard from '@/Components/Dashboard/StatCard.vue'
import PerformanceChart from '@/Components/Dashboard/PerformanceChart.vue'
import AchievementTarget from '@/Components/Dashboard/AchievementTarget.vue'
import OrdersChart from '@/Components/Dashboard/OrdersChart.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import { TourGuideManager } from 'v-tour-guide'
import { useToast } from '@/Composables/useToast'

const swiperModules = [Navigation]
const toast = useToast()

// Tour Guide
const tourManagerRef = ref(null)
const tourSteps = [
  {
    id: 'step-2',
    title: 'Váš účet',
    content: 'Toto je váš uživatelský účet. Kliknutím zobrazíte profil, nastavení a možnost odhlášení.',
    target: '[data-tour-guide="step-2"]',
    direction: 'bottom',
    showAction: true
  },
  {
    id: 'step-3',
    title: 'Rychlé statistiky',
    content: 'Zde vidíte rychlý přehled statistik - objednávky, zásoby a další důležité informace na první pohled.',
    target: '[data-tour-guide="step-3"]',
    direction: 'bottom',
    showAction: true
  },
  {
    id: 'step-4',
    title: 'Distributoři',
    content: 'Zde vidíte přehled všech distributorů s informacemi o počtu produktů, poslední aktualizaci a jejich stavu. Můžete procházet pomocí šipek.',
    target: '[data-tour-guide="step-4"]',
    direction: 'bottom',
    showAction: true
  },
  {
    id: 'step-5',
    title: 'Grafy objednávek',
    content: 'Zde najdete vizualizaci objednávek v čase. Grafy vám pomohou lépe pochopit trendy a vývoj vašich objednávek.',
    target: '[data-tour-guide="step-5"]',
    direction: 'bottom',
    showAction: true
  },
  {
    id: 'step-6',
    title: 'Nejprodávanější produkty',
    content: 'Tento blok zobrazuje produkty, které se prodávají nejlépe. Můžete zde rychle zjistit, které produkty jsou nejpopulárnější.',
    target: '[data-tour-guide="step-6"]',
    direction: 'bottom',
    showAction: true
  },
  {
    id: 'step-7',
    title: 'Poslední objednávky',
    content: 'Zde vidíte přehled nejnovějších objednávek s informacemi o zákaznících, částkách a stavu zpracování.',
    target: '[data-tour-guide="step-7"]',
    direction: 'bottom',
    showAction: true
  }
]

// Distributors swiper control
const distributorsSwiperInstance = ref(null)
const distributorsIsBeginning = ref(true)
const distributorsIsEnd = ref(false)

const onDistributorsSwiper = (swiper) => {
  distributorsSwiperInstance.value = swiper
  distributorsIsBeginning.value = swiper.isBeginning
  distributorsIsEnd.value = swiper.isEnd
}

const onDistributorsSlideChange = () => {
  if (distributorsSwiperInstance.value) {
    distributorsIsBeginning.value = distributorsSwiperInstance.value.isBeginning
    distributorsIsEnd.value = distributorsSwiperInstance.value.isEnd
  }
}

const distributorsSwiperPrev = () => {
  distributorsSwiperInstance.value?.slidePrev()
}

const distributorsSwiperNext = () => {
  distributorsSwiperInstance.value?.slideNext()
}

// Distributoři
const distributors = ref([
  {
    name: 'Alliance',
    products: 1245,
    lastUpdate: '15.12. 08:05',
    status: 'Aktivní',
    bgClass: 'bg-cyan-50 border-cyan-200',
    textClass: 'text-cyan-800',
    subTextClass: 'text-cyan-700',
    iconClass: 'text-cyan-600',
    badgeClass: 'bg-cyan-200 text-cyan-800'
  },
  {
    name: 'Phoenix',
    products: 892,
    lastUpdate: '16.12. 18:10',
    status: 'Aktivní',
    bgClass: 'bg-emerald-50 border-emerald-200',
    textClass: 'text-emerald-800',
    subTextClass: 'text-emerald-700',
    iconClass: 'text-emerald-600',
    badgeClass: 'bg-emerald-200 text-emerald-800'
  },
  {
    name: 'Pharmos',
    products: 634,
    lastUpdate: '16.12. 12:30',
    status: 'Aktivní',
    bgClass: 'bg-violet-50 border-violet-200',
    textClass: 'text-violet-800',
    subTextClass: 'text-violet-700',
    iconClass: 'text-violet-600',
    badgeClass: 'bg-violet-200 text-violet-800'
  },
  {
    name: 'Avenier',
    products: 421,
    lastUpdate: '15.12. 22:45',
    status: 'Aktivní',
    bgClass: 'bg-amber-50 border-amber-200',
    textClass: 'text-amber-800',
    subTextClass: 'text-amber-700',
    iconClass: 'text-amber-600',
    badgeClass: 'bg-amber-200 text-amber-800'
  },
  {
    name: 'Medicom',
    products: 356,
    lastUpdate: '14.12. 16:20',
    status: 'Aktivní',
    bgClass: 'bg-rose-50 border-rose-200',
    textClass: 'text-rose-800',
    subTextClass: 'text-rose-700',
    iconClass: 'text-rose-600',
    badgeClass: 'bg-rose-200 text-rose-800'
  },
  {
    name: 'Gehe',
    products: 289,
    lastUpdate: '16.12. 09:15',
    status: 'Aktivní',
    bgClass: 'bg-blue-50 border-blue-200',
    textClass: 'text-blue-800',
    subTextClass: 'text-blue-700',
    iconClass: 'text-blue-600',
    badgeClass: 'bg-blue-200 text-blue-800'
  },
  {
    name: 'Tamda',
    products: 178,
    lastUpdate: '13.12. 14:00',
    status: 'Neaktivní',
    bgClass: 'bg-slate-100 border-slate-200',
    textClass: 'text-slate-600',
    subTextClass: 'text-slate-500',
    iconClass: 'text-slate-400',
    badgeClass: 'bg-slate-200 text-slate-600'
  },
  {
    name: 'Zentiva',
    products: 523,
    lastUpdate: '16.12. 07:30',
    status: 'Aktivní',
    bgClass: 'bg-teal-50 border-teal-200',
    textClass: 'text-teal-800',
    subTextClass: 'text-teal-700',
    iconClass: 'text-teal-600',
    badgeClass: 'bg-teal-200 text-teal-800'
  }
])

// Nejprodávanější produkty
const topProducts = ref([
  { name: 'Paralen 500mg', sold: 245, price: '89' },
  { name: 'Ibalgin 400mg', sold: 198, price: '129' },
  { name: 'Vitamín C 1000mg', sold: 176, price: '199' },
  { name: 'Coldrex MaxGrip', sold: 143, price: '249' },
  { name: 'Aspirin 100mg', sold: 128, price: '149' }
])

// Poslední objednávky
const recentOrders = ref([
  {
    customer: 'Jan Novák',
    date: 'Dnes, 14:32',
    total: '456',
    status: 'Nová',
    statusClass: 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400',
    statusTextClass: 'text-primary-600 dark:text-primary-400'
  },
  {
    customer: 'Marie Svobodová',
    date: 'Dnes, 12:15',
    total: '892',
    status: 'Zpracovává se',
    statusClass: 'bg-warning/20 text-warning',
    statusTextClass: 'text-warning'
  },
  {
    customer: 'Petr Dvořák',
    date: 'Včera, 18:45',
    total: '234',
    status: 'Odesláno',
    statusClass: 'bg-info/20 text-info',
    statusTextClass: 'text-info'
  },
  {
    customer: 'Eva Králová',
    date: 'Včera, 16:20',
    total: '1 245',
    status: 'Doručeno',
    statusClass: 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
    statusTextClass: 'text-green-600 dark:text-green-400'
  }
])

// Upozornění
const alerts = ref([
  {
    title: 'Expirace produktů',
    description: '5 produktů vyprší do 30 dnů',
    icon: 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    bgClass: 'bg-secondary-50 dark:bg-secondary-900/20',
    iconClass: 'text-secondary-500',
    textClass: 'text-secondary-700 dark:text-secondary-400'
  },
  {
    title: 'Nízké zásoby',
    description: '12 produktů pod minimální zásobou',
    icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    bgClass: 'bg-warning/10',
    iconClass: 'text-warning',
    textClass: 'text-amber-700 dark:text-amber-400'
  },
  {
    title: 'Nové recenze',
    description: '3 nové recenze čekají na schválení',
    icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    bgClass: 'bg-info/10',
    iconClass: 'text-info',
    textClass: 'text-blue-700 dark:text-blue-400'
  }
])

// Funkce pro spuštění tour - poskytneme přes provide
const startTour = () => {
  if (tourManagerRef.value && tourManagerRef.value.startTourGuide) {
    tourManagerRef.value.startTourGuide('step-2')
  }
}

// Poskytneme funkci startTour pro Header komponentu
provide('startTour', startTour)

// Keyboard handling pro tour guide
const handleTourKeyboard = (event) => {
  if (!tourManagerRef.value || !tourManagerRef.value.isActive) {
    return
  }

  // Escape - ukončit tour
  if (event.key === 'Escape') {
    event.preventDefault()
    if (tourManagerRef.value.skipTourGuide) {
      tourManagerRef.value.skipTourGuide()
    }
    return
  }

  // ArrowRight nebo ArrowDown - další krok
  if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
    event.preventDefault()
    if (tourManagerRef.value.nextStep) {
      tourManagerRef.value.nextStep()
    }
    return
  }

  // ArrowLeft nebo ArrowUp - předchozí krok
  if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
    event.preventDefault()
    if (tourManagerRef.value.previousStep) {
      tourManagerRef.value.previousStep()
    }
    return
  }
}

// Spustit tour automaticky při načtení stránky (pouze při první návštěvě)
onMounted(() => {
  // Přidat keyboard listener
  window.addEventListener('keydown', handleTourKeyboard)

  // Zkontrolovat, zda uživatel už viděl tour
  const hasSeenTour = localStorage.getItem('dashboard-tour-seen')

  if (!hasSeenTour) {
    nextTick(() => {
      // Malé zpoždění, aby se zajistilo, že všechny elementy jsou načtené
      setTimeout(() => {
        if (tourManagerRef.value && tourManagerRef.value.startTourGuide) {
          tourManagerRef.value.startTourGuide('step-2')
        }
      }, 1000)
    })
  }
})

// Odstranit keyboard listener při unmount
onUnmounted(() => {
  window.removeEventListener('keydown', handleTourKeyboard)
})

// Označit tour jako zhlédnutou po dokončení
const onTourComplete = () => {
  localStorage.setItem('dashboard-tour-seen', 'true')
  toast.success(
    'Průvodce dokončen!',
    'Nyní znáte základní prvky dashboardu. Přejeme příjemnou práci!',
    5000
  )
}
</script>

<style scoped>
.distributors-swiper-container {
  position: relative;
}

.distributors-swiper {
  padding: 4px 0;
}

.distributors-swiper :deep(.swiper-slide) {
  height: auto;
}
</style>

