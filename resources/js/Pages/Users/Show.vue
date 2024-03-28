<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    user: Object,
});
</script>

<template>
    <GuestLayout>
        <div>{{ user }}</div>
    </GuestLayout>
</template>
