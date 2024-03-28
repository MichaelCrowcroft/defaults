<script setup>
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';

const props = defineProps(['topics']);

const form = useForm({
    name: '',
    description: '',
});

const createPost = () => form.post(route('products.store'));
</script>

<template>
    <AppLayout>
        <form @submit.prevent="createPost" class="mt-6">
            <div>
                <InputLabel for="name" class="sr-only">Name</InputLabel>
                <TextInput
                    id="name"
                    class="w-full"
                    v-model="form.name"
                    placeholder="Give it a great name…"
                />
                <InputError :message="form.errors.name" class="mt-1" />
            </div>

            <div class="mt-3">
                <InputLabel for="description" class="sr-only">Description</InputLabel>
                <MarkdownEditor v-model="form.description"></MarkdownEditor>
                <InputError :message="form.errors.description" class="mt-1" />
            </div>

            <div class="mt-3">
                <PrimaryButton type="submit">Create Post</PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
