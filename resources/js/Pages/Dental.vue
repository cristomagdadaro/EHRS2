<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dental">
        <template #toolbar>
            Dental Section
        </template>
        <slot></slot>
    </AppLayout>
</template>
