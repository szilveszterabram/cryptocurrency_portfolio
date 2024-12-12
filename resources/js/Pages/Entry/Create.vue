<script setup lang="ts">
const properties = defineProps<{
    asset: {
        asset_id: String;
        name: String;
        price_usd: String;
    };
    icon_url: String;
}>();
</script>

<template>
    <div class="flex h-full w-full items-center justify-center">
        You can buy the coin here
    </div>
</template>
