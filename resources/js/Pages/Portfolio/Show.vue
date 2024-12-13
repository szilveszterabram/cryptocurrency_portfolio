<script setup lang="ts">
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
        created_at: string;
    }[];
    icons: {
        id: string;
        url: string;
    }[];
}>();

const entriesWithIcons = computed(() =>
    properties.entries.map((entry) => {
        const icon = properties.icons.find(
            (icon) => icon.id === entry.asset_short,
        );
        return {
            ...entry,
            iconUrl: icon ? icon.url : null,
        };
    }),
);
</script>

<template>
    <Head :title="portfolio.name" />

    <div class="flex h-full w-full items-start justify-center">
        <table class="mt-14" v-if="entries.length != 0">
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
                    <td>Date of acquisition</td>
                </tr>
                <tr v-for="entry in entriesWithIcons" :key="entry.id">
                    <td>
                        <img
                            v-if="entry.iconUrl"
                            :src="entry.iconUrl"
                            alt="Icon"
                            width="30"
                            height="30"
                            class="mr-2 inline-block"
                        /><span>{{ entry.asset_short }}</span>
                    </td>
                    <td>{{ entry.asset_long }}</td>
                    <td>{{ entry.amount }}</td>
                    <td>
                        {{
                            moment(entry.created_at).format(
                                'MMMM DD, YYYY h:mm A',
                            )
                        }}
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
