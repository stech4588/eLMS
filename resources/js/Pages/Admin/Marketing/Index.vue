<template>
    <Head title="Marketing Management" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Form View (Edit or Create) -->
                <div v-if="editingItem || isCreating" class="bg-white dark:bg-[#1A2C38] overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-white border-b border-gray-200 dark:border-[#2f4455]">
                        <h3 class="text-xl font-semibold mb-6">{{ viewTitle }}</h3>
                        <form @submit.prevent="submitForm">
                            <!-- Quote Form Fields -->
                            <div v-if="activeTab === 'quotes'">
                                <div class="mb-4">
                                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-[#e5f2ff]">Content</label>
                                    <textarea id="content" v-model="form.content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-[#293E4C] dark:border-[#3b5161] dark:text-white"></textarea>
                                    <InputError :message="form.errors.content" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label for="author" class="block text-sm font-medium text-gray-700 dark:text-[#e5f2ff]">Author</label>
                                    <input type="text" id="author" v-model="form.author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-[#293E4C] dark:border-[#3b5161] dark:text-white" />
                                    <InputError :message="form.errors.author" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label for="is_active_quote" class="flex items-center">
                                        <input type="checkbox" id="is_active_quote" v-model="form.is_active" class="rounded dark:bg-[#0F212E] border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-[#0F212E]" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-[#b8d4e5]">Is Active</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Promotion Form Fields -->
                            <div v-if="activeTab === 'promotions'">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ isEditMode ? 'Edit Promotion' : 'Add New Promotion' }}</h3>

                                <div class="mt-4">
                                    <InputLabel for="title" value="Title" />
                                    <TextInput id="title" v-model="form.title" class="mt-1 block w-full" required />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>

                                <div class="mb-4">
                                    <label for="promotion_type" class="block text-sm font-medium text-gray-700 dark:text-[#e5f2ff]">Promotion Type</label>
                                    <select id="promotion_type" v-model="form.promotion_type" class="mt-1 block w-full border-gray-300 dark:border-[#3b5161] dark:bg-[#293E4C] dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="text">Text Promotion</option>
                                        <option value="poster">Poster Promotion</option>
                                    </select>
                                    <InputError :message="form.errors.promotion_type" class="mt-2" />
                                </div>

                                <div class="mb-4" v-if="form.promotion_type === 'text'">
                                    <label for="promotion_text" class="block text-sm font-medium text-gray-700 dark:text-[#e5f2ff]">Promotion Text</label>
                                    <textarea id="promotion_text" v-model="form.text_content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-[#293E4C] dark:border-[#3b5161] dark:text-white"></textarea>
                                    <InputError :message="form.errors.text_content" class="mt-2" />
                                </div>

                                <div class="mb-4" v-if="form.promotion_type === 'poster'">
                                    <label for="poster_image" class="block text-sm font-medium text-gray-700 dark:text-[#e5f2ff]">Poster Image</label>
                                    <input type="file" @change="onFileChange" id="poster_image" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-[#c8dcee] focus:outline-none dark:bg-[#293E4C] dark:border-[#3b5161] dark:placeholder-[#9db8ca]" />
                                    <div v-if="form.image_url" class="mt-2">
                                        <img :src="form.image_url" alt="Poster Preview" class="h-20 w-20 object-cover rounded-md">
                                    </div>
                                    <InputError :message="form.errors.poster_image" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label for="promotion_is_active" class="flex items-center">
                                        <input type="checkbox" id="promotion_is_active" v-model="form.is_active" class="rounded dark:bg-[#0F212E] border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-[#0F212E]" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-[#b8d4e5]">Is Active</span>
                                    </label>
                                </div>

                                <div class="mb-4">
                                    <label for="till_date" class="block text-sm font-medium text-gray-700 dark:text-[#e5f2ff]">Till Date</label>
                                    <input type="date" id="till_date" v-model="form.till_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-[#293E4C] dark:border-[#3b5161] dark:text-white" />
                                    <InputError :message="form.errors.till_date" class="mt-2" />
                                </div>
                            </div>

                             <!-- Prompt Form Fields -->
                            <div v-if="activeTab === 'prompts'">
                                <div class="mt-4">
                                    <InputLabel for="prompt_title" value="Title" />
                                    <TextInput id="prompt_title" v-model="form.title" class="mt-1 block w-full" required />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="prompt_text" value="Prompt Text" />
                                    <textarea id="prompt_text" v-model="form.prompt_text" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-[#293E4C] dark:border-[#3b5161] dark:text-white"></textarea>
                                    <InputError class="mt-2" :message="form.errors.prompt_text" />
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="target_audience" value="Target Audience" />
                                    <select id="target_audience" v-model="form.target_audience" class="mt-1 block w-full border-gray-300 dark:border-[#3b5161] dark:bg-[#293E4C] dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="students">Students</option>
                                        <option value="instructors">Instructors</option>
                                        <option value="all">All</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.target_audience" />
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="trigger_condition" value="Trigger Condition" />
                                    <select id="trigger_condition" v-model="form.trigger_condition" @change="resetFrequency" class="mt-1 block w-full border-gray-300 dark:border-[#3b5161] dark:bg-[#293E4C] dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.trigger_condition" />
                                </div>

                                <!-- Daily Condition Fields -->
                                <div v-if="form.trigger_condition === 'daily'">
                                    <div class="mt-4">
                                        <InputLabel for="times_per_day" value="Times Per Day" />
                                        <TextInput id="times_per_day" type="number" v-model.number="form.times_per_day" @input="updateFrequencyTimes" min="1" class="mt-1 block w-full" required />
                                        <InputError class="mt-2" :message="form.errors.times_per_day" />
                                    </div>
                                    <div class="mt-4" v-for="(time, index) in form.frequency" :key="index">
                                        <InputLabel :for="'frequency_time_' + index" :value="'Time ' + (index + 1) + ' (EST)'" />
                                        <TextInput :id="'frequency_time_' + index" type="time" v-model="form.frequency[index]" class="mt-1 block w-full" required />
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.frequency" />
                                </div>

                                <!-- Weekly Condition Fields -->
                                <div v-if="form.trigger_condition === 'weekly'" class="mt-4">
                                    <InputLabel for="frequency_day" value="Day of the Week" />
                                    <select id="frequency_day" v-model="form.frequency" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.frequency" />
                                </div>
                                <div class="mt-4">
                                    <label for="prompt_is_active" class="flex items-center">
                                        <input type="checkbox" id="prompt_is_active" v-model="form.is_active" class="rounded dark:bg-[#0F212E] border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-[#0F212E]" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-[#b8d4e5]">Is Active</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button @click.prevent="cancelAction" type="button" class="px-4 py-2 border border-gray-300 dark:border-[#3b5161] rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-[#e5f2ff] hover:bg-gray-50 dark:hover:bg-[#253a49] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
                                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">{{ editingItem ? 'Save Changes' : 'Create' }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List View (Tabs and Tables) -->
                <div v-else>
                    <!-- Tab Navigation -->
                    <div class="mb-6">
                        <nav class="flex space-x-4 tabs_marketing_management">
                            <button @click="changeTab('quotes')" :class="{' bg-[#3b82f6] text-white ': activeTab === 'quotes', ' text-black dark:text-[#e5f2ff] bg-white dark:bg-[#1A2C38] ': activeTab !== 'quotes'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2">
                                Quotes
                            </button>
                            <button @click="changeTab('promotions')" :class="{'bg-[#3b82f6] text-white': activeTab === 'promotions', 'border-transparent text-black dark:text-[#e5f2ff] bg-white dark:bg-[#1A2C38]': activeTab !== 'promotions'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2" style="margin-left: 0px;">
                                Promotions
                            </button>
                             <button @click="changeTab('prompts')" :class="{'bg-[#3b82f6] text-white': activeTab === 'prompts', 'border-transparent text-black dark:text-[#e5f2ff] bg-white dark:bg-[#1A2C38]': activeTab !== 'prompts'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2" style="margin-left: 0px;">
                                Prompts
                            </button>
                        </nav>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Quotes Box -->
                        <div v-if="activeTab === 'quotes'" class="bg-white dark:bg-[#1A2C38] overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-white">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Quotes</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Quote</button>
                                </div>
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-[#0F212E] dark:text-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Content</th>
                                                <th scope="col" class="px-6 py-3">Author</th>
                                                <th scope="col" class="px-6 py-3">Active</th>
                                                <th scope="col" class="px-6 py-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="quote in quotes.data" :key="quote.id" class="bg-white border-b dark:bg-[#293E4C] dark:border-gray-700">
                                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ quote.content.split(' ').slice(0, 10).join(' ') + (quote.content.split(' ').length > 10 ? '...' : '') }}</td>
                                                <td class="px-6 py-4">{{ quote.author }}</td>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" :checked="quote.is_active" @change="toggleStatus(quote)" class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                                </td>
                                                <td class="px-6 py-4 flex items-center">
                                                    <button @click="startEdit(quote)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4 dark:invert action-icon" /></button>
                                                    <button @click="deleteItem(quote.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline"><img src="/images/delete_icon.svg" alt="Delete" class="w-4 h-4 dark:invert action-icon" /></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination class="mt-6" :links="quotes.links" />
                            </div>
                        </div>

                        <!-- Promotions Box -->
                        <div v-if="activeTab === 'promotions'" class="bg-white dark:bg-[#1A2C38] overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-white">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Promotions</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Promotion</button>
                                </div>
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-[#0F212E] dark:text-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">ID</th>
                                                <th scope="col" class="px-6 py-3">Title</th>
                                                <th scope="col" class="px-6 py-3">Type</th>
                                                <th scope="col" class="px-6 py-3">Content/Image</th>
                                                <th scope="col" class="px-6 py-3">Active</th>
                                                <th scope="col" class="px-6 py-3">Till Date</th>
                                                <th scope="col" class="px-6 py-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="promotion in promotions.data" :key="promotion.id" class="bg-white border-b dark:bg-[#293E4C] dark:border-gray-700">
                                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ promotion.id }}</td>
                                                <td class="px-6 py-4">{{ promotion.title }}</td>
                                                <td class="px-6 py-4">{{ promotion.promotion_type }}</td>
                                                <td class="px-6 py-4">
                                                    <span v-if="promotion.promotion_type === 'text'">{{ promotion.text_content ? (promotion.text_content.split(' ').slice(0, 10).join(' ') + (promotion.text_content.split(' ').length > 10 ? '...' : '')) : '' }}</span>
                                                    <img v-else-if="promotion.promotion_type === 'poster' && promotion.image_url" :src="promotion.image_url" alt="Poster" class="h-10 w-10 object-cover rounded" />
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" :checked="promotion.is_active" @change="toggleStatus(promotion)" class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                                </td>
                                                <td class="px-6 py-4">{{ promotion.till_date }}</td>
                                                <td class="px-6 py-4 flex items-center">
                                                    <button @click="startEdit(promotion)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4 dark:invert action-icon" /></button>
                                                    <button @click="deleteItem(promotion.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline"><img src="/images/delete_icon.svg" alt="Delete" class="w-4 h-4 dark:invert action-icon" /></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination class="mt-6" :links="promotions.links" />
                            </div>
                        </div>

                        <!-- Prompts Box -->
                        <div v-if="activeTab === 'prompts'" class="bg-white dark:bg-[#1A2C38] overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-white">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Prompts</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Prompt</button>
                                </div>
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-[#0F212E] dark:text-gray-200">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Title</th>
                                                <th scope="col" class="px-6 py-3">Target Audience</th>
                                                <th scope="col" class="px-6 py-3">Trigger</th>
                                                <th scope="col" class="px-6 py-3">Frequency</th>
                                                <th scope="col" class="px-6 py-3">Active</th>
                                                <th scope="col" class="px-6 py-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="prompt in prompts.data" :key="prompt.id" class="bg-white border-b dark:bg-[#293E4C] dark:border-gray-700">
                                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ prompt.title }}</td>
                                                <td class="px-6 py-4">{{ prompt.target_audience }}</td>
                                                <td class="px-6 py-4">{{ prompt.trigger_condition }}</td>
                                                <td class="px-6 py-4">{{ formatFrequency(prompt) }}</td>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" :checked="prompt.is_active" @change="toggleStatus(prompt)" class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                                </td>
                                                <td class="px-6 py-4 flex items-center">
                                                    <button @click="startEdit(prompt)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4 dark:invert action-icon" /></button>
                                                    <button @click="deleteItem(prompt.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline"><img src="/images/delete_icon.svg" alt="Delete" class="w-4 h-4 dark:invert action-icon" /></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination class="mt-6" :links="prompts.links" />
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
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    quotes: Object,
    promotions: Object,
    prompts: Object, // New prop for prompts data
    errors: Object,
});

const editingItem = ref(null);
const isCreating = ref(false);
const activeTab = ref('quotes');

const form = useForm({
    // Common fields
    title: '',
    is_active: false,
    
    // Quote specific fields
    content: '',
    author: '',
    
    // Promotion specific fields
    promotion_type: 'text',
    text_content: '',
    poster_image: null,
    image_url: null,
    till_date: null,
    
    // Prompt specific fields
    prompt_text: '',
    target_audience: 'students',
    trigger_condition: 'daily',
    frequency: [],
    times_per_day: 1,
});

const viewTitle = computed(() => {
    let action = editingItem.value ? 'Edit' : 'Create New';
    if (activeTab.value === 'quotes') return `${action} Quote`;
    if (activeTab.value === 'promotions') return `${action} Promotion`;
    if (activeTab.value === 'prompts') return `${action} Prompt`;
    return '';
});

const changeTab = (tab) => {
    activeTab.value = tab;
    cancelAction();
};

const startCreate = () => {
    isCreating.value = true;
    editingItem.value = null;
    form.reset();
    form.clearErrors();
    if (activeTab.value === 'promotions') {
        form.promotion_type = 'text';
        form.is_active = false;
        form.till_date = null;
    } else if (activeTab.value === 'quotes') {
        form.is_active = false;
    } else if (activeTab.value === 'prompts') {
        form.is_active = true;
        form.target_audience = 'students';
        form.trigger_condition = 'daily';
        form.times_per_day = 1;
        form.frequency = [''];
    }
};

const startEdit = (item) => {
    isCreating.value = false;
    editingItem.value = { ...item };
    form.clearErrors();

    if (activeTab.value === 'quotes') {
        form.content = item.content;
        form.author = item.author;
        form.is_active = !!item.is_active;
    } else if (activeTab.value === 'promotions') {
        form.title = item.title;
        form.promotion_type = item.promotion_type;
        form.text_content = item.text_content;
        form.image_url = item.image_url;
        form.is_active = !!item.is_active;
        form.till_date = item.till_date;
    } else if (activeTab.value === 'prompts') {
        form.title = item.title;
        form.prompt_text = item.prompt_text;
        form.target_audience = item.target_audience;
        form.trigger_condition = item.trigger_condition;
        form.times_per_day = item.times_per_day || 1;
        form.is_active = !!item.is_active;

        if (item.trigger_condition === 'daily') {
            try {
                const parsedFrequency = JSON.parse(item.frequency);
                form.frequency = Array.isArray(parsedFrequency) ? parsedFrequency : [parsedFrequency];
            } catch (e) {
                form.frequency = [item.frequency];
            }
            updateFrequencyTimes();
        } else {
            form.frequency = item.frequency;
        }
    }
};

const cancelAction = () => {
    isCreating.value = false;
    editingItem.value = null;
    form.reset();
    form.clearErrors();
};

const resetFrequency = () => {
    if (form.trigger_condition === 'daily') {
        form.times_per_day = 1;
        form.frequency = [''];
    } else {
        form.times_per_day = null;
        form.frequency = 'Monday';
    }
};

const updateFrequencyTimes = () => {
    const times = form.times_per_day > 0 ? form.times_per_day : 1;
    const currentTimes = Array.isArray(form.frequency) ? form.frequency : [];
    const newTimes = [];
    for (let i = 0; i < times; i++) {
        newTimes.push(currentTimes[i] || '');
    }
    form.frequency = newTimes;
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.poster_image = file;
        form.image_url = URL.createObjectURL(file);
    }
};

const submitForm = () => {
    if (activeTab.value === 'quotes') {
        if (editingItem.value) {
            router.put(`/admin/marketing/${editingItem.value.id}`, form, {
                preserveScroll: true,
                onSuccess: () => {
                    cancelAction();
                    Swal.fire('Updated!', 'The quote has been updated.', 'success');
                },
            });
        } else if (isCreating.value) {
            form.post('/admin/marketing', {
                onSuccess: () => {
                    cancelAction();
                    Swal.fire('Created!', 'The quote has been created.', 'success');
                },
            });
        }
    } else if (activeTab.value === 'promotions') {
        const url = editingItem.value ? `/admin/promotions/${editingItem.value.id}` : '/admin/promotions';
        
        router.post(url, {
            ...form.data(),
            _method: editingItem.value ? 'put' : 'post',
        }, {
            forceFormData: true, 
            preserveScroll: true,
            onSuccess: () => {
                cancelAction();
                Swal.fire('Success!', 'Promotion saved successfully.', 'success');
            },
        });
    } else if (activeTab.value === 'prompts') {
        if (editingItem.value) {
            router.put(`/admin/prompts/${editingItem.value.id}`, form, {
                preserveScroll: true,
                onSuccess: () => {
                    cancelAction();
                    Swal.fire('Updated!', 'The prompt has been updated.', 'success');
                },
            });
        } else {
            form.post('/admin/prompts', {
                 onSuccess: () => {
                    cancelAction();
                    Swal.fire('Created!', 'The prompt has been created.', 'success');
                },
            });
        }
    }
};

const toggleStatus = (item) => {
    let url = '';
    if (activeTab.value === 'quotes') url = `/admin/marketing/${item.id}/toggle-status`;
    else if (activeTab.value === 'promotions') url = `/admin/promotions/${item.id}/toggle-status`;
    else if (activeTab.value === 'prompts') url = `/admin/prompts/${item.id}/toggle-status`;

    if (url) {
        if (typeof window !== 'undefined' && typeof window.showPageLoader === 'function') {
            window.showPageLoader();
        }
        router.post(url, {}, {
            preserveScroll: true,
            onSuccess: () => {
                if (typeof window !== 'undefined' && typeof window.hidePageLoader === 'function') {
                    window.hidePageLoader();
                }
                Swal.fire('Status Updated!', 'The status has been toggled.', 'success');
            },
            onError: () => {
                if (typeof window !== 'undefined' && typeof window.hidePageLoader === 'function') {
                    window.hidePageLoader();
                }
                Swal.fire('Error', 'Failed to toggle status. Please try again.', 'error');
            },
            onFinish: () => {
                if (typeof window !== 'undefined' && typeof window.hidePageLoader === 'function') {
                    window.hidePageLoader();
                }
            }
        });
    }
};

const deleteItem = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let url = '';
            if (activeTab.value === 'quotes') url = `/admin/marketing/${id}`;
            else if (activeTab.value === 'promotions') url = `/admin/promotions/${id}`;
            else if (activeTab.value === 'prompts') url = `/admin/prompts/${id}`;

            if (url) {
                router.delete(url, {
                    preserveScroll: true,
                    onSuccess: () => Swal.fire('Deleted!', 'The item has been deleted.', 'success'),
                });
            }
        }
    });
};

const formatFrequency = (prompt) => {
    if (prompt.trigger_condition === 'weekly') {
        return prompt.frequency;
    }
    if (prompt.trigger_condition === 'daily') {
        try {
            const times = JSON.parse(prompt.frequency);
            if (Array.isArray(times)) {
                return times.map(time => {
                    if (!time) return '';
                    const [hour, minute] = time.split(':');
                    if (hour === undefined || minute === undefined) return time;
                    let h = parseInt(hour, 10);
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12;
                    h = h ? h : 12;
                    return `${h}:${minute} ${ampm}`;
                }).join(', ');
            }
        } catch (e) {
            if (typeof prompt.frequency === 'string' && prompt.frequency.includes(':')) {
                const [hour, minute] = prompt.frequency.split(':');
                if (hour !== undefined && minute !== undefined) {
                    let h = parseInt(hour, 10);
                    const ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12;
                    h = h ? h : 12;
                    return `${h}:${minute} ${ampm}`;
                }
            }
            return prompt.frequency;
        }
    }
    return prompt.frequency;
};
</script>

<style scoped>
.tabs_marketing_management {
    gap: 10px;
}
@media (max-width: 425px) {
    .tabs_marketing_management {
        flex-direction: column;
    }
}
.dark .course_management_dark_icons {
    filter: invert(1);
}
</style>
