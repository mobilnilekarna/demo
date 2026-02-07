<template>
  <!-- Packeta má vlastní modal přes widget API -->
  <PacketaModal
    v-model="showPacketa"
    :packeta-settings="packetaSettings"
    @branch-selected="handleBranchSelected"
  />

  <!-- Společný modal pro ostatní typy výdejních míst -->
  <PickupBranchModal
    v-model="showBranchModal"
    :branch-type="currentBranchType"
    :packeta-settings="packetaSettings"
    @branch-selected="handleBranchSelected"
  />
</template>

<script setup>
import { ref } from 'vue';
import PacketaModal from './PacketaModal.vue';
import PickupBranchModal from './PickupBranchModal.vue';

const props = defineProps({
  packetaSettings: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['branch-selected']);

const showPacketa = ref(false);
const showBranchModal = ref(false);
const currentBranchType = ref(null);

const openModal = (transportType) => {
  // Reset všech modalů
  showPacketa.value = false;
  showBranchModal.value = false;
  currentBranchType.value = null;

  // Otevřít příslušný modal podle typu dopravy
  switch (transportType) {
    case 'packeta-cz':
    case 'packeta-sk':
      // Packeta má vlastní modal
      showPacketa.value = true;
      break;
    case 'ppl-parcel':
    case 'dpd-pickup':
    case 'cp-np':
    case 'cp-ba':
      // Ostatní typy používají společný modal
      currentBranchType.value = transportType;
      showBranchModal.value = true;
      break;
    case 'personal':
      // Pro personal zatím nic neděláme
      break;
    default:
      console.warn(`Unknown transport type: ${transportType}`);
  }
};

const handleBranchSelected = (data) => {
  emit('branch-selected', data);
};

// Exponovat metodu pro otevření modalů
defineExpose({
  openModal
});
</script>
