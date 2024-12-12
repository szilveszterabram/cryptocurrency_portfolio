<script setup lang="ts"></script>

<template>
    <div class="flex h-full w-full flex-col">
        <header class="fixed h-12 w-full bg-black text-white">
            <nav
                class="mx-auto flex max-w-screen-lg items-center justify-between p-2"
            >
                <div
                    v-if="$page.props.auth.user"
                    class="flex w-full justify-evenly space-x-6 self-start text-xl"
                >
                    <Link :href="route('welcome')">Home</Link>
                    <Link :href="route('assets.first')">Assets</Link>
                    <Link :href="route('profile')">{{
                        $page.props.auth.user.name
                    }}</Link>
                    <Link :href="route('logout')" as="button" method="post"
                        >Log out</Link
                    >
                </div>
                <div
                    v-else
                    class="flex w-full justify-evenly space-x-6 self-start text-xl"
                >
                    <Link :href="route('register')">Register</Link>
                    <Link :href="route('login')">Login</Link>
                </div>
            </nav>
        </header>
        <main class="mb-5 mt-14 flex h-full w-full p-2">
            <slot />
        </main>
    </div>
</template>
