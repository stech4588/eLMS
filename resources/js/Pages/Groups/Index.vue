<template>
    <Head title="Groups" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">Groups</h2>
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <input type="text" v-model="search" placeholder="Search for groups..." class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button @click="showCreateGroupPopup = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 flex-shrink-0">
                        Create Group
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="groups.data.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="group in groups.data" :key="group.id" class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col">
                            <div class="p-6 flex-grow">
                                <div class="flex items-center space-x-4 mb-4">
                                    <img :src="group.profile_picture_url" alt="Group Avatar" class="w-16 h-16 rounded-full object-cover flex-shrink-0">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ group.name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Created by {{ group.creator.name }}</p>
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2">{{ group.description }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-b-lg flex justify-end items-center space-x-2">
                                <button v-if="group.creator.id === $page.props.auth.user.id" @click="openInvitePopup(group)" class="px-3 py-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">Invite</button>
                                
                                <Link v-if="group.is_member" :href="route('groups.chat', group.id)" class="px-3 py-1 text-sm font-semibold bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Chat</Link>
                                <button v-else @click="joinGroup(group.id)" class="px-3 py-1 text-sm font-semibold bg-green-600 text-white rounded-md hover:bg-green-700 transition">Join</button>

                                <button @click="openDetailsPopup(group)" class="px-3 py-1 text-sm font-semibold text-gray-600 dark:text-gray-400 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-white dark:text-gray-400 py-16">
                    <svg class="mx-auto h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-white dark:text-gray-100">No groups found</h3>
                    <p class="mt-1 text-sm text-white">Get started by creating a new group.</p>
                </div>

                <!-- Pagination -->
                <div v-if="groups.links.length > 3" class="mt-6 flex justify-center">
                    <div class="flex flex-wrap -mb-1">
                        <template v-for="(link, key) in groups.links" :key="key">
                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded dark:border-gray-600" v-html="link.label" />
                            <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded dark:border-gray-600 hover:bg-white dark:hover:bg-gray-700 focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-white dark:bg-gray-700': link.active }" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <CreateGroupPopup :show="showCreateGroupPopup" @close="showCreateGroupPopup = false" />
        <GroupDetailsPopup :show="showDetailsPopup" :group="selectedGroup" @close="showDetailsPopup = false" />
        <InviteMemberPopup :show="showInvitePopup" :group="selectedGroup" @close="showInvitePopup = false" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import CreateGroupPopup from './CreateGroupPopup.vue';
import GroupDetailsPopup from './GroupDetailsPopup.vue';
import InviteMemberPopup from './InviteMemberPopup.vue';

const props = defineProps({
    groups: Object,
    filters: Object,
});

const form = useForm({});
const search = ref(props.filters.search);
const showCreateGroupPopup = ref(false);
const showDetailsPopup = ref(false);
const showInvitePopup = ref(false);
const selectedGroup = ref(null);

watch(search, (value) => {
    router.get(route('groups.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
});

const openDetailsPopup = (group) => {
    selectedGroup.value = group;
    showDetailsPopup.value = true;
};

const openInvitePopup = (group) => {
    selectedGroup.value = group;
    showInvitePopup.value = true;
};

const joinGroup = (groupId) => {
    form.post(route('groups.join', groupId), {
        preserveScroll: true,
    });
};
</script>
