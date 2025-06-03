<template>
    <Head title="Course Management" />

    <AuthenticatedLayout>
        

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Form View (Edit or Create) -->
                <div v-if="editingItem || isCreating" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <h3 class="text-xl font-semibold mb-6">{{ viewTitle }}</h3>
                        <form @submit.prevent="submitForm">
                            <div>
                                <label for="itemName" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" v-model="form.name" id="itemName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Name">
                                <p v-if="form.errors.name" class="text-red-500 text-xs italic mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div class="mt-4">
                                <label for="itemDescription" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea v-model="form.description" id="itemDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Description"></textarea>
                                <p v-if="form.errors.description" class="text-red-500 text-xs italic mt-1">{{ form.errors.description }}</p>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button @click.prevent="cancelAction" type="button" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ editingItem ? 'Save Changes' : 'Create' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List View (Tabs and Tables) -->
                <div v-else>
                    <!-- Tab Navigation -->
                    <div class="mb-6">
                        <nav class="flex space-x-4 tabs_course_management" >
                            <button @click="activeTab = 'courseType'" :class="{' bg-[#3b82f6] text-white ': activeTab === 'courseType', ' text-black-700 bg-white ': activeTab !== 'courseType'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2">
                                Course Types
                            </button>
                            <button @click="activeTab = 'topic'" :class="{'bg-[#3b82f6] text-white': activeTab === 'topic', 'border-transparent text-black-500  bg-white ': activeTab !== 'topic'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2 " style="margin-left: 0px;">
                                Topics
                            </button>
                            <button @click="activeTab = 'courseCertificate'" :class="{'bg-[#3b82f6] text-white': activeTab === 'courseCertificate', 'border-transparent text-black-500 bg-white ': activeTab !== 'courseCertificate'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2" style="margin-left: 0px;">
                                Course Certificates
                            </button>
                            <button @click="activeTab = 'courseIndustry'" :class="{'bg-[#3b82f6] text-white': activeTab === 'courseIndustry', 'border-transparent text-black-500  bg-white': activeTab !== 'courseIndustry'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2" style="margin-left: 0px;">
                                Course Industries
                            </button>
                        </nav>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Course Types Box -->
                        <div v-if="activeTab === 'courseType'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Course Types</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create New</button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class=" py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">ID</th>
                                                <th scope="col" class=" py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">Name</th>
                                                <th scope="col" class=" py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="justify-content: flex-end; display: flex;color: black; font-weight: 600; font-size: 14px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="courseType in courseTypes.data" :key="courseType.id">
                                                <td class="py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ courseType.id }}</td>
                                                <td class=" py-4 whitespace-nowrap text-sm text-gray-500">{{ courseType.name }}</td>
                                                <td class="py-4 whitespace-nowrap text-sm font-medium" style="justify-content: flex-end; display: flex;">
                                                    <button @click="startEdit(courseType)" class="px-2 py-1 text-white rounded mr-2"><img src="/images/pen_icon.svg" alt="Edit" /></button>
                                                    <button @click="deleteItem(courseType.id)" class="px-2 py-1"><img src="/images/delete_icon.svg" alt="delete" class="w-4 h-4"/></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Topics Box -->
                        <div v-if="activeTab === 'topic'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Topics</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create New</button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="justify-content: flex-end; display: flex; color: black; font-weight: 600; font-size: 14px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="topic in topics.data" :key="topic.id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ topic.id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ topic.name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="justify-content: flex-end; display: flex;">
                                                    <button @click="startEdit(topic)" class="px-2 py-1 text-white rounded mr-2"><img src="/images/pen_icon.svg" alt="Edit" /></button>
                                                    <button @click="deleteItem(topic.id)" class="px-2 py-1"><img src="/images/delete_icon.svg" alt="delete" class="w-4 h-4"/></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Course Certificates Box -->
                        <div v-if="activeTab === 'courseCertificate'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Course Certificates</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create New</button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">Description</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="justify-content: flex-end; display: flex; color: black; font-weight: 600; font-size: 14px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="certificate in courseCertificates.data" :key="certificate.id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ certificate.id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ certificate.name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ certificate.description }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="justify-content: flex-end; display: flex;">
                                                    <button @click="startEdit(certificate)" class="px-2 py-1 text-white rounded mr-2"><img src="/images/pen_icon.svg" alt="Edit" /></button>
                                                    <button @click="deleteItem(certificate.id)" class="px-2 py-1"><img src="/images/delete_icon.svg" alt="delete" class="w-4 h-4"/></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Course Industries Box -->
                        <div v-if="activeTab === 'courseIndustry'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Course Industries</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create New</button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">ID</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: black; font-weight: 600; font-size: 14px;">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="justify-content: flex-end; display: flex; color: black; font-weight: 600; font-size: 14px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="industry in courseIndustries.data" :key="industry.id">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ industry.id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ industry.name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="justify-content: flex-end; display: flex;">
                                                    <button @click="startEdit(industry)" class="px-2 py-1 text-white rounded mr-2"><img src="/images/pen_icon.svg" alt="Edit" /></button>
                                                    <button @click="deleteItem(industry.id)" class="px-2 py-1"><img src="/images/delete_icon.svg" alt="delete" class="w-4 h-4"/></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    courseTypes: Object,
    topics: Object,
    courseCertificates: Object,
    courseIndustries: Object,
    errors: Object
});

const editingItem = ref(null);
const isCreating = ref(false);
const currentItemType = ref('');
const currentItemId = ref(null);
const activeTab = ref('courseType');

const form = useForm({
    name: '',
    description: '',
});

const viewTitle = computed(() => {
    let action = 'View';
    if (editingItem.value) {
        action = 'Edit';
    } else if (isCreating.value) {
        action = 'Create New';
    }
    
    let itemTypeName = '';
    if (currentItemType.value === 'courseType') itemTypeName = 'Course Type';
    if (currentItemType.value === 'topic') itemTypeName = 'Topic';
    if (currentItemType.value === 'courseCertificate') itemTypeName = 'Course Certificate';
    if (currentItemType.value === 'courseIndustry') itemTypeName = 'Course Industry';
    
    return `${action} ${itemTypeName}`;
});

const startCreate = () => {
    isCreating.value = true;
    editingItem.value = null;
    currentItemId.value = null;
    currentItemType.value = activeTab.value;
    form.reset();
    form.clearErrors();
};

const startEdit = (item) => {
    isCreating.value = false;
    currentItemType.value = activeTab.value;
    editingItem.value = { ...item };
    currentItemId.value = item.id;
    form.name = item.name;
    form.description = item.description;
    form.clearErrors();
};

const cancelAction = () => {
    isCreating.value = false;
    editingItem.value = null;
    currentItemId.value = null;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    const urlMap = {
        courseType: '/course-types',
        topic: '/topics',
        courseCertificate: '/course-certificates',
        courseIndustry: '/course-industries',
    };
    const baseUrl = urlMap[currentItemType.value];

    if (editingItem.value && currentItemId.value) {
        form.put(`${baseUrl}/${currentItemId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                cancelAction();
            },
            onError: (errors) => {
                console.log("Error updating:", errors);
            }
        });
    } else if (isCreating.value) {
        form.post(baseUrl, {
            preserveScroll: true,
            onSuccess: () => {
                cancelAction();
            },
            onError: (errors) => {
                console.log("Error creating:", errors);
            }
        });
    }
};

const deleteItem = (id) => {
    if (!confirm('Are you sure you want to delete this item?')) return;

    const urlMap = {
        courseType: '/course-types',
        topic: '/topics',
        courseCertificate: '/course-certificates',
        courseIndustry: '/course-industries',
    };
    const url = `${urlMap[activeTab.value]}/${id}`;

    router.delete(url, {
        preserveScroll: true,
        onSuccess: () => {
        }
    });
};

</script> 

<style scoped>
.tabs_course_management{
    gap: 10px;
}
@media (max-width: 425px) {
.tabs_course_management {
    
    flex-direction: column;
    
}}
</style>