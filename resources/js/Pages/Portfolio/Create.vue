<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: null,
});

const submit = () => {
    form.post(route('portfolio.store'));
};
</script>

<template>
    <Head title="Create a portfolio" />
    <div class="mt-14 flex h-screen items-start justify-center">
        <form @submit.prevent="submit" class="w-full max-w-sm space-y-4">
            <div>
                <TextInput
                    name="Add a name to your New Portfolio"
                    v-model="form.name"
                    :error-message="form.errors.name"
                    type="text"
                />
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full rounded-md bg-black p-2 text-lg text-white hover:bg-red-500"
                    :disabled="form.processing"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>
