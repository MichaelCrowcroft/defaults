<script setup>
import Pagination from '@/Components/Pagination.vue';
import Container from '@/Components/Container.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    products: Object,
});
</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="product in products.data" :key="product.id">
                    <Link :href="product.routes.show" class="block group px-2 py-4">
                        <span class="font-bold text-lg group-hover:text-indigo-500">{{ product.name }}</span>
                    </Link>
                </li>
            </ul>

            <Pagination :meta="products.meta" :only="['products']" class="mt-2"/>
        </Container>
    </AppLayout>
</template>
