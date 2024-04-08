<script setup>
import { StarIcon } from '@heroicons/vue/24/outline'
import { ref, watch } from 'vue';

const model = defineModel({
    type: Number,
    required: true,
});

const rating = ref(0);

watch(() => model.value, (value) => rating.value = value);
// TODO make functional with screen readers and keyboards
</script>

<template>
    <div class="flex">
        <template v-for="count in 5">
            <StarIcon
                class="h-8 w-8" :class="{ 'fill-yellow-400': model >= count || rating >= count }"
                @mouseover="rating = count"
                @mouseleave="rating = model"
                @click="model = count"
            />
        </template>
    </div>
</template>
