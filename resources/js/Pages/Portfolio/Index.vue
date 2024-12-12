<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Eye, Plus, Trash2 } from 'lucide-vue-next';

const properties = defineProps({
    portfolios: Array<{ id: number; name: string }>,
});
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

    <table v-if="portfolios?.length != 0" class="mt-5 p-2">
        <tbody>
            <tr
                class="rounded bg-black text-lg text-white hover:bg-black hover:text-white"
            >
                <td>Name</td>
                <td>View</td>
                <td>Delete</td>
            </tr>
            <tr v-for="portfolio in portfolios" :key="portfolio.id">
                <td class="text-lm">
                    {{ portfolio.name }}
                </td>
                <td>
                    <Eye
                        class="rounded bg-gray-100 p-2 hover:cursor-pointer hover:bg-gray-500 hover:text-white"
                        :size="38"
                    />
                </td>
                <td>
                    <Trash2
                        class="rounded bg-gray-100 p-2 hover:cursor-pointer hover:bg-red-500 hover:text-white"
                        :size="38"
                        @click="
                            router.delete(
                                route('portfolio.destroy', {
                                    portfolio: portfolio.id,
                                }),
                            )
                        "
                    />
                </td>
            </tr>
        </tbody>
    </table>

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
