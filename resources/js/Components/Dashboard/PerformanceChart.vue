<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-transparent dark:border-gray-700">
    <!-- Header -->
    <div class="flex items-center gap-2 mb-4">
      <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Přehled prodejů</h2>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
      <div
        v-for="metric in metrics"
        :key="metric.label"
        class="flex items-center space-x-2"
      >
        <div :class="['p-2.5 rounded', metric.bgColor]">
          <component :is="metric.icon" :class="['w-6 h-6', metric.iconColor]" />
        </div>
        <div>
          <p :class="['text-2xl font-bold', metric.textColor]">{{ metric.value }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ metric.label }}</p>
        </div>
      </div>
    </div>

    <!-- Chart -->
    <div class="h-64">
      <Bar :data="chartData" :options="chartOptions" />
    </div>
  </div>
</template>

<script setup>
import { ref, markRaw } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
)

// Icon components
const IconDocument = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`
})

const IconCheck = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
})

const IconStar = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`
})

const IconPrescription = markRaw({
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`
})

const metrics = [
  {
    value: '156',
    label: 'Objednávky',
    icon: IconDocument,
    bgColor: 'bg-warning/10 dark:bg-warning/20',
    iconColor: 'text-warning',
    textColor: 'text-warning'
  },
  {
    value: '48.2k',
    label: 'Tržby (Kč)',
    icon: IconCheck,
    bgColor: 'bg-primary-50 dark:bg-primary-900/20',
    iconColor: 'text-primary-600 dark:text-primary-400',
    textColor: 'text-primary-600 dark:text-primary-400'
  },
  {
    value: '28',
    label: 'Noví zákazníci',
    icon: IconStar,
    bgColor: 'bg-info/10 dark:bg-info/20',
    iconColor: 'text-info',
    textColor: 'text-info'
  },
  {
    value: '342',
    label: 'eRecepty přijaté',
    icon: IconPrescription,
    bgColor: 'bg-secondary-50 dark:bg-secondary-900/20',
    iconColor: 'text-secondary-600 dark:text-secondary-400',
    textColor: 'text-secondary-600 dark:text-secondary-400'
  }
]

const chartData = ref({
  labels: ['Led', 'Úno', 'Bře', 'Dub', 'Kvě', 'Čer', 'Čvc', 'Srp', 'Zář', 'Říj', 'Lis', 'Pro'],
  datasets: [
    {
      label: 'Objednávky',
      data: [120, 135, 98, 142, 128, 156, 138, 168, 152, 185, 192, 220],
      backgroundColor: 'rgba(245, 158, 11, 0.8)', // warning color
      borderColor: '#f59e0b',
      borderWidth: 2,
      borderRadius: 4,
      borderSkipped: false,
      yAxisID: 'y1'
    },
    {
      label: 'Tržby (Kč)',
      data: [42000, 48000, 35000, 52000, 45000, 58000, 51000, 62000, 55000, 68000, 72000, 85000],
      backgroundColor: 'rgba(0, 171, 89, 0.8)', // primary color
      borderColor: '#00ab59',
      borderWidth: 2,
      borderRadius: 4,
      borderSkipped: false,
      yAxisID: 'y'
    },
    {
      label: 'Noví zákazníci',
      data: [25, 32, 18, 28, 22, 35, 30, 42, 38, 48, 52, 65],
      backgroundColor: 'rgba(0, 166, 214, 0.8)', // info color
      borderColor: '#00A6D6',
      borderWidth: 2,
      borderRadius: 4,
      borderSkipped: false,
      yAxisID: 'y1'
    },
    {
      label: 'eRecepty přijaté',
      data: [280, 310, 245, 335, 290, 365, 320, 390, 355, 425, 445, 520],
      backgroundColor: 'rgba(255, 69, 0, 0.8)', // secondary color
      borderColor: '#ff4500',
      borderWidth: 2,
      borderRadius: 4,
      borderSkipped: false,
      yAxisID: 'y1'
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  // Grouped bar chart - 3 sloupce vedle sebe pro každý měsíc
  datasets: {
    bar: {
      barPercentage: 0.7,
      categoryPercentage: 0.9,
      maxBarThickness: 50
    }
  },
  plugins: {
    legend: {
      display: true,
      position: 'bottom',
      labels: {
        padding: 15,
        usePointStyle: true,
        color: '#6b7280',
        font: {
          size: 12
        }
      }
    },
    tooltip: {
      mode: 'index',
      intersect: false,
      backgroundColor: 'rgba(255, 255, 255, 0.95)',
      titleColor: '#1f2937',
      bodyColor: '#1f2937',
      borderColor: '#e5e7eb',
      borderWidth: 1,
      padding: 12,
      cornerRadius: 8,
      displayColors: true,
      callbacks: {
        label: function(context) {
          let label = context.dataset.label || ''
          if (label) {
            label += ': '
          }
          if (context.parsed.y !== null) {
            if (context.datasetIndex === 1) {
              // Tržby - formátovat jako Kč
              label += context.parsed.y.toLocaleString('cs-CZ') + ' Kč'
            } else {
              // Objednávky a zákazníci - jen číslo
              label += context.parsed.y
            }
          }
          return label
        }
      }
    }
  },
  scales: {
    x: {
      stacked: false,
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
      type: 'linear',
      position: 'left',
      stacked: false,
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
          // Pro tržby zobrazit jako Kč
          if (value >= 1000) {
            return value.toLocaleString('cs-CZ') + ' Kč'
          }
          return value
        }
      },
      beginAtZero: true,
      title: {
        display: true,
        text: 'Tržby (Kč)',
        color: '#6b7280',
        font: {
          size: 11
        }
      }
    },
    y1: {
      type: 'linear',
      position: 'right',
      stacked: false,
      grid: {
        display: false
      },
      ticks: {
        color: '#6b7280',
        font: {
          size: 11
        }
      },
      beginAtZero: true,
      title: {
        display: true,
        text: 'Objednávky / Zákazníci',
        color: '#6b7280',
        font: {
          size: 11
        }
      }
    }
  },
  interaction: {
    mode: 'index',
    intersect: false
  }
})
</script>

