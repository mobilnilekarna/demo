<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-transparent dark:border-gray-700">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-2">
        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
          <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Objednávky za posledních 6 měsíců</h2>
      </div>
      <div class="flex items-center gap-2">
        <select v-model="selectedPeriod" class="text-sm border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
          <option value="6">Posledních 6 měsíců</option>
          <option value="12">Posledních 12 měsíců</option>
          <option value="3">Posledních 3 měsíce</option>
        </select>
      </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Line Chart - Sales -->
      <div>
        <div class="h-72">
          <Line :data="salesChartData" :options="salesChartOptions" />
        </div>
        <div class="flex items-center justify-center mt-4 gap-2">
          <span class="w-8 h-1 rounded bg-primary-500"></span>
          <span class="text-base text-gray-600 dark:text-gray-400">Prodej objednávek</span>
        </div>
      </div>

      <!-- Bar Chart - Order Count -->
      <div>
        <div class="h-72">
          <Bar :data="ordersChartData" :options="ordersChartOptions" />
        </div>
        <div class="flex items-center justify-center mt-4 gap-2">
          <span class="w-4 h-4 rounded bg-pink-200 border border-pink-300"></span>
          <span class="text-base text-gray-600 dark:text-gray-400">Počet objednávek</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Line, Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
)

const selectedPeriod = ref('6')

// Data for last 6 months
const monthLabels = ['Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec']
const salesData = [850000, 495000, 610000, 450000, 625000, 680000, 865000]
const ordersData = [945, 620, 725, 450, 685, 745, 890]

// Pastel colors for bar chart (matching the screenshot)
const barColors = [
  'rgba(134, 239, 172, 0.7)', // green
  'rgba(253, 224, 71, 0.7)',  // yellow
  'rgba(196, 181, 253, 0.7)', // purple
  'rgba(147, 197, 253, 0.7)', // blue
  'rgba(147, 197, 253, 0.7)', // blue
  'rgba(253, 224, 71, 0.7)',  // yellow
]

const barBorderColors = [
  'rgba(134, 239, 172, 1)',
  'rgba(253, 224, 71, 1)',
  'rgba(196, 181, 253, 1)',
  'rgba(147, 197, 253, 1)',
  'rgba(147, 197, 253, 1)',
  'rgba(253, 224, 71, 1)',
]

// Generate colors for each bar
const generateBarColors = (count) => {
  const colors = [
    'rgba(252, 165, 165, 0.6)', // red/pink
    'rgba(134, 239, 172, 0.6)', // green
    'rgba(253, 224, 71, 0.6)',  // yellow
    'rgba(196, 181, 253, 0.6)', // purple
    'rgba(147, 197, 253, 0.6)', // blue
    'rgba(253, 224, 71, 0.6)',  // yellow
    'rgba(147, 197, 253, 0.6)', // blue
  ]
  return colors.slice(0, count)
}

const generateBarBorderColors = (count) => {
  const colors = [
    'rgba(252, 165, 165, 1)',
    'rgba(134, 239, 172, 1)',
    'rgba(253, 224, 71, 1)',
    'rgba(196, 181, 253, 1)',
    'rgba(147, 197, 253, 1)',
    'rgba(253, 224, 71, 1)',
    'rgba(147, 197, 253, 1)',
  ]
  return colors.slice(0, count)
}

// Sales Line Chart
const salesChartData = computed(() => ({
  labels: monthLabels,
  datasets: [
    {
      label: 'Prodej objednávek',
      data: salesData,
      borderColor: '#22c55e',
      backgroundColor: 'rgba(34, 197, 94, 0.1)',
      fill: true,
      tension: 0.4,
      pointRadius: 6,
      pointBackgroundColor: '#22c55e',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverRadius: 8
    }
  ]
}))

const salesChartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      backgroundColor: 'rgba(255, 255, 255, 0.95)',
      titleColor: '#1f2937',
      bodyColor: '#1f2937',
      borderColor: '#e5e7eb',
      borderWidth: 1,
      padding: 12,
      cornerRadius: 8,
      displayColors: false,
      callbacks: {
        label: function(context) {
          return context.parsed.y.toLocaleString('cs-CZ') + ' Kč'
        }
      }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        color: '#6b7280',
        font: {
          size: 11
        }
      }
    },
    y: {
      grid: {
        color: 'rgba(0, 0, 0, 0.05)',
        drawBorder: false
      },
      ticks: {
        color: '#6b7280',
        font: {
          size: 11
        },
        callback: function(value) {
          return value.toLocaleString('cs-CZ') + ' Kč'
        }
      },
      min: 450000,
      max: 900000
    }
  },
  interaction: {
    mode: 'nearest',
    axis: 'x',
    intersect: false
  }
})

// Orders Bar Chart
const ordersChartData = computed(() => ({
  labels: monthLabels,
  datasets: [
    {
      label: 'Počet objednávek',
      data: ordersData,
      backgroundColor: generateBarColors(ordersData.length),
      borderColor: generateBarBorderColors(ordersData.length),
      borderWidth: 1,
      borderRadius: 4,
      barThickness: 40
    }
  ]
}))

const ordersChartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      backgroundColor: 'rgba(255, 255, 255, 0.95)',
      titleColor: '#1f2937',
      bodyColor: '#1f2937',
      borderColor: '#e5e7eb',
      borderWidth: 1,
      padding: 12,
      cornerRadius: 8,
      displayColors: false,
      callbacks: {
        label: function(context) {
          return context.parsed.y + ' x'
        }
      }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        color: '#6b7280',
        font: {
          size: 11
        }
      }
    },
    y: {
      grid: {
        color: 'rgba(0, 0, 0, 0.05)',
        drawBorder: false
      },
      ticks: {
        color: '#6b7280',
        font: {
          size: 11
        },
        callback: function(value) {
          return value + ' x'
        }
      },
      min: 0,
      max: 1000
    }
  }
})
</script>

