<template>
    <AppLayout title="O nás">
        <div>
            <h1>O nás - toto je testovací aplikace pro tablet</h1>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    message: String,
});
</script>
