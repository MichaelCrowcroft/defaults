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
import { useConfirm } from '@/Utilities/Composables/useConfirm.js';
import { router, useForm } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import StarInput from '@/Components/StarInput.vue';
import Stars from '@/Components/Stars.vue';

const props = defineProps({
    product: Object,
    reviews: Object,
});

const reviewForm = useForm({
    body: '',
    stars: null,
});

const { confirmation } = useConfirm();

const reviewEditorRef = ref(null);
const reviewIdBeingEdited = ref(null);
const reviewBeingEdited = computed(() => props.reviews.data.find(
    review => review.id === reviewIdBeingEdited.value)
);
const editReview = (reviewId) => {
    reviewIdBeingEdited.value = reviewId;
    reviewForm.body = reviewBeingEdited.value?.body;
    reviewForm.stars = reviewBeingEdited.value?.stars;
    reviewEditorRef.value?.focus();
};

const cancelEditReview = () => {
    reviewIdBeingEdited.value = null;
    reviewForm.reset();
};

const addReview = () => reviewForm.post(route('products.reviews.store', props.product.id), {
    preserveScroll: true,
    onSuccess: () => reviewForm.reset(),
});

const updatedReview = async () => {
    if (! await confirmation('Are you sure you want to update this review?')) {
        reviewEditorRef.value?.focus();
        return;
    }

    reviewForm.patch(route('reviews.update', {
        review: reviewIdBeingEdited.value,
        page: props.reviews.meta.current_page,
    }), {
        preserveScroll: true,
        onSuccess: cancelEditReview,
    });
};

const deleteReview = async (reviewId) => {
    if (! await confirmation('Are you sure you want to delete this review?')) {
        return;
    }

    router.delete(route('reviews.delete', {
        review: reviewId,
        page: props.reviews.meta.current_page,
    }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout>
        <Container>
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ product.name }}</h1>
                <div class="flex gap-x-3">
                    <Stars :stars="product.rating" />
                    <p>{{ product.rating }}</p>
                </div>
            </div>

            <article class="mt-6 prose prose-sm max-w-none" v-html="product.html"></article>

            <div class="mt-12">
                <h2 class="text-xl font-semibold">Reviews</h2>

                <form v-if="$page.props.auth.user" @submit.prevent="() => reviewIdBeingEdited ? updatedReview() : addReview()" class="mt-4">
                    <div>
                        <InputLabel for="stars" class="sr-only">Stars</InputLabel>
                        <StarInput v-model="reviewForm.stars" />
                        <InputError :message="reviewForm.errors.stars" class="mt-1" />
                    </div>

                    <div class="mt-3">
                        <InputLabel for="body" class="sr-only">Review</InputLabel>
                        <MarkdownEditor ref="reviewEditorRef" v-model="reviewForm.body" editorClass="min-h-[180px]" placeholder="Leave a review..." />
                        <InputError :message="reviewForm.errors.body" class="mt-1" />
                    </div>

                    <PrimaryButton type="submit" class="mt-3" :disabled="reviewForm.processing"
                        v-text="reviewIdBeingEdited ? 'Update Review' : 'Add Review'">
                    </PrimaryButton>
                    <SecondaryButton v-if="reviewIdBeingEdited" @click="cancelEditReview" class="ml-2">Cancel</SecondaryButton>
                </form>

                <ul class="divide-y mt-4">
                    <li v-for="review in reviews.data" :key="review.id" class="px-2 py-4">
                        <Review @edit="editReview" @delete="deleteReview"  :review="review"/>
                    </li>
                </ul>

                <Pagination :meta="reviews.meta" :only="['reviews']"/>
            </div>
        </Container>
    </AppLayout>
</template>
