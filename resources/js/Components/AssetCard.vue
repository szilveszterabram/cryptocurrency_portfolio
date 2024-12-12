<script setup lang="ts">
const props = defineProps({
    asset_id: String,
    image_url: String,
    name: String,
    price_usd: null,
});
</script>

<template>
    <div class="m-2 flex h-10 w-6 flex-col rounded bg-gray-100 p-2">
        <img :src="image_url" alt="image" />
        <p class="text-lg">{{ name }}</p>
        <p class="text-lg">${{ price_usd }}</p>
    </div>
</template>
