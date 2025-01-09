<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps({
    type: { type: String, required: true },
    message: { type: String, required: true },
});

const visible = ref(false);

const hide = () => {
    visible.value = false;
};

watch(
    () => props.message,
    () => {
        visible.value = true;
        setTimeout(() => {
            hide();
        }, 20000);
    },
);
</script>

<template>
    <div
        v-if="visible"
        :class="[
            'fixed right-4 top-4 rounded px-4 py-2 text-white shadow-lg',
            type === 'success' ? 'bg-green-500' : 'bg-red-500',
        ]"
        @click="hide"
    >
        {{ message }}
    </div>
</template>
