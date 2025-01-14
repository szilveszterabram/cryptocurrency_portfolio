<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const searchValue = ref('');

const properties = defineProps<{
    assets: {
        data: {
            asset_id: string;
            name: string;
            price_usd?: number;
            icon_url: string;
        }[];
        links: {
            active: boolean;
            label: string;
            url?: string;
        };
        current_page: number;
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        next_page_url?: string;
        path: string;
        per_page: number;
        prev_page_url?: string;
        to: number;
        total: number;
    };
    roles: string[];
}>();

watch(searchValue, (newValue) => {
    router.get(route('assets'), { search: newValue }, { preserveState: true });
});

const isUser = ref(properties.roles.includes('user'));
</script>

<template>
    <div class="flex min-h-screen w-full flex-col">
        <TextInput
            name="search"
            type="text"
            :model-value="searchValue"
            placeholder="Type an asset in short form (Ex.: BTC)"
            @input="searchValue = $event.target.value"
        />
        <table>
            <tbody>
                <tr
                    class="bg-black text-lg text-white hover:bg-black hover:text-white"
                >
                    <td>Icon</td>
                    <td>Id</td>
                    <td>Name</td>
                    <td>Price USD</td>
                    <td v-if="isUser">Buy</td>
                    <td v-if="isUser">Watch</td>
                </tr>
                <tr v-for="asset in assets.data" :key="asset.asset_id">
                    <td>
                        <img
                            v-if="asset.icon_url"
                            :src="asset.icon_url"
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
                    <td v-if="isUser">
                        <Link
                            class="rounded bg-gray-100 p-2 hover:bg-black hover:text-white"
                            v-if="asset.price_usd != null"
                            :href="
                                route('entry.create', {
                                    assetId: asset.asset_id,
                                })
                            "
                            prefetch
                        >
                            Buy
                        </Link>
                    </td>
                    <td v-if="isUser">
                        <Link
                            class="rounded bg-gray-100 p-2 hover:bg-black hover:text-white"
                            v-if="asset.price_usd != null"
                            :href="
                                route('observation.create', {
                                    asset: asset.asset_id,
                                })
                            "
                            prefetch
                        >
                            Notify me
                        </Link>
                    </td>
                </tr>
            </tbody>
            <tfoot></tfoot>
        </table>

        <div class="pagination-bar mb-4 flex items-center justify-center">
            <button
                class="mx-2 rounded bg-black p-2 text-lg text-white disabled:bg-gray-100 disabled:text-gray-500"
                :disabled="assets.prev_page_url == null"
                @click="
                    router.get(assets.prev_page_url!, { search: searchValue })
                "
            >
                Previous
            </button>

            <button
                class="mx-2 rounded bg-black p-2 text-lg text-white disabled:bg-gray-100 disabled:text-gray-500"
                :disabled="assets.next_page_url == null"
                @click="
                    router.get(assets.next_page_url!, { search: searchValue })
                "
            >
                Next
            </button>
        </div>
    </div>

    <Head title="Assets" />
</template>
