<script setup lang="ts">
const props = defineProps({
    assets: Array,
    total_pages: null,
    page: null,
    icons: Array,
});
</script>

<template>
    <table>
        <tbody>
            <tr
                class="bg-black text-lg text-white hover:bg-black hover:text-white"
            >
                <td>Id</td>
                <td>Name</td>
                <td>Price USD</td>
            </tr>
            <tr v-for="asset in assets">
                <td>{{ asset.asset_id }}</td>
                <td>{{ asset.name }}</td>
                <td>{{ asset.price_usd }}</td>
            </tr>
        </tbody>
    </table>
    <Head title="Assets" />
</template>
