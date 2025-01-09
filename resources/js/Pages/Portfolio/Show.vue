<script setup lang="ts">
import Notification from '@/Components/Notification.vue';
import { router } from '@inertiajs/vue3';
import { CircleDollarSign, HandCoins } from 'lucide-vue-next';
import moment from 'moment';
import { computed } from 'vue';

const properties = defineProps<{
    portfolio: {
        id: number;
        name: string;
    };
    entries: {
        id: number;
        portfolio_id: number;
        asset_short: string;
        asset_long: string;
        amount: number;
        price_at_buy: number;
        created_at: string;
    }[];
    data: {
        asset_id: string;
        name: string;
        price_usd: number;
        icon_url: string;
    }[];
}>();

const entriesWithIcons = computed(() => {
    return properties.entries.map((entry) => {
        const assetData = properties.data.find(
            (asset) => asset.asset_id === entry.asset_short,
        );
        const currentPrice = assetData ? assetData.price_usd : null;
        const percentageChange = currentPrice
            ? ((currentPrice - entry.price_at_buy) / entry.price_at_buy) * 100
            : null;

        return {
            ...entry,
            currentPrice,
            percentageChange,
            assetData,
        };
    });
});
</script>

<template>
    <Head :title="portfolio.name" />

    <Notification
        v-if="$page.props.success"
        type="success"
        :message="$page.props.success as string"
    />

    <div class="flex h-full w-full items-start justify-center">
        <table class="mt-14 w-full" v-if="entries.length != 0">
            <thead>
                {{
                    portfolio.name
                }}
            </thead>
            <tbody>
                <tr
                    class="bg-black text-lg text-white hover:bg-black hover:text-white"
                >
                    <td>Id</td>
                    <td>Name</td>
                    <td>Amount held</td>
                    <td>Price at acquisition</td>
                    <td>Current price</td>
                    <td>Change</td>
                    <td>Date of acquisition</td>
                    <td>Buy more</td>
                    <td>Sell</td>
                </tr>
                <tr v-for="entry in entriesWithIcons" :key="entry.id">
                    <td>
                        <img
                            v-if="entry.assetData!.icon_url"
                            :src="entry.assetData!.icon_url"
                            alt="Icon"
                            width="30"
                            height="30"
                            class="mr-2 inline-block"
                        /><span>{{ entry.asset_short }}</span>
                    </td>
                    <td>{{ entry.asset_long }}</td>
                    <td>{{ entry.amount }}</td>
                    <td>${{ entry.price_at_buy }}</td>
                    <td>${{ entry.currentPrice }}</td>
                    <td
                        :class="
                            entry.percentageChange! < 0
                                ? 'text-red-500'
                                : 'text-green-500'
                        "
                    >
                        {{ entry.percentageChange!.toFixed(5) }}%
                    </td>
                    <td>
                        {{
                            moment(entry.created_at).format(
                                'MMMM DD, YYYY h:mm A',
                            )
                        }}
                    </td>
                    <td>
                        <HandCoins
                            class="rounded bg-black p-2 text-white hover:cursor-pointer hover:bg-gray-600"
                            :size="50"
                            @click="
                                router.get(
                                    route('entry.create', {
                                        assetId: entry.asset_short,
                                    }),
                                )
                            "
                        />
                    </td>
                    <td>
                        <CircleDollarSign
                            :class="
                                (entry.percentageChange! < 0
                                    ? 'bg-red-500 hover:bg-red-400'
                                    : 'bg-green-500 hover:bg-green-400') +
                                ' rounded p-2 text-white hover:cursor-pointer'
                            "
                            :size="50"
                            @click="
                                router.delete(
                                    route('entry.destroy', {
                                        entry: entry.id,
                                    }),
                                    {
                                        onSuccess: () => {
                                            router.flushAll();
                                        },
                                    },
                                )
                            "
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        <div
            v-else
            class="flex items-start justify-center rounded bg-black p-4"
        >
            <p class="text-lg text-white">
                Looks like you don't have any active holdings in
                {{ portfolio.name }}
            </p>
        </div>
    </div>
</template>
