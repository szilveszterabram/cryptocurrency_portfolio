<script setup lang="ts">
import Notification from '@/Components/Notification.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const buy_form = useForm({
    buy_price: 0,
});

const properties = defineProps<{
    asset: {
        asset_id: string;
        name: string;
        price_usd: number;
    };
    icon_url: string;
    portfolios: Array<{
        id: number;
        name: string;
    }>;
    balance: number;
}>();

const form = useForm({
    portfolio_id: -1,
    asset_short: properties.asset.asset_id,
    asset_long: properties.asset.name,
    price_at_buy: properties.asset.price_usd,
    amount: 0,
});

const dropdownIsVisible = ref(false);
const selectedPortfolioName = ref('Select a portfolio');

watch(
    () => buy_form.buy_price,
    (newBuyPrice) => {
        form.amount = newBuyPrice / properties.asset.price_usd;
    },
);

watch(
    () => form.amount,
    (newAmount) => {
        buy_form.buy_price = newAmount * properties.asset.price_usd;
    },
);

const toggleDropdown = () => {
    dropdownIsVisible.value = !dropdownIsVisible.value;
};

const selectPortfolio = (portfolio: { id: number; name: string }) => {
    selectedPortfolioName.value = portfolio.name;
    form.portfolio_id = portfolio.id;
    dropdownIsVisible.value = false;
};

const buy = () => {
    form.post(route('entry.store'));
};
</script>

<template>
    <Head :title="'Buy ' + asset.asset_id" />

    <Notification
        v-if="$page.props.errors"
        type="error"
        :message="Object.values($page.props.errors)[0]"
    />

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
            />{{ asset.asset_id }} | {{ asset.name }}
            <p class="flex-row text-lg"></p>
        </div>

        <div
            class="mt-2 flex h-full w-full flex-col items-start rounded border-2 border-gray-50 bg-white p-6 shadow-sm"
        >
            <h2 class="mb-4">Available balance: ${{ balance }}</h2>

            <p class="mb-4 text-lg">
                Price of 1 {{ asset.name }}: ${{ asset.price_usd }}
            </p>
            <p class="mb-4 text-lg">
                Price of 1 USD: {{ asset.asset_id }}{{ 1 / asset.price_usd }}
            </p>

            <TextInput
                name="USD amount"
                :model-value="buy_form.buy_price"
                type="number"
                :error-message="form.errors.amount"
                @update:model-value="(value) => (buy_form.buy_price = value)"
                :positive-only="true"
            /><TextInput
                :name="asset.asset_id + ' amount'"
                :model-value="form.amount"
                type="number"
                :error-message="form.errors.amount"
                @update:model-value="(value) => (form.amount = value)"
                :positive-only="true"
            />

            <button
                @click="toggleDropdown"
                id="dropdownDefaultButton"
                class="inline-flex items-center rounded-lg bg-black px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-4"
                type="button"
            >
                <span>{{ selectedPortfolioName }}</span>
                <svg
                    class="ms-3 h-2.5 w-2.5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 10 6"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 1 4 4 4-4"
                    />
                </svg>
            </button>

            <div
                id="dropdown"
                :class="{
                    hidden: !dropdownIsVisible,
                    block: dropdownIsVisible,
                }"
                class="z-10 w-44 divide-y divide-gray-100 rounded-lg bg-white shadow"
            >
                <ul
                    class="py-2 text-sm text-gray-700"
                    aria-labelledby="dropdownDefaultButton"
                >
                    <li
                        v-for="portfolio in properties.portfolios"
                        :key="portfolio.id"
                    >
                        <a
                            @click.prevent="selectPortfolio(portfolio)"
                            class="block px-4 py-2 hover:bg-gray-100"
                            >{{ portfolio.name }}</a
                        >
                    </li>
                </ul>
            </div>
            <button
                class="flex place-self-end self-center rounded bg-black p-2 text-lg text-white hover:bg-gray-800 disabled:bg-gray-200"
                @click="buy"
                :disabled="
                    form.processing || !form.isDirty || form.portfolio_id == -1
                "
            >
                Purchase
            </button>
        </div>
    </div>
</template>
