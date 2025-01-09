<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

defineProps<{
    current_balance: number;
}>();

const form = useForm({
    balance: 0,
});

const submit = () => {
    form.put(route('profile.update-balance'));
    form.reset();
};
</script>

<template>
    <div class="margin-3 padding-2 w-full">
        <h2 class="mb-2">Balance: ${{ current_balance }}</h2>
        <TextInput
            name="Add funds"
            v-model="form.balance"
            type="number"
            :error-message="form.errors.balance"
            placeholder="0.0"
            :positive-only="true"
        />
        <button
            class="flex place-self-start self-center rounded bg-black p-2 text-lg text-white hover:bg-gray-800 disabled:bg-gray-200"
            @click="submit"
            :disabled="form.processing"
        >
            Add to balance!
        </button>
    </div>
</template>
