<script setup lang="ts">
import BalanceCard from '@/Components/BalanceCard.vue';

defineProps<{
    current_balance: number;
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <div class="flex h-full w-full py-12 pb-4">
        <div class="mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <BalanceCard :current_balance="current_balance" />
            </div>
        </div>
    </div>
</template>
