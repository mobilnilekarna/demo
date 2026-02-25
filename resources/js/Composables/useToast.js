import { reactive } from 'vue'

// Global toast state
const toasts = reactive([])
let nextId = 0

export function useToast() {
  const show = (type, title, message, duration = 3000) => {
    const id = nextId++
    const toast = {
      id,
      type,
      title,
      message,
      duration,
      progress: 100
    }

    toasts.push(toast)

    // Auto remove after duration
    if (duration > 0) {
      const startTime = Date.now()

      const updateProgress = () => {
        const elapsed = Date.now() - startTime
        const remaining = Math.max(0, duration - elapsed)
        const progressPercent = (remaining / duration) * 100

        const toastIndex = toasts.findIndex(t => t.id === id)
        if (toastIndex > -1) {
          toasts[toastIndex].progress = progressPercent

          if (remaining > 0) {
            requestAnimationFrame(updateProgress)
          }
        }
      }

      requestAnimationFrame(updateProgress)

      setTimeout(() => {
        remove(id)
      }, duration)
    }

    // Max 3 toasts
    if (toasts.length > 3) {
      remove(toasts[0].id)
    }

    return id
  }

  const remove = (id) => {
    const index = toasts.findIndex(t => t.id === id)
    if (index > -1) {
      toasts.splice(index, 1)
    }
  }

  const success = (title, message, duration) => {
    return show('success', title, message, duration)
  }

  const warning = (title, message, duration) => {
    return show('warning', title, message, duration)
  }

  const error = (title, message, duration) => {
    return show('danger', title, message, duration)
  }

  const info = (title, message, duration) => {
    return show('info', title, message, duration)
  }

  const primary = (title, message, duration) => {
    return show('primary', title, message, duration)
  }

  const clear = () => {
    toasts.splice(0, toasts.length)
  }

  return {
    toasts,
    show,
    remove,
    success,
    warning,
    error,
    info,
    primary,
    clear
  }
}

