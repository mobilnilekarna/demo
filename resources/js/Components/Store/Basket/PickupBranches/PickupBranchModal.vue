<template>
  <Teleport to="body">
    <div
      v-if="modelValue"
      class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex items-center justify-between p-4 border-b">
          <h2 class="text-xl font-semibold">{{ modalTitle }}</h2>
          <button
            @click="closeModal"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            ✕
          </button>
        </div>
        <div class="flex-1 overflow-hidden">
          <!-- PPL Parcel -->
          <div v-if="branchType === 'ppl-parcel'" class="h-full p-4" style="min-height: 600px;">
            <div v-if="loading" class="flex items-center justify-center h-full">
              <div class="text-gray-500">Načítání mapy...</div>
            </div>
            <div id="ppl-parcelshop-map" ref="pplContainer" style="min-height: 600px;"></div>
          </div>

          <!-- DPD Pickup -->
          <iframe
            v-else-if="branchType === 'dpd-pickup'"
            id="dpd-pickup-map"
            ref="dpdIframe"
            class="w-full h-full border-0"
            :style="{ minHeight: '600px' }"
            :src="iframeSrc"
          ></iframe>

          <!-- Česká pošta - Pobočka -->
          <iframe
            v-else-if="branchType === 'cp-np'"
            id="cp-np-map"
            ref="cpNpIframe"
            class="w-full h-full border-0"
            :style="{ minHeight: '600px' }"
            :src="iframeSrc"
          ></iframe>

          <!-- Česká pošta - Balíkovna -->
          <iframe
            v-else-if="branchType === 'cp-ba'"
            id="cp-ba-map"
            ref="cpBaIframe"
            class="w-full h-full border-0"
            :style="{ minHeight: '600px' }"
            :src="iframeSrc"
          ></iframe>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  branchType: {
    type: String,
    default: null
  },
  packetaSettings: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update:modelValue', 'branch-selected']);

const modalTitle = computed(() => {
  const titles = {
    'ppl-parcel': 'Vyberte výdejní místo PPL Parcel',
    'dpd-pickup': 'Vyberte výdejní místo DPD',
    'cp-np': 'Vyberte pobočku České pošty',
    'cp-ba': 'Vyberte balíkovnu České pošty'
  };
  return titles[props.branchType] || 'Vyberte výdejní místo';
});

// Refs pro iframe a container
const pplContainer = ref(null);
const dpdIframe = ref(null);
const cpNpIframe = ref(null);
const cpBaIframe = ref(null);
const iframeSrc = ref('');
const loading = ref(false);

// PPL resources
let pplLinkLoaded = false;
let pplScriptLoaded = false;
let pplScriptElement = null;

const closeModal = () => {
  emit('update:modelValue', false);
  // Reset src při zavření
  iframeSrc.value = '';
};

// PPL resource loading
const loadPplResources = () => {
  const mapElement = document.getElementById('ppl-parcelshop-map');
  if (!mapElement) {
    console.error('PPL map element not found in DOM');
    return;
  }

  // Načíst CSS
  if (!pplLinkLoaded) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://www.ppl.cz/sources/map/main.css';
    document.head.appendChild(link);
    pplLinkLoaded = true;
  }

  // Načíst JS
  if (!pplScriptLoaded && !pplScriptElement) {
    loading.value = true;

    pplScriptElement = document.createElement('script');
    pplScriptElement.src = 'https://www.ppl.cz/sources/map/main.js';
    pplScriptElement.async = true;

    pplScriptElement.onload = () => {
      pplScriptLoaded = true;
      loading.value = false;
    };

    pplScriptElement.onerror = () => {
      loading.value = false;
      console.error('Failed to load PPL widget script');
    };

    document.head.appendChild(pplScriptElement);
  } else if (pplScriptLoaded) {
    loading.value = false;
  }
};

// Message handlers
const handlePplEvent = (event) => {
  try {
    if (event && event.detail) {
      emit('branch-selected', {
        branch_id: event.detail.code,
        branch_name: event.detail.name
      });
      closeModal();
    }
  } catch (e) {
    console.error('Error handling PPL event:', e);
  }
};

const handleMessage = (event) => {
  try {
    // DPD
    if (event.data && event.data.dpdWidget) {
      emit('branch-selected', {
        branch_id: event.data.dpdWidget.id,
        branch_name: event.data.dpdWidget.pickupPointResult
      });
      closeModal();
      return;
    }

    // Česká pošta - Pobočka
    if (event.data && event.data.point && event.data.point.type === 'POST_OFFICE') {
      emit('branch-selected', {
        branch_id: event.data.point.zip,
        branch_name: event.data.point.name + ' | ' + event.data.point.address
      });
      closeModal();
      return;
    }

    // Česká pošta - Balíkovna
    if (event.data && event.data.point && event.data.point.type === 'BALIKOVNY') {
      emit('branch-selected', {
        branch_id: event.data.point.zip,
        branch_name: event.data.point.name + ' | ' + event.data.point.address
      });
      closeModal();
      return;
    }
  } catch (e) {
    // Ignorovat chyby
  }
};

// Watch pro změnu typu a otevření modalu
watch(() => props.modelValue, async (isOpen) => {
  if (isOpen && props.branchType) {
    await nextTick();

    if (props.branchType === 'ppl-parcel') {
      loadPplResources();
    } else if (props.branchType === 'dpd-pickup') {
      iframeSrc.value = 'https://api.dpd.cz/widget/latest/index.html';
    } else if (props.branchType === 'cp-np') {
      iframeSrc.value = 'https://b2c.cpost.cz/locations/?type=POST_OFFICE&phone=FALSE&skiplocation=FALSE';
    } else if (props.branchType === 'cp-ba') {
      iframeSrc.value = 'https://b2c.cpost.cz/locations/?type=BALIKOVNY&phone=FALSE&skiplocation=FALSE';
    }
  } else if (!isOpen) {
    // Reset při zavření
    iframeSrc.value = '';
  }
});

onMounted(() => {
  document.addEventListener('ppl-parcelshop-map', handlePplEvent);
  window.addEventListener('message', handleMessage);
});

onUnmounted(() => {
  document.removeEventListener('ppl-parcelshop-map', handlePplEvent);
  window.removeEventListener('message', handleMessage);
});
</script>
