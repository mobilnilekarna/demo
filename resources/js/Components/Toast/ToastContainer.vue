<template>
  <Teleport to="body">
    <div class="toaster-container">
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="['toaster-notification', toast.type]"
          @click="remove(toast.id)"
        >
          <div class="toaster-icon">{{ getIcon(toast.type) }}</div>
          <div class="toaster-content">
            <div class="toaster-title">{{ toast.title }}</div>
            <div class="toaster-message">{{ toast.message }}</div>
          </div>
          <button
            class="toaster-close"
            aria-label="Zavřít"
            @click.stop="remove(toast.id)"
          >
            ×
          </button>
          <div
            class="toaster-progress"
            :style="{ width: toast.progress + '%' }"
          ></div>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '@/Composables/useToast'

const { toasts, remove } = useToast()

const getIcon = (type) => {
  const icons = {
    success: '✓',
    warning: '⚠',
    danger: '✕',
    info: 'ℹ',
    primary: '★'
  }
  return icons[type] || '•'
}
</script>

<style scoped>
.toaster-container {
  position: fixed;
  bottom: 30px;
  right: 30px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 12px;
  pointer-events: none;
}

.toaster-notification {
  position: relative;
  width: fit-content;
  min-width: 320px;
  max-width: 450px;
  padding: 16px 20px;
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  pointer-events: auto;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-family: "IBM Plex Sans", system-ui, -apple-system, sans-serif;
  transition: all 0.3s ease;
  cursor: pointer;
}

.toaster-notification:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.toaster-notification.success {
  background: linear-gradient(135deg, #00CC61, #00b854);
  color: #FFFFFF;
}

.toaster-notification.warning {
  background: linear-gradient(135deg, #FFCA0A, #ffb700);
  color: #000000;
}

.toaster-notification.danger {
  background: linear-gradient(135deg, #FF477B, #e63946);
  color: #FFFFFF;
}

.toaster-notification.info {
  background: linear-gradient(135deg, #00b2da, #0099cc);
  color: #FFFFFF;
}

.toaster-notification.primary {
  background: linear-gradient(135deg, #040303, #1a1a1a);
  color: #FFFFFF;
}

.toaster-icon {
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: bold;
}

.toaster-notification.success .toaster-icon {
  background: rgba(255, 255, 255, 0.2);
  color: #FFFFFF;
}

.toaster-notification.warning .toaster-icon {
  background: rgba(0, 0, 0, 0.1);
  color: #000000;
}

.toaster-notification.danger .toaster-icon {
  background: rgba(255, 255, 255, 0.2);
  color: #FFFFFF;
}

.toaster-notification.info .toaster-icon {
  background: rgba(255, 255, 255, 0.2);
  color: #FFFFFF;
}

.toaster-notification.primary .toaster-icon {
  background: rgba(255, 255, 255, 0.2);
  color: #FFFFFF;
}

.toaster-content {
  flex: 1;
  min-width: 0;
}

.toaster-title {
  font-size: 16px;
  font-weight: 600;
  line-height: 1.3;
  margin: 0 0 4px 0;
}

.toaster-message {
  font-size: 14px;
  line-height: 1.4;
  margin: 0;
  opacity: 0.9;
}

.toaster-close {
  flex-shrink: 0;
  min-width: 20px;
  min-height: 20px;
  max-width: 20px;
  max-height: 20px;
  border: none;
  background: rgba(255, 255, 255, 0.2);
  color: inherit;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
  transition: all 0.2s ease;
  opacity: 0.7;
}

.toaster-close:hover {
  opacity: 1;
  background: rgba(255, 255, 255, 0.3);
  transform: scale(1.1);
}

.toaster-notification.warning .toaster-close {
  background: rgba(0, 0, 0, 0.1);
  color: #000000;
}

.toaster-notification.warning .toaster-close:hover {
  background: rgba(0, 0, 0, 0.2);
}

.toaster-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 0 0 12px 12px;
  transition: width linear;
}

.toaster-notification.warning .toaster-progress {
  background: rgba(0, 0, 0, 0.2);
}

/* Toast transitions */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}

.toast-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}

.toast-move {
  transition: transform 0.3s ease;
}

@media (max-width: 768px) {
  .toaster-container {
    bottom: 20px;
    left: 20px;
    right: 20px;
    transform: none;
  }

  .toaster-notification {
    min-width: auto;
    max-width: none;
    width: 100%;
  }
}
</style>

