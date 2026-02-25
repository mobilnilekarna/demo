<template>
  <div class="bg-white rounded-xl shadow-lg p-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
      <div class="p-2 bg-blue-50 rounded-lg mr-3">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h2 class="text-xl font-bold text-gray-800">Performance Indicator</h2>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div
        v-for="metric in metrics"
        :key="metric.label"
        class="flex items-center space-x-3"
      >
        <div :class="['p-3 rounded-lg', metric.bgColor]">
          <component :is="metric.icon" :class="['w-6 h-6', metric.iconColor]" />
        </div>
        <div>
          <p :class="['text-2xl font-bold', metric.textColor]">{{ metric.value }}</p>
          <p class="text-sm text-gray-600">{{ metric.label }}</p>
        </div>
      </div>
    </div>

    <!-- Chart -->
    <div class="h-80">
      <Line :data="chartData" :options="chartOptions" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
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
  Title,
  Tooltip,
  Legend,
  Filler
)

const metrics = [
  {
    value: '40',
    label: 'Attends',
    icon: 'IconDocument',
    bgColor: 'bg-orange-50',
    iconColor: 'text-orange-500',
    textColor: 'text-orange-500'
  },
  {
    value: '125',
    label: 'Tasks Done',
    icon: 'IconCheck',
    bgColor: 'bg-purple-50',
    iconColor: 'text-purple-500',
    textColor: 'text-purple-500'
  },
  {
    value: '17',
    label: 'Complaints',
    icon: 'IconAlert',
    bgColor: 'bg-pink-50',
    iconColor: 'text-pink-500',
    textColor: 'text-pink-500'
  },
  {
    value: '18',
    label: 'Referrals',
    icon: 'IconStar',
    bgColor: 'bg-blue-50',
    iconColor: 'text-blue-500',
    textColor: 'text-blue-500'
  }
]

const chartData = ref({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Sept', 'Oct', 'Dec'],
  datasets: [
    {
      label: 'Series 1',
      data: [80, 90, 70, 85, 75, 90, 80, 95, 85, 100, 95, 90],
      borderColor: '#8b5cf6',
      backgroundColor: 'rgba(139, 92, 246, 0.2)',
      fill: true,
      tension: 0.4,
      pointRadius: 0,
      pointHoverRadius: 6
    },
    {
      label: 'Series 2',
      data: [60, 70, 50, 65, 55, 70, 60, 75, 65, 80, 75, 70],
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59, 130, 246, 0.2)',
      fill: true,
      tension: 0.4,
      pointRadius: 0,
      pointHoverRadius: 6
    },
    {
      label: 'Series 3',
      data: [40, 50, 30, 45, 35, 50, 40, 55, 45, 60, 55, 50],
      borderColor: '#ec4899',
      backgroundColor: 'rgba(236, 72, 153, 0.2)',
      fill: true,
      tension: 0.4,
      pointRadius: 3,
      pointBackgroundColor: '#ec4899',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverRadius: 6
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      mode: 'index',
      intersect: false,
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      cornerRadius: 8
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        color: '#9ca3af'
      }
    },
    y: {
      grid: {
        color: 'rgba(0, 0, 0, 0.05)',
        drawBorder: false
      },
      ticks: {
        color: '#9ca3af',
        stepSize: 35
      },
      min: 0,
      max: 140
    }
  },
  interaction: {
    mode: 'nearest',
    axis: 'x',
    intersect: false
  }
})
</script>

<script>
const IconDocument = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`
}

const IconCheck = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
}

const IconAlert = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`
}

const IconStar = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`
}

export default {
  components: {
    IconDocument,
    IconCheck,
    IconAlert,
    IconStar
  }
}
</script>

