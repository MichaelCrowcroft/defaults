<script setup>
import { relativeDate } from '@/Utilities/date.js';

const props = defineProps({
    review: Object,
});

const emit = defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="review.user.profile_photo_url" class="h-10 w-10 rounded-full" />
        </div>
        <div class="flex-1">
            <p class="mt-1 break-all">{{ review.body }}</p>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{ review.user.handle }} {{ relativeDate(review.created_at) }} ago</span>
            <div class="mt-2 flex justify-end space-x-3 empty:hidden">
                <form v-if="review.can?.update" @submit.prevent="$emit('edit', review.id)">
                    <button class="text-xs hover:underline">Edit</button>
                </form>

                <form v-if="review.can?.delete" @submit.prevent="$emit('delete', review.id)">
                    <button class="text-red-700 text-xs hover:underline">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>