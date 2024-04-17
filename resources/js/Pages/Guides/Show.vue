<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Container from '@/Components/Container.vue';
import Review from '@/Components/Review.vue';
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import InputError from "@/Components/InputError.vue";
import StarInput from '@/Components/StarInput.vue';
import Stars from '@/Components/Stars.vue';

const props = defineProps({
    guide: Object,
    episodes: Object,
});
</script>

<template>
    <AppLayout>
        <Container>
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ guide.name }}</h1>
                <div class="flex gap-x-3">
                    <p>{{ guide.rating }}</p>
                </div>
            </div>

            <article class="mt-6 prose prose-sm max-w-none" v-html="guide.html"></article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Episodes</h2>


                <ul class="divide-y mt-4">
                    <li v-for="episode in episodes.data" :key="episode.id" class="px-2 py-4">
                        {{ episode.guide }}
                    </li>
                </ul>

                <Pagination :meta="episodes.meta" :only="['episodes']"/>
            </div>
        </Container>
    </AppLayout>
</template>
