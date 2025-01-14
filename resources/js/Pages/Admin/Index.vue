<script setup lang="ts">
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
        <table class="mt-3">
            <thead>
                Users
            </thead>
            <tbody>
                <tr class="bg-black text-white hover:bg-black hover:text-white">
                    <td>Id</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Balance</td>
                    <td>Roles</td>
                </tr>
                <tr v-for="user in props.users.data" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>${{ user.balance }}</td>
                    <td>
                        {{
                            user.roles
                                .map((role) => capitalizeFirstLetter(role.name))
                                .join(', ')
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
