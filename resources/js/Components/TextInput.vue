<script setup>
const model = defineModel({
    type: null,
    required: true,
});

defineProps({
    name: {
        type: String,
    },
    type: {
        type: String,
        default: 'text',
    },
    errorMessage: String,
    placeholder: String,
});
</script>

<template>
    <div class="mb-6">
        <label>{{ name }}</label>
        <input
            :type="type"
            v-model="model"
            :class="{ '!ring-red-500': errorMessage }"
            :placeholder="placeholder"
        />
        <small class="text-red-500" v-message="errorMessage">{{
            errorMessage
        }}</small>
    </div>
</template>
