<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const properties = defineProps<{
    portfolio: {
        id: number;
        user_id: number;
        name: string;
    };
}>();

const form = useForm({
    name: properties.portfolio.name,
});

const submit = () => {
    form.patch(
        route('portfolio.update', {
            portfolio: properties.portfolio.id,
        }),
    );
};
</script>

<template>
    <Head :title="'Edit ' + portfolio.name" />

    <div class="mt-14 flex h-screen items-start justify-center">
        <form @submit.prevent="submit" class="w-full max-w-sm space-y-4">
            <div>
                <TextInput
                    :name="'Change the name to ' + portfolio.name"
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
