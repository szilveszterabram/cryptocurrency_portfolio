<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const properties = defineProps({ portfolios: Array });
</script>

<template>
    <Head title="Portfolios" />
    <div class="flex items-center text-lg">
        <Plus
            :size="34"
            class="mx-2 rounded bg-black text-white hover:cursor-pointer hover:bg-red-500"
            @click="router.get(route('portfolio.create'))"
        />
        Add a portfolio
    </div>

    <div
        class="flex h-full w-full justify-center"
        v-if="portfolios?.length == 0"
    >
        <div class="items-center self-center rounded bg-gray-200 p-4 text-lg">
            <p>
                Looks like you don't have any portfolios yet. Want to
                <span
                    class="text-blue-400 hover:cursor-pointer"
                    @click="router.get(route('portfolio.create'))"
                    >create one</span
                >?
            </p>
        </div>
    </div>
</template>
