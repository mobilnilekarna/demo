<template>
  <DashboardLayout>
    <div class="p-4 relative">
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
          <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Zpracování distribuce</h1>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Spusťte manuální zpracování distribuce pro objednávku
        </p>
      </div>

      <!-- Formulář pro spuštění jobu -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-4">Spustit distribuci</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              ID objednávky <span class="text-red-500">*</span>
            </label>
            <input
              v-model="orderId"
              type="number"
              min="1"
              placeholder="Zadejte ID objednávky"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
              :disabled="isRunning"
            />
          </div>

          <button
            @click="startJob"
            :disabled="!orderId || isRunning"
            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2"
          >
            <svg v-if="isRunning" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ isRunning ? 'Probíhá zpracování...' : 'Spustit distribuci' }}</span>
          </button>
        </div>
      </div>

      <!-- Progress panel -->
      <div v-if="currentJob" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="mb-4">
          <div class="flex items-center justify-between mb-2">
            <h2 class="text-md font-semibold text-gray-800 dark:text-gray-100">Průběh zpracování</h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">Job ID: {{ currentJob.id }}</span>
          </div>

          <!-- Progress bar -->
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 mb-2 overflow-hidden">
            <div
              class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-300 ease-out rounded-full flex items-center justify-center"
              :style="{ width: `${progress}%` }"
            >
              <span v-if="progress > 15" class="text-xs font-semibold text-white">
                {{ progress }}%
              </span>
            </div>
          </div>

          <!-- Progress percentage -->
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">{{ progress }}%</span>
            <span :class="statusColor" class="font-medium">{{ statusText }}</span>
          </div>
        </div>

        <!-- Status message -->
        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
          <div class="flex items-start gap-3">
            <div v-if="isRunning" class="mt-1">
              <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            <div v-else-if="status === 'completed'" class="mt-1">
              <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div v-else-if="status === 'error'" class="mt-1">
              <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 flex-1">{{ message }}</p>
          </div>
        </div>

        <!-- Reset button -->
        <button
          v-if="!isRunning"
          @click="resetJob"
          class="mt-4 w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors"
        >
          Spustit nový job
        </button>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onUnmounted, onMounted } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import axios from 'axios'
import { useToast } from '@/Composables/useToast'

const toast = useToast()

const orderId = ref(1)
const currentJob = ref(null)
const progress = ref(0)
const message = ref('')
const status = ref('idle')
const isRunning = ref(false)
const eventSource = ref(null)
const queuedStartTime = ref(null)

const STORAGE_KEY = 'distribution_current_job_id'
const QUEUED_TIMEOUT = 30000 // 30 sekund ve frontě bez zpracování = pravděpodobně worker neběží

// Načíst uložený jobId při načtení stránky
onMounted(() => {
  const savedJobId = localStorage.getItem(STORAGE_KEY)
  if (savedJobId) {
    // Zkontrolovat, jestli job stále existuje
    checkJobStatus(savedJobId)
  }
})

const statusText = computed(() => {
  switch (status.value) {
    case 'queued':
      return 'Ve frontě'
    case 'processing':
      return 'Zpracovává se'
    case 'completed':
      return 'Dokončeno'
    case 'error':
      return 'Chyba'
    default:
      return 'Čeká'
  }
})

const statusColor = computed(() => {
  switch (status.value) {
    case 'queued':
      return 'text-yellow-600 dark:text-yellow-400'
    case 'processing':
      return 'text-blue-600 dark:text-blue-400'
    case 'completed':
      return 'text-green-600 dark:text-green-400'
    case 'error':
      return 'text-red-600 dark:text-red-400'
    default:
      return 'text-gray-600 dark:text-gray-400'
  }
})

const startJob = async () => {
  if (!orderId.value) {
    toast.error('Chyba', 'Zadejte ID objednávky')
    return
  }

  try {
    isRunning.value = true
    currentJob.value = null
    progress.value = 0
    message.value = 'Spouštím job...'
    status.value = 'queued'

    const response = await axios.post('/dashboard/distribution/start', {
      order_id: orderId.value
    })

    if (response.data.success) {
      currentJob.value = { id: response.data.job_id }
      message.value = 'Job byl úspěšně spuštěn. Čekám na zpracování...'

      // Uložit jobId do localStorage pro obnovení po refreshi
      localStorage.setItem(STORAGE_KEY, response.data.job_id)

      // Spustit SSE stream pro live updates
      startSSEStream(response.data.job_id)
      toast.success('Úspěch', 'Job byl spuštěn')
    }
  } catch (error) {
    isRunning.value = false
    status.value = 'error'
    message.value = error.response?.data?.message || 'Nepodařilo se spustit job'
    toast.error('Chyba', message.value)
  }
}

const startSSEStream = (jobId) => {
  // Zavřít předchozí stream, pokud existuje
  if (eventSource.value) {
    eventSource.value.close()
    eventSource.value = null
  }

  // Vytvořit nový EventSource (SSE stream)
  // Použít absolutní URL pro lepší kompatibilitu
  const url = window.location.origin + `/dashboard/distribution/stream/${jobId}`

  console.log('Spouštím SSE stream:', url)
  eventSource.value = new EventSource(url)

  // Naslouchat na otevření streamu
  eventSource.value.onopen = () => {
    console.log('SSE stream otevřen')
  }

  // Naslouchat na zprávy
  eventSource.value.onmessage = (event) => {
    try {
      const data = JSON.parse(event.data)
      console.log('SSE message:', data)

      updateProgress(data)
    } catch (error) {
      console.error('Chyba při parsování SSE zprávy:', error)
    }
  }

  // Zpracovat chyby
  eventSource.value.onerror = (error) => {
    console.error('SSE stream error:', error)
    console.log('SSE readyState:', eventSource.value?.readyState)

    // EventSource.CONNECTING = 0, EventSource.OPEN = 1, EventSource.CLOSED = 2
    if (eventSource.value?.readyState === EventSource.CLOSED) {
      // Stream byl zavřen - zkusit načíst poslední status přes API jako fallback
      console.log('SSE stream uzavřen, přecházím na polling fallback')
      fetchLastProgress(jobId)
    }
  }
}

const fetchLastProgress = async (jobId) => {
  try {
    const response = await axios.get(`/dashboard/distribution/progress/${jobId}`)
    updateProgress(response.data)
  } catch (error) {
    console.error('Chyba při získávání posledního progressu:', error)
  }
}

const updateProgress = (data) => {
  const currentProgress = data.progress || 0
  const currentStatus = data.status || 'processing'

  // Aktualizovat progress a status
  progress.value = currentProgress
  message.value = data.message || 'Zpracovává se...'
  status.value = currentStatus

  // Kontrola jobu ve frontě příliš dlouho (worker neběží)
  if (currentStatus === 'queued') {
    if (!queuedStartTime.value) {
      queuedStartTime.value = Date.now()
    } else {
      const timeInQueue = Date.now() - queuedStartTime.value

      // Po 20 sekundách ukázat varování, ale pokračovat
      if (timeInQueue > 20000 && timeInQueue < QUEUED_TIMEOUT) {
        message.value = '⏳ Job čeká ve frontě déle než obvykle. Zkontroluj, jestli běží queue worker.'
      }

      if (timeInQueue > QUEUED_TIMEOUT) {
        // Job je ve frontě příliš dlouho - worker pravděpodobně neběží
        status.value = 'error'
        message.value = '⚠️ Job je ve frontě příliš dlouho (' + Math.round(timeInQueue / 1000) + 's). Worker pravděpodobně neběží. Spusť "php artisan queue:work redis" v terminálu, nebo vyčisti frontu pomocí "php artisan queue:clear-redis".'
        isRunning.value = false
        localStorage.removeItem(STORAGE_KEY)

        // Zavřít SSE stream
        if (eventSource.value) {
          eventSource.value.close()
          eventSource.value = null
        }

        toast.error('Worker neběží', 'Job čeká ve frontě příliš dlouho. Zkontroluj, jestli běží queue worker.')
        return
      }
    }
  } else {
    // Pokud job už není ve frontě, resetovat timer
    queuedStartTime.value = null
  }

  // Pokud je job ve frontě nebo zpracovává se, nastavit isRunning na true
  if (currentStatus === 'queued' || currentStatus === 'processing') {
    isRunning.value = true
  }

  // Pokud je dokončeno nebo chyba, zastavit stream
  if (currentStatus === 'completed' || currentStatus === 'error' || currentStatus === 'not_found' || currentStatus === 'timeout') {
    isRunning.value = false

    // Odstranit jobId z localStorage po dokončení/chybě
    if (currentStatus === 'completed' || currentStatus === 'error') {
      localStorage.removeItem(STORAGE_KEY)
    }

    // Zavřít SSE stream
    if (eventSource.value) {
      eventSource.value.close()
      eventSource.value = null
    }

    if (currentStatus === 'completed') {
      toast.success('Hotovo!', 'Distribuce byla úspěšně zpracována')
    } else if (currentStatus === 'error') {
      toast.error('Chyba', data.message || 'Došlo k chybě při zpracování')
    }
  }
}

const checkJobStatus = async (jobId) => {
  try {
    const response = await axios.get(`/dashboard/distribution/progress/${jobId}`)
    const data = response.data

    // Pokud job stále existuje a není dokončený, obnovit sledování
    if (data.status !== 'not_found' && data.status !== 'completed' && data.status !== 'error') {
      currentJob.value = { id: jobId }

      // Pokud je job ve frontě, nastavit čas pro timeout kontrolu
      if (data.status === 'queued') {
        queuedStartTime.value = Date.now()
      }

      // Znovu spustit SSE stream
      startSSEStream(jobId)

      toast.info('Obnoveno', 'Sledování běžícího jobu bylo obnoveno')
    } else {
      // Job už neexistuje nebo je dokončený, odstranit z localStorage
      localStorage.removeItem(STORAGE_KEY)
      resetJob()
    }
  } catch (error) {
    console.error('Chyba při kontrole statusu jobu:', error)
    localStorage.removeItem(STORAGE_KEY)
  }
}

const resetJob = () => {
  currentJob.value = null
  progress.value = 0
  message.value = ''
  status.value = 'idle'
  isRunning.value = false
  queuedStartTime.value = null

  // Odstranit jobId z localStorage
  localStorage.removeItem(STORAGE_KEY)

  // Zavřít SSE stream
  if (eventSource.value) {
    eventSource.value.close()
    eventSource.value = null
  }
}

// Cleanup při unmount
onUnmounted(() => {
  if (eventSource.value) {
    eventSource.value.close()
    eventSource.value = null
  }
})
</script>
