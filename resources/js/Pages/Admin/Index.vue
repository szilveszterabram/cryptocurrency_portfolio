<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';

const props = defineProps<{
    users: {
        current_page: number;
        data: {
            id: number;
            name: string;
            email: string;
            balance: number;
            roles: {
                id: number;
                name: string;
            }[];
        }[];
        first_page_url: string;
        from: number;
        to: number;
        total: number;
        lat_page_url: string;
        next_page_url?: string;
        per_page: number;
        prev_page_url?: string;
    };
}>();

const capitalizeFirstLetter = (inp: string) => {
    return inp.charAt(0).toUpperCase() + inp.slice(1);
};
</script>

<template>
    <Head title="Admin" />

    <div class="flex items-center justify-center">
        <table class="mt-3 border-spacing-x-2">
            <thead>
                Users
            </thead>
            <tbody>
                <tr
                    class="bg-black p-2 text-white hover:bg-black hover:text-white"
                >
                    <th class="rounded-tl p-2">Id</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Balance</th>
                    <th class="p-2">Roles</th>
                    <th class="rounded-tr p-2">See</th>
                </tr>
                <tr
                    v-for="user in props.users.data"
                    :key="user.id"
                    class="bg-gray-100 hover:bg-gray-200"
                >
                    <td class="p-2">{{ user.id }}</td>
                    <td class="p-2">{{ user.name }}</td>
                    <td class="p-2">{{ user.email }}</td>
                    <td class="p-2">${{ user.balance }}</td>
                    <td class="p-2">
                        {{
                            user.roles
                                .map((role) => capitalizeFirstLetter(role.name))
                                .join(', ')
                        }}
                    </td>
                    <td class="p-2">
                        <Eye
                            class="rounded bg-gray-300 p-2 hover:cursor-pointer hover:bg-gray-500 hover:text-white"
                            :size="38"
                            @click="
                                router.get(
                                    route('admin.show', {
                                        user: user.id,
                                    }),
                                )
                            "
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
