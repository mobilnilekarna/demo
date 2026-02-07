import { ref, computed, watch } from 'vue'

const STORAGE_KEY = 'dashboard-goals'

// Výchozí cíle
const defaultGoals = {
  orders: {
    month: 200,
    sixMonths: 1200,
    year: 2400
  },
  revenue: {
    month: 80000,
    sixMonths: 480000,
    year: 960000
  }
}

// Načíst cíle z localStorage
const loadGoals = () => {
  try {
    const stored = localStorage.getItem(STORAGE_KEY)
    if (stored) {
      return JSON.parse(stored)
    }
  } catch (e) {
    console.error('Chyba při načítání cílů:', e)
  }
  return defaultGoals
}

// Uložit cíle do localStorage
const saveGoals = (goals) => {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(goals))
  } catch (e) {
    console.error('Chyba při ukládání cílů:', e)
  }
}

// Reaktivní stav cílů
const goals = ref(loadGoals())

// Uložit cíle při změně
watch(goals, (newGoals) => {
  saveGoals(newGoals)
}, { deep: true })

export function useGoals() {
  // Aktuální hodnoty (simulace - v reálné aplikaci by to přišlo z API)
  const currentValues = ref({
    orders: {
      month: 150, // aktuální počet objednávek za měsíc
      sixMonths: 900,
      year: 1800
    },
    revenue: {
      month: 60000, // aktuální tržby za měsíc
      sixMonths: 360000,
      year: 720000
    }
  })

  // Vypočítat progress pro každý cíl
  const getProgress = (type, period) => {
    const current = currentValues.value[type][period]
    const target = goals.value[type][period]
    if (!target || target === 0) return 0
    return Math.min(Math.round((current / target) * 100), 100)
  }

  // Zjistit stav cíle
  const getStatus = (type, period) => {
    const progress = getProgress(type, period)
    if (progress >= 100) return 'completed'
    if (progress >= 75) return 'good'
    if (progress >= 50) return 'progress'
    return 'low'
  }

  // Získat emoji podle stavu
  const getEmoji = (status) => {
    switch (status) {
      case 'completed':
        return '🎉'
      case 'good':
        return '😊'
      case 'progress':
        return '😐'
      case 'low':
        return '😔'
      default:
        return '📊'
    }
  }

  // Získat barvu podle stavu
  const getColor = (status) => {
    switch (status) {
      case 'completed':
        return 'bg-gradient-to-r from-green-400 to-green-500'
      case 'good':
        return 'bg-gradient-to-r from-primary-400 to-primary-500'
      case 'progress':
        return 'bg-gradient-to-r from-warning to-warning'
      case 'low':
        return 'bg-gradient-to-r from-secondary-400 to-secondary-500'
      default:
        return 'bg-gradient-to-r from-gray-400 to-gray-500'
    }
  }

  // Aktualizovat cíle
  const updateGoals = (newGoals) => {
    goals.value = { ...goals.value, ...newGoals }
  }

  // Aktualizovat aktuální hodnoty (pro simulaci)
  const updateCurrentValues = (newValues) => {
    currentValues.value = { ...currentValues.value, ...newValues }
  }

  // Získat všechny cíle s progress
  const getAllGoalsWithProgress = () => {
    const result = []
    
    // Objednávky
    result.push({
      id: 'orders-month',
      type: 'orders',
      period: 'month',
      label: 'Počet objednávek (měsíc)',
      current: currentValues.value.orders.month,
      target: goals.value.orders.month,
      progress: getProgress('orders', 'month'),
      status: getStatus('orders', 'month'),
      emoji: getEmoji(getStatus('orders', 'month')),
      color: getColor(getStatus('orders', 'month'))
    })

    result.push({
      id: 'orders-six-months',
      type: 'orders',
      period: 'sixMonths',
      label: 'Počet objednávek (6 měsíců)',
      current: currentValues.value.orders.sixMonths,
      target: goals.value.orders.sixMonths,
      progress: getProgress('orders', 'sixMonths'),
      status: getStatus('orders', 'sixMonths'),
      emoji: getEmoji(getStatus('orders', 'sixMonths')),
      color: getColor(getStatus('orders', 'sixMonths'))
    })

    result.push({
      id: 'orders-year',
      type: 'orders',
      period: 'year',
      label: 'Počet objednávek (rok)',
      current: currentValues.value.orders.year,
      target: goals.value.orders.year,
      progress: getProgress('orders', 'year'),
      status: getStatus('orders', 'year'),
      emoji: getEmoji(getStatus('orders', 'year')),
      color: getColor(getStatus('orders', 'year'))
    })

    // Tržby
    result.push({
      id: 'revenue-month',
      type: 'revenue',
      period: 'month',
      label: 'Tržby za měsíc',
      current: currentValues.value.revenue.month,
      target: goals.value.revenue.month,
      progress: getProgress('revenue', 'month'),
      status: getStatus('revenue', 'month'),
      emoji: getEmoji(getStatus('revenue', 'month')),
      color: getColor(getStatus('revenue', 'month')),
      formatValue: (val) => `${val.toLocaleString('cs-CZ')} Kč`
    })

    result.push({
      id: 'revenue-six-months',
      type: 'revenue',
      period: 'sixMonths',
      label: 'Tržby za 6 měsíců',
      current: currentValues.value.revenue.sixMonths,
      target: goals.value.revenue.sixMonths,
      progress: getProgress('revenue', 'sixMonths'),
      status: getStatus('revenue', 'sixMonths'),
      emoji: getEmoji(getStatus('revenue', 'sixMonths')),
      color: getColor(getStatus('revenue', 'sixMonths')),
      formatValue: (val) => `${val.toLocaleString('cs-CZ')} Kč`
    })

    result.push({
      id: 'revenue-year',
      type: 'revenue',
      period: 'year',
      label: 'Tržby za rok',
      current: currentValues.value.revenue.year,
      target: goals.value.revenue.year,
      progress: getProgress('revenue', 'year'),
      status: getStatus('revenue', 'year'),
      emoji: getEmoji(getStatus('revenue', 'year')),
      color: getColor(getStatus('revenue', 'year')),
      formatValue: (val) => `${val.toLocaleString('cs-CZ')} Kč`
    })

    return result
  }

  return {
    goals: computed(() => goals.value),
    currentValues: computed(() => currentValues.value),
    updateGoals,
    updateCurrentValues,
    getAllGoalsWithProgress,
    getProgress,
    getStatus,
    getEmoji,
    getColor
  }
}

