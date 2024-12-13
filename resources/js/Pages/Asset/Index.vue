<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const properties = defineProps({
    assets: Array<{
        asset_id: string;
        name: string;
        price_usd: number | null;
    }>,
    total_pages: Number,
    page: Number,
    icons: Array<{
        asset_id: string;
        url: string;
    }>,
});

const assetsWithIcons = computed(() => {
    return properties.assets!.map((asset) => {
        const icon = properties.icons!.find(
            (icon) => icon.asset_id === asset.asset_id,
        );
        return {
            ...asset,
            iconUrl: icon ? icon.url : null,
        };
    });
});
</script>

<template>
    <div class="flex h-full w-full flex-col">
        <table>
            <tbody>
                <tr
                    class="bg-black text-lg text-white hover:bg-black hover:text-white"
                >
                    <td>Icon</td>
                    <td>Id</td>
                    <td>Name</td>
                    <td>Price USD</td>
                    <td>Buy</td>
                </tr>
                <tr v-for="asset in assetsWithIcons" :key="asset.asset_id">
                    <td>
                        <img
                            v-if="asset.iconUrl"
                            :src="asset.iconUrl"
                            alt="Icon"
                            width="30"
                            height="30"
                        />
                        <span v-else></span>
                    </td>
                    <td>{{ asset.asset_id }}</td>
                    <td>{{ asset.name }}</td>
                    <td v-if="asset.price_usd">${{ asset.price_usd }}</td>
                    <td v-else>No price data</td>
                    <td>
                        <button
                            class="rounded bg-gray-100 p-2 hover:bg-black hover:text-white"
                            v-if="asset.price_usd != null"
                            @click="
                                router.visit(route('entry.create'), {
                                    method: 'get',
                                    data: {
                                        asset_id: asset.asset_id,
                                    },
                                })
                            "
                        >
                            Buy
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination-bar mb-4 flex items-center justify-center">
            <button
                class="mx-2 rounded bg-black p-2 text-lg text-white disabled:bg-gray-100 disabled:text-gray-500"
                :disabled="page == 1"
                @click="
                    router.get(
                        route('assets.page', { key: (page as number) - 1 }),
                    )
                "
            >
                Previous
            </button>
            <button
                class="ml-2 rounded bg-black p-2 text-lg text-white"
                v-if="page! >= 2"
                @click="router.get(route('assets.page', { key: 1 }))"
            >
                1
            </button>
            <span class="mx-2 text-lg" v-if="page! >= 3"> . . . </span>
            <button class="mx-2 rounded bg-red-500 p-2 text-lg text-white">
                {{ page }}
            </button>
            <span class="mr-2 text-lg" v-if="page! <= total_pages! - 2">
                . . .
            </span>
            <button
                class="mr-2 rounded bg-black p-2 text-lg text-white"
                v-if="page! <= total_pages! - 1"
                @click="
                    router.get(
                        route('assets.page', { key: total_pages as number }),
                    )
                "
            >
                {{ properties.total_pages }}
            </button>

            <button
                class="mx-2 rounded bg-black p-2 text-lg text-white disabled:bg-gray-100 disabled:text-gray-500"
                :disabled="page === properties.total_pages"
                @click="
                    router.get(
                        route('assets.page', {
                            key: parseInt(page! as unknown as string) + 1,
                        }),
                    )
                "
            >
                Next
            </button>
        </div>
    </div>

    <Head title="Assets" />
</template>
