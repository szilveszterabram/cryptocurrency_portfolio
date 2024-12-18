<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const properties = defineProps<{
    asset: {
        asset_id: string;
        name: string;
        price_usd: number;
    };
    icon_url: string;
}>();

const form = useForm({
    asset_id: properties.asset.asset_id,
    target: properties.asset.price_usd,
    active: true,
});

const submit = () => {
    form.post(route('observation.store'));
};
</script>

<template>
    <Head :title="'Watch ' + asset.asset_id" />

    <div class="mt-4 flex h-3/4 flex-col items-center justify-start">
        <div
            class="flex items-center justify-start rounded bg-black p-2 text-white shadow"
        >
            <img
                class="mr-2 flex"
                :src="icon_url"
                width="30"
                height="30"
                v-if="icon_url != null"
            />
            <p class="flex-row text-lg">
                {{ asset.asset_id }} | {{ asset.name }} | Price observation
            </p>
        </div>

        <div
            class="mt-2 flex h-full w-full flex-col items-start rounded border-2 border-gray-50 bg-white p-6 shadow-sm"
        >
            <p class="mb-4 text-lg">
                Current price of 1 {{ asset.name }}: ${{ asset.price_usd }}
            </p>

            <TextInput
                :name="'Notify me when ' + asset.asset_id + '`s price reaches'"
                v-model="form.target"
                type="number"
                :error-message="form.errors.target"
                :positive-only="true"
            />

            <button
                class="flex place-self-end self-center rounded bg-black p-2 text-lg text-white hover:bg-gray-800 disabled:bg-gray-200"
                @click="submit"
                :disabled="form.processing"
            >
                Set up notification
            </button>
        </div>
    </div>
</template>
