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
    <div class="mb-6 flex flex-col">
        <strong>
            <label>{{ name }}</label>
        </strong>

        <input
            :type="type"
            v-model="model"
            :class="{ '!ring-red-500': errorMessage }"
            :placeholder="placeholder"
            class="rounded"
        />
        <small class="text-red-500" v-message="errorMessage">{{
            errorMessage
        }}</small>
    </div>
</template>
