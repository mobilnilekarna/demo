<template>
  <DashboardLayout>
    <div class="p-4 relative">
      <h1>Správa zákazníckých zpráv</h1>
    </div>
  </DashboardLayout>
</template>

<script setup>

const props = defineProps({
  tickets: {
    type: Array,
    default: () => []
  }
})

</script>

<style scoped>

</style>
