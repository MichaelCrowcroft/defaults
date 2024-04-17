<script setup>
import Pagination from '@/Components/Pagination.vue';
import Container from '@/Components/Container.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import Stars from '@/Components/Stars.vue';

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
                        <div class="flex gap-x-3 items-center">
                            <img :src="product.logo_path" class="w-8 h-8" />
                            <h2 class="font-bold text-lg group-hover:text-indigo-500">{{ product.name }}</h2>
                            <Stars :stars="product.rating" />
                        </div>
                        <p class="mt-3">{{ product.summary }}</p>
                    </Link>
                </li>
            </ul>

            <Pagination :meta="products.meta" :only="['products']" class="mt-2"/>
        </Container>
    </AppLayout>
</template>
