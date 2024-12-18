<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

const properties = defineProps<{
    observations: {
        id: number;
        user_id: number;
        asset_id: string;
        target: string;
        active: boolean;
        icon_url: string;
    }[];
}>();
</script>

<template>
    <Head title="My Observations" />

    <table v-if="observations?.length != 0" class="mt-5 p-2">
        <tbody>
            <tr
                class="rounded bg-black text-lg text-white hover:bg-black hover:text-white"
            >
                <td>Name</td>
                <td>Target value</td>
                <td>Status</td>
                <td>Delete</td>
            </tr>
            <tr v-for="observation in observations" :key="observation.id">
                <td class="text-lm w-min items-center">
                    <span
                        ><img
                            v-if="observation.icon_url"
                            :src="observation.icon_url"
                            alt="Icon"
                            width="30"
                            height="30"
                        />{{ observation.asset_id }}</span
                    >
                </td>
                <td>${{ observation.target }}</td>
                <td
                    :class="
                        'font-bold ' +
                        (observation.active ? 'text-green-600' : 'text-red-500')
                    "
                >
                    {{ observation.active ? 'Active' : 'Inactive' }}
                </td>
                <td>
                    <Trash2
                        class="rounded bg-gray-100 p-2 hover:cursor-pointer hover:bg-red-500 hover:text-white"
                        :size="38"
                        @click="
                            router.delete(
                                route('observation.destroy', {
                                    observation: observation.id,
                                }),
                            )
                        "
                    />
                </td>
            </tr>
        </tbody>
    </table>

    <div
        class="flex h-full w-full justify-center"
        v-if="observations?.length == 0"
    >
        <div class="items-center self-center rounded bg-gray-200 p-4 text-lg">
            <p>Looks like you don't have any coin observations yet.</p>
        </div>
    </div>
</template>
