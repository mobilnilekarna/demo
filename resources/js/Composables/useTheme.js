import { ref, watch } from 'vue'

// Globální reactive state pro theme
const currentTheme = ref('light')

// Načtení theme z localStorage
const loadThemeFromStorage = () => {
  if (typeof window === 'undefined') return 'light'

  try {
    const stored = localStorage.getItem('app-theme')
    if (stored && (stored === 'light' || stored === 'dark')) {
      return stored
    }
  } catch (e) {
    console.warn('Failed to load theme from localStorage:', e)
  }
  return 'light'
}

// Uložení theme do localStorage
const saveThemeToStorage = (theme) => {
  if (typeof window === 'undefined') return

  try {
    localStorage.setItem('app-theme', theme)
  } catch (e) {
    console.warn('Failed to save theme to localStorage:', e)
  }
}

// Aplikace theme na document
const applyTheme = (theme) => {
  if (typeof document === 'undefined') return

  if (theme === 'dark') {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

// Inicializace theme
currentTheme.value = loadThemeFromStorage()
applyTheme(currentTheme.value)

// Watch pro automatické ukládání a aplikování
watch(currentTheme, (newTheme) => {
  saveThemeToStorage(newTheme)
  applyTheme(newTheme)
})

/**
 * Composable pro práci s dark/light mode
 */
export function useTheme() {
  const setTheme = (theme) => {
    if (theme === 'light' || theme === 'dark') {
      currentTheme.value = theme
    }
  }

  const toggleTheme = () => {
    currentTheme.value = currentTheme.value === 'light' ? 'dark' : 'light'
  }

  const isDark = () => currentTheme.value === 'dark'
  const isLight = () => currentTheme.value === 'light'

  return {
    theme: currentTheme,
    setTheme,
    toggleTheme,
    isDark,
    isLight
  }
}

