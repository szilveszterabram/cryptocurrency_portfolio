<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});
</script>

<template>
    <Head title="Create invite" />

    <div
        class="mt-4 flex min-h-screen w-full flex-col items-center justify-start space-y-2"
    >
        <TextInput
            name="Email"
            v-model="form.email"
            :error-message="form.errors.email"
            placeholder="example@email.com"
        />
        <button
            class="rounded bg-gray-200 p-2 hover:bg-gray-100"
            @click="form.post(route('invite.invite'))"
        >
            Send invite!
        </button>
    </div>
</template>
