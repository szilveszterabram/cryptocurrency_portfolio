<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Dialog } from 'primevue';
import { ref } from 'vue';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        balance: number;
        roles: {
            id: number;
            name: string;
        }[];
    };
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const capitalizeFirstLetter = (inp: string) => {
    return inp.charAt(0).toUpperCase() + inp.slice(1);
};

const isAdmin = (roles: { id: number; name: string }[]): boolean => {
    return roles.some(
        (role) => role.id === 1 && role.name.toLowerCase() === 'admin',
    );
};

const { props: pageProps } = usePage();

const visible = ref(false);
const editing = ref(false);
</script>

<template>
    <Head :title="props.user.name" />

    <div class="min-w-screen min-h-screen bg-gray-50 p-6">
        <div class="mx-auto max-w-3xl rounded-lg bg-white shadow-md">
            <div class="p-6">
                <div
                    v-if="pageProps.success"
                    class="mb-4 rounded bg-green-500 p-3 text-white"
                >
                    {{ pageProps.success }}
                </div>

                <h2 class="mb-4 text-xl font-semibold text-gray-700">
                    User Details
                </h2>
                <ul class="space-y-2" v-if="editing === false">
                    <li><strong>ID:</strong> {{ props.user.id }}</li>
                    <li><strong>Name:</strong> {{ props.user.name }}</li>
                    <li><strong>Email:</strong> {{ props.user.email }}</li>
                    <li><strong>Balance:</strong> ${{ props.user.balance }}</li>
                    <li>
                        <strong>Roles:&nbsp;</strong>
                        <span>
                            {{
                                props.user.roles
                                    .map((role) =>
                                        capitalizeFirstLetter(role.name),
                                    )
                                    .join(', ')
                            }}
                        </span>
                    </li>
                </ul>

                <ul class="space-y-2" v-if="editing === true">
                    <li><strong>ID:</strong> {{ props.user.id }}</li>
                    <li>
                        <strong>Name:</strong>
                        <TextInput
                            v-model="form.name"
                            type="text"
                            :error-message="form.errors.name"
                        />
                    </li>
                    <li>
                        <strong>Email:</strong>
                        <TextInput
                            v-model="form.email"
                            type="text"
                            :error-message="form.errors.email"
                        />
                    </li>
                    <li><strong>Balance:</strong> ${{ props.user.balance }}</li>
                    <li>
                        <strong>Roles:&nbsp;</strong>
                        <span>
                            {{
                                props.user.roles
                                    .map((role) =>
                                        capitalizeFirstLetter(role.name),
                                    )
                                    .join(', ')
                            }}
                        </span>
                    </li>
                    <div class="mt-4 flex flex-row space-x-2">
                        <button
                            class="rounded bg-gray-200 p-2 hover:bg-gray-100"
                            @click="
                                () => {
                                    form.patch(
                                        route('admin.update', {
                                            user: user.id,
                                        }),
                                    );
                                    editing = !editing;
                                }
                            "
                        >
                            Save
                        </button>
                    </div>
                </ul>

                <hr class="mt-4" />
                <strong>
                    <h1>Actions</h1>
                </strong>
                <div class="mt-2 flex flex-row space-x-2">
                    <button
                        class="rounded bg-gray-200 p-2 hover:bg-gray-100"
                        @click="editing = !editing"
                    >
                        Edit this profile
                    </button>
                    <button
                        class="rounded bg-gray-200 p-2 hover:bg-gray-100"
                        v-if="!isAdmin(props.user.roles)"
                        @click="
                            router.post(
                                route('admin.make-admin', {
                                    user: user.id,
                                }),
                            )
                        "
                    >
                        Make user admin
                    </button>
                    <button
                        class="rounded bg-gray-200 p-2 hover:bg-gray-100"
                        v-if="
                            user.id !==
                            ($page.props.authentication as any).user.id
                        "
                        @click="
                            router.post(
                                route('impersonate.start', {
                                    user: user.id,
                                }),
                            )
                        "
                    >
                        Impersonate user
                    </button>
                </div>

                <hr class="mt-4" />
                <strong class="mb-2">
                    <h1>Danger zone</h1>
                </strong>
                <div class="mt-2 flex flex-row space-x-2"></div>
                <button
                    class="rounded bg-red-500 p-2 text-white hover:bg-red-400"
                    @click="visible = true"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <div class="card flex justify-center">
        <Dialog
            v-model:visible="visible"
            :style="{
                width: '25rem',
                backgroundColor: '#E5E7EB',
                padding: '2rem',
                borderRadius: '20px',
            }"
        >
            <strong>
                <h1>Are you sure you want to delete this user?</h1>
            </strong>

            <div class="flex justify-end gap-2">
                <button
                    @click="visible = false"
                    class="rounded bg-black p-2 text-white hover:bg-gray-800"
                >
                    No, keep it
                </button>
                <button
                    @click="
                        router.delete(
                            route('admin.delete', {
                                user: user.id,
                            }),
                        )
                    "
                    class="rounded bg-black p-2 text-white hover:bg-red-600"
                >
                    Yes, delete it
                </button>
            </div>
        </Dialog>
    </div>
</template>
