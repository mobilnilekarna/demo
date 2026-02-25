<template>
  <Teleport to="body">
    <!-- Modal se používá externě přes Packeta Widget API -->
    <div v-if="false" id="packeta-widget-container"></div>
  </Teleport>
</template>

<script setup>
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  packetaSettings: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update:modelValue', 'branch-selected']);

let packetaScriptLoaded = false;

const loadPacketaScript = () => {
  if (packetaScriptLoaded || typeof window.Packeta !== 'undefined') {
    return;
  }

  const script = document.createElement('script');
  script.src = 'https://widget.packeta.com/v6/www/js/library.js';
  script.async = true;
  script.onload = () => {
    packetaScriptLoaded = true;
  };
  document.head.appendChild(script);
};

const openPacketaWidget = () => {
  if (typeof window.Packeta === 'undefined' || !window.Packeta.Widget) {
    console.error('Packeta Widget is not loaded');
    return;
  }

  window.Packeta.Widget.pick(
    null,
    (data) => {
      if (data && data.id && data.name) {
        emit('branch-selected', {
          branch_id: data.id,
          branch_name: data.name
        });
        emit('update:modelValue', false);
      }
    },
    props.packetaSettings || {}
  );
};

watch(() => props.modelValue, (isOpen) => {
  if (isOpen) {
    if (!packetaScriptLoaded && typeof window.Packeta === 'undefined') {
      loadPacketaScript();
      // Počkat na načtení scriptu
      const checkInterval = setInterval(() => {
        if (typeof window.Packeta !== 'undefined' && window.Packeta.Widget) {
          clearInterval(checkInterval);
          openPacketaWidget();
        }
      }, 100);
    } else {
      openPacketaWidget();
    }
  }
});

onMounted(() => {
  loadPacketaScript();
});
</script>
