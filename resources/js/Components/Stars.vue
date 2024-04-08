<script setup>
import { StarIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    stars: Number,
});
const remainder = props.stars % 1;
const fractionToShow = Math.round(remainder/10*12*10);
</script>

<template>
    <div class="flex">
        <template v-for="count in 5">
            <div class="h-6 w-6">
                <div class="overflow-hidden" :class="{
                    'w-1/12' : fractionToShow == 1 & stars - remainder + 1  == count,
                    'w-2/12' : fractionToShow == 2 & stars - remainder + 1  == count,
                    'w-3/12' : fractionToShow == 3 & stars - remainder + 1  == count,
                    'w-4/12' : fractionToShow == 4 & stars - remainder + 1  == count,
                    'w-5/12' : fractionToShow == 5 & stars - remainder + 1  == count,
                    'w-6/12' : fractionToShow == 6 & stars - remainder + 1  == count,
                    'w-7/12' : fractionToShow == 7 & stars - remainder + 1  == count,
                    'w-8/12' : fractionToShow == 8 & stars - remainder + 1  == count,
                    'w-9/12' : fractionToShow == 9 & stars - remainder + 1  == count,
                    'w-10/12' : fractionToShow == 10 & stars - remainder + 1  == count,
                    'w-11/12' : fractionToShow == 11 & stars - remainder + 1  == count,
                    'w-12/12' : fractionToShow == 12 & stars - remainder + 1  == count,
                }">
                    <StarIcon
                        class="h-6 w-6"
                        :class="{
                            'fill-yellow-400': stars >= count || stars - remainder + 1  == count,
                            'hidden': stars + 1  <= count
                        }"
                    />
                </div>
            </div>
        </template>
    </div>
</template>
