<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen w-full bg-gray-50">
        <header class="fixed h-12 w-full bg-black text-white">
            <nav
                class="mx-auto flex max-w-screen-lg items-center justify-between p-2"
            >
                <div
                    v-if="$page.props.auth.user"
                    class="flex w-full justify-evenly space-x-6 self-start text-xl"
                >
                    <Link
                        :href="route('impersonate.stop')"
                        v-if="
                            ($page.props.impersonation as any).isImpersonating
                        "
                        as="button"
                        method="post"
                        >Stop impersonating</Link
                    >
                    <Link
                        v-if="($page.props.authentication as any).isAdmin"
                        prefetch
                        cache-for="10s"
                        :href="route('invite')"
                        >Send an invitation</Link
                    >
                    <Link
                        v-if="($page.props.authentication as any).isAdmin"
                        prefetch
                        cache-for="10s"
                        :href="route('admin')"
                        >Admin</Link
                    >
                    <Link prefetch cache-for="10s" :href="route('welcome')"
                        >Home</Link
                    >
                    <Link prefetch cache-for="10s" :href="route('assets')"
                        >Assets</Link
                    >
                    <Link prefetch cache-for="10s" :href="route('portfolio')"
                        >My Portfolios</Link
                    >
                    <Link prefetch cache-for="10s" :href="route('observation')"
                        >My Observations</Link
                    >
                    <Link prefetch cache-for="10s" :href="route('profile')">{{
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
                    <Link prefetch cache-for="10s" :href="route('welcome')"
                        >Home</Link
                    >
                    <Link prefetch cache-for="10s" :href="route('assets')"
                        >Assets</Link
                    >
                    <Link :href="route('login')">Login</Link>
                </div>
            </nav>
        </header>
        <main class="mt-8 min-h-screen w-full">
            <div class="h-full w-full bg-gradient-to-b from-teal-800 to-black">
                <slot />
            </div>
        </main>
    </div>
</template>
