<template>
    <Head title="Marketing Management" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Form View (Edit or Create) -->
                <div v-if="editingItem || isCreating" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold mb-6">{{ viewTitle }}</h3>
                        <form @submit.prevent="submitForm">
                            <!-- Quote Form Fields -->
                            <div v-if="activeTab === 'quotes'">
                                <div class="mb-4">
                                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                                    <textarea id="content" v-model="form.content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                    <InputError :message="form.errors.content" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Author</label>
                                    <input type="text" id="author" v-model="form.author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                    <InputError :message="form.errors.author" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label for="is_active_quote" class="flex items-center">
                                        <input type="checkbox" id="is_active_quote" v-model="form.is_active" class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is Active</span>
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
                                    <label for="promotion_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Promotion Type</label>
                                    <select id="promotion_type" v-model="form.promotion_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="text">Text Promotion</option>
                                        <option value="poster">Poster Promotion</option>
                                    </select>
                                    <InputError :message="form.errors.promotion_type" class="mt-2" />
                                </div>

                                <div class="mb-4" v-if="form.promotion_type === 'text'">
                                    <label for="promotion_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Promotion Text</label>
                                    <textarea id="promotion_text" v-model="form.text_content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                                    <InputError :message="form.errors.text_content" class="mt-2" />
                                </div>

                                <div class="mb-4" v-if="form.promotion_type === 'poster'">
                                    <label for="poster_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poster Image</label>
                                    <input type="file" @change="onFileChange" id="poster_image" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                                    <div v-if="form.image_url" class="mt-2">
                                        <img :src="form.image_url" alt="Poster Preview" class="h-20 w-20 object-cover rounded-md">
                                    </div>
                                    <InputError :message="form.errors.poster_image" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label for="promotion_is_active" class="flex items-center">
                                        <input type="checkbox" id="promotion_is_active" v-model="form.is_active" class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is Active</span>
                                    </label>
                                </div>

                                <div class="mb-4">
                                    <label for="till_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Till Date</label>
                                    <input type="date" id="till_date" v-model="form.till_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                    <InputError :message="form.errors.till_date" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button @click.prevent="cancelAction" type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
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
                            <button @click="changeTab('quotes')" :class="{' bg-[#3b82f6] text-white ': activeTab === 'quotes', ' text-black dark:text-gray-300 bg-white dark:bg-dark-bg-secondary ': activeTab !== 'quotes'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2">
                                Quotes
                            </button>
                            <button @click="changeTab('promotions')" :class="{'bg-[#3b82f6] text-white': activeTab === 'promotions', 'border-transparent text-black dark:text-gray-300 bg-white dark:bg-dark-bg-secondary': activeTab !== 'promotions'}" class="px-3 py-2 font-medium text-sm rounded-md mb-2" style="margin-left: 0px;">
                                Promotions
                            </button>
                        </nav>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Quotes Box -->
                        <div v-if="activeTab === 'quotes'" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Quotes</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Quote</button>
                                </div>
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-6 py-3">Content</th>
                                                <th scope="col" class="px-6 py-3">Author</th>
                                                <th scope="col" class="px-6 py-3">Active</th>
                                                <th scope="col" class="px-6 py-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="quote in quotes.data" :key="quote.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ quote.content.split(' ').slice(0, 10).join(' ') + (quote.content.split(' ').length > 10 ? '...' : '') }}</td>
                                                <td class="px-6 py-4">{{ quote.author }}</td>
                                                <td class="px-6 py-4">
                                                    <input type="checkbox" :checked="quote.is_active" @change="toggleStatus(quote)" class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                                                </td>
                                                <td class="px-6 py-4 flex items-center">
                                                    <button @click="startEdit(quote)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4 dark:invert" /></button>
                                                    <button @click="deleteItem(quote.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline"><img src="/images/delete_icon.svg" alt="Delete" class="w-4 h-4 dark:invert" /></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Promotions Box -->
                        <div v-if="activeTab === 'promotions'" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Promotions</h3>
                                    <button @click="startCreate" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Promotion</button>
                                </div>
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                                            <tr v-for="promotion in promotions.data" :key="promotion.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
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
                                                    <button @click="startEdit(promotion)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3"><img src="/images/pen_icon.svg" alt="Edit" class="w-4 h-4 dark:invert" /></button>
                                                    <button @click="deleteItem(promotion.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline"><img src="/images/delete_icon.svg" alt="Delete" class="w-4 h-4 dark:invert" /></button>
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
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    quotes: Object,
    promotions: Object, // New prop for promotions data
    errors: Object,
});

const editingItem = ref(null);
const isCreating = ref(false);
const activeTab = ref('quotes'); // Default to quotes tab

const form = useForm({
    // Common fields for both quotes and promotions
    is_active: false,
    // Quote specific fields
    content: '',
    author: '',
    
    // Promotion specific fields
    promotion_type: 'text', // Default promotion type
    text_content: '',
    poster_image: null,
    image_url: null, // For previewing selected image
    till_date: null, // New field for promotion till date
    title: '', // New field for promotion title
});

const viewTitle = computed(() => {
    let action = '';
    if (editingItem.value) {
        action = 'Edit';
    } else if (isCreating.value) {
        action = 'Create New';
    }

    if (activeTab.value === 'quotes') {
        return `${action} Quote`;
    } else if (activeTab.value === 'promotions') {
        return `${action} Promotion`;
    }
    return '';
});

const changeTab = (tab) => {
    activeTab.value = tab;
    cancelAction(); // Reset form and editing state when changing tabs
};

const startCreate = () => {
    isCreating.value = true;
    editingItem.value = null;
    form.reset();
    form.clearErrors();
    // Set default values based on active tab
    if (activeTab.value === 'promotions') {
        form.promotion_type = 'text';
        form.is_active = false;
        form.till_date = null; // Set default for till_date when creating
    } else if (activeTab.value === 'quotes') {
        form.is_active = false;
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
        form.promotion_type = item.promotion_type;
        form.text_content = item.text_content;
        form.image_url = item.image_url; // Assuming image_url is returned for existing posters
        form.is_active = !!item.is_active;
        form.till_date = item.till_date; // Populate till_date for editing
        form.title = item.title; // Populate title for editing
    }
};

const cancelAction = () => {
    isCreating.value = false;
    editingItem.value = null;
    form.reset();
    form.clearErrors();
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
            router.post(`/admin/marketing/${editingItem.value.id}`, {
                _method: 'put',
                content: form.content,
                author: form.author,
                is_active: form.is_active,
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    cancelAction();
                    Swal.fire('Updated!', 'The quote has been updated.', 'success');
                },
                onError: (errors) => {
                    console.error("Error updating quote:", errors);
                    Swal.fire('Error!', 'There was a problem updating the quote.', 'error');
                }
            });
        } else if (isCreating.value) {
            form.post('/admin/marketing', {
                onSuccess: () => {
                    cancelAction();
                    Swal.fire('Created!', 'The quote has been created.', 'success');
                },
                onError: (errors) => {
                    console.error("Error creating quote:", errors);
                    Swal.fire('Error!', 'There was a problem creating the quote.', 'error');
                }
            });
        }
    } else if (activeTab.value === 'promotions') {
        // Logic for promotions
        const formData = new FormData();
        formData.append('_method', editingItem.value ? 'put' : 'post');
        formData.append('promotion_type', form.promotion_type);
        formData.append('is_active', form.is_active ? 1 : 0);
        if (form.till_date) {
            formData.append('till_date', form.till_date);
        }
        if (form.title) {
            formData.append('title', form.title);
        }

        if (form.promotion_type === 'text') {
            formData.append('text_content', form.text_content);
        } else if (form.promotion_type === 'poster' && form.poster_image) {
            formData.append('poster_image', form.poster_image);
        }

        const url = editingItem.value ? `/admin/promotions/${editingItem.value.id}` : '/admin/promotions';

        router.post(url, formData, {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                cancelAction();
                Swal.fire('Success!', 'Promotion saved successfully.', 'success');
            },
            onError: (errors) => {
                console.error("Error saving promotion:", errors);
                Swal.fire('Error!', 'There was a problem saving the promotion.', 'error');
            }
        });
    }
};

const toggleStatus = (item) => {
    if (activeTab.value === 'quotes') {
        router.post(`/admin/marketing/${item.id}/toggle-status`, { _method: 'put' }, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire('Status Updated!', 'The quote status has been toggled.', 'success');
            },
            onError: (errors) => {
                console.error("Error toggling quote status:", errors);
                Swal.fire('Error!', 'There was a problem toggling the quote status.', 'error');
            }
        });
    } else if (activeTab.value === 'promotions') {
        router.post(`/admin/promotions/${item.id}/toggle-status`, { _method: 'put' }, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire('Status Updated!', 'The promotion status has been toggled.', 'success');
            },
            onError: (errors) => {
                console.error("Error toggling promotion status:", errors);
                Swal.fire('Error!', 'There was a problem toggling the promotion status.', 'error');
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
            const url = activeTab.value === 'quotes' ? `/admin/marketing/${id}` : `/admin/promotions/${id}`;
            router.delete(url, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(
                        'Deleted!',
                        'The item has been deleted.',
                        'success'
                    );
                },
                onError: () => {
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting the item.',
                        'error'
                    );
                }
            });
        }
    });
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
