<template>
    <Head title="Contact Us" />

    <component :is="layout">
        <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 contact-page-background">
            <div class="max-w-2xl mx-auto">
                <div class="contact-form-container">
                    <h1 class="text-3xl font-bold mb-6 contact-title">
                        Contact us.
                    </h1>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium mb-2 contact-label">
                                    First name
                                </label>
                                <input
                                    type="text"
                                    id="first_name"
                                    v-model="form.first_name"
                                    required
                                    class="w-full px-4 py-2 border rounded-md contact-input"
                                    placeholder="Jane"
                                />
                                <p v-if="errors.first_name" class="text-red-500 text-sm mt-1">{{ errors.first_name }}</p>
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium mb-2 contact-label">
                                    Last name
                                </label>
                                <input
                                    type="text"
                                    id="last_name"
                                    v-model="form.last_name"
                                    required
                                    class="w-full px-4 py-2 border rounded-md contact-input"
                                    placeholder="Smith"
                                />
                                <p v-if="errors.last_name" class="text-red-500 text-sm mt-1">{{ errors.last_name }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-2 contact-label">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                v-model="form.email"
                                required
                                class="w-full px-4 py-2 border rounded-md contact-input"
                                placeholder="jane@educate.io"
                            />
                            <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium mb-2 contact-label">
                                How can we help?
                            </label>
                            <textarea
                                id="message"
                                v-model="form.message"
                                required
                                rows="5"
                                class="w-full px-4 py-2 border rounded-md contact-input resize-y"
                                placeholder="Describe your problem"
                            ></textarea>
                            <p v-if="errors.message" class="text-red-500 text-sm mt-1">{{ errors.message }}</p>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-8 py-3 font-semibold rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed contact-submit-btn"
                            >
                                <span v-if="form.processing">Submitting...</span>
                                <span v-else>Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const layout = computed(() => user.value ? AuthenticatedLayout : GuestLayout);

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    message: '',
});

const errors = ref({});

const submitForm = () => {
    form.post(route('contact.submit'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Message Sent!',
                text: 'Thank you for contacting us. We will get back to you soon.',
                timer: 3000,
                showConfirmButton: false,
            });
            form.reset();
        },
        onError: (err) => {
            if (err.errors) {
                errors.value = err.errors;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error sending your message. Please try again.',
            });
        },
    });
};
</script>

<style scoped>
.contact-page-background {
    /* background: linear-gradient(115deg, #102548 30%, #004c8d 65%, #009ada 100%);
    min-height: 100vh; */
}

/* Light theme styles */
.contact-form-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.contact-form-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #102548 0%, #004c8d 50%, #009ada 100%);
    border-radius: 20px 20px 0 0;
}

.contact-form-container:hover {
    transform: translateY(-5px);
    box-shadow: 
        0 25px 70px rgba(0, 0, 0, 0.35),
        0 0 0 1px rgba(255, 255, 255, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.7);
}

.contact-title {
    color: #000000;
    font-size: 2.5rem;
    letter-spacing: -0.02em;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
    transition: color 0.3s ease;
}

.contact-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #102548 0%, #004c8d 50%, #009ada 100%);
    border-radius: 2px;
}

.contact-label {
    color: #000000;
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.01em;
    margin-bottom: 0.5rem;
    display: block;
    transition: color 0.3s ease;
}

.contact-input {
    background-color: #ffffff;
    border: 1.5px solid #e0e0e0;
    color: #000000;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.contact-input:hover {
    border-color: #cccccc;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.contact-input:focus {
    outline: none;
    border: 2px solid #004c8d;
    box-shadow: 
        0 0 0 4px rgba(0, 76, 141, 0.1),
        0 4px 12px rgba(0, 0, 0, 0.1);
    background-color: #fafafa;
}

.contact-input::placeholder {
    color: #999999;
    opacity: 0.8;
}

.contact-submit-btn {
    background: linear-gradient(115deg, #102548 30%, #004c8d 65%, #009ada 100%);
    color: #ffffff;
    box-shadow: 
        0 4px 15px rgba(0, 76, 141, 0.3),
        0 2px 5px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.contact-submit-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.contact-submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    background: linear-gradient(115deg, #1a3a5c 30%, #005ba3 65%, #00b4f0 100%);
    box-shadow: 
        0 6px 20px rgba(0, 76, 141, 0.4),
        0 3px 8px rgba(0, 0, 0, 0.3);
}

.contact-submit-btn:hover:not(:disabled)::before {
    left: 100%;
}

.contact-submit-btn:active:not(:disabled) {
    transform: translateY(0);
    box-shadow: 
        0 2px 8px rgba(0, 76, 141, 0.3),
        0 1px 3px rgba(0, 0, 0, 0.2);
}

/* Dark mode support */
.dark .contact-form-container {
    background: linear-gradient(135deg, #1A2C38 0%, #0F212E 100%);
    box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.5),
        0 0 0 1px rgba(255, 255, 255, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.dark .contact-form-container:hover {
    box-shadow: 
        0 25px 70px rgba(0, 0, 0, 0.6),
        0 0 0 1px rgba(255, 255, 255, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
}

.dark .contact-title {
    color: #ffffff;
}

.dark .contact-label {
    color: #e0e0e0;
}

.dark .contact-input {
    background-color: #2d2d2d;
    border: 1.5px solid #404040;
    color: #ffffff;
}

.dark .contact-input:hover {
    border-color: #505050;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.dark .contact-input:focus {
    border: 2px solid #38B6FF;
    box-shadow: 
        0 0 0 4px rgba(56, 182, 255, 0.2),
        0 4px 12px rgba(0, 0, 0, 0.3);
    background-color: #353535;
}

.dark .contact-input::placeholder {
    color: #888888;
    opacity: 0.8;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .contact-form-container {
        padding: 2rem 1.5rem;
        border-radius: 16px;
    }
    
    .contact-title {
        font-size: 2rem;
    }
}

/* Animation for form appearance */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.contact-form-container {
    animation: fadeInUp 0.6s ease-out;
}
</style>
