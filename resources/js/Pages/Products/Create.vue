<script setup>
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import Container from '@/Components/Container.vue';

const form = useForm({
    name: '',
    summary: '',
    body: '',
    logo: '',
});

const createPost = () => form.post(route('products.store'));
</script>

<template>
    <AppLayout>
        <Container>
            <h1 class="font-bold text-2xl">Add a Product</h1>
            <form @submit.prevent="createPost" class="mt-6">
                <div>
                    <InputLabel for="name">Name</InputLabel>
                    <TextInput
                        id="name"
                        class="w-full"
                        v-model="form.name"
                        placeholder="Give it a great name…"
                    />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="summary">Summary</InputLabel>
                    <TextInput
                        id="summary"
                        class="w-full"
                        v-model="form.summary"
                        placeholder="Give it a great summary…"
                    />
                    <InputError :message="form.errors.summary" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="logo">Logo</InputLabel>
                    <input
                        type="file"
                        id="logo"
                        class="w-full"
                        @input="form.logo = $event.target.files[0]"
                    ></input>
                    <InputError :message="form.errors.logo" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="description">Description</InputLabel>
                    <MarkdownEditor v-model="form.body"></MarkdownEditor>
                    <InputError :message="form.errors.body" class="mt-1" />
                </div>

                <div class="mt-3">
                    <PrimaryButton type="submit">Add Product</PrimaryButton>
                </div>
            </form>
        </Container>
    </AppLayout>
</template>
