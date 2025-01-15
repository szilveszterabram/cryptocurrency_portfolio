<script setup lang="ts"></script>

<style scoped>
.gradient-text {
    background: linear-gradient(90deg, #ee0979, #42a5f5, #7b1fa2, #ee0979);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: flowing-gradient 10s linear infinite;
}

@keyframes flowing-gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>

<template>
    <Head title="Welcome" />

    <div class="m-4 flex-col space-y-6 text-white">
        <p class="gradient-text pt-6 text-8xl font-bold">
            Cryptocurrency Portfolio
        </p>
        <p class="gradient-text w-1/2 text-4xl font-semibold">
            Start investing Today.
        </p>
    </div>
</template>
