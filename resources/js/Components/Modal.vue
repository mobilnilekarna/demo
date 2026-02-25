<template>
  <Teleport to="body">
    <!-- Overlay -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="modelValue"
        class="fixed inset-0 bg-black/50 z-40"
        @click="close"
      ></div>
    </Transition>

    <!-- Modal box s animací -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-8 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 translate-y-8 scale-95"
    >
      <div
        v-if="modelValue"
        class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none p-4"
      >
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 relative pointer-events-auto max-h-[90vh] overflow-y-auto">
          <button
            v-if="closable"
            @click="close"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
          >
            ✕
          </button>

          <h2 v-if="title" class="text-xl font-semibold mb-4">{{ title }}</h2>

          <div class="mb-6">
            <slot />
          </div>

          <div v-if="$slots.footer" class="flex justify-end gap-2">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
const props = defineProps({
  modelValue: { type: Boolean, required: true },
  title: { type: String, default: "" },
  closable: { type: Boolean, default: true }
})

const emit = defineEmits(["update:modelValue"])
const close = () => emit("update:modelValue", false)
</script>
