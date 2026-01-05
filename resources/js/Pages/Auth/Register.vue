<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const step = ref(1);

const nextStep = () => {
    step.value++;
};

const prevStep = () => {
    step.value--;
};

const passwordFieldType = ref('password');

const togglePasswordVisibility = () => {
    passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password';
};

const profilePicturePreview = ref(null);
const profilePictureInput = ref(null);

// Separate form for profile picture upload
const profilePictureForm = useForm({
    profile_picture: null,
});

const selectProfilePicture = () => {
    profilePictureInput.value.click();
}

const onProfilePictureChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.profile_picture = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            profilePicturePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Auto-save profile picture when selected (only if user is authenticated)
        if (authUser.value) {
            saveProfilePicture(file);
        }
    }
};

// Auto-save profile picture
const saveProfilePicture = (file) => {
    if (!authUser.value || !file) return;
    
    profilePictureForm.profile_picture = file;
    profilePictureForm.post('/profile/upload-picture', {
        preserveState: true,
        preserveScroll: true,
        only: ['auth'],
        forceFormData: true,
        onSuccess: (page) => {
            // Update preview with the new profile picture path from response or auth user
            const profilePic = page.props.auth?.user?.profile_picture || 
                             (page.props.profile_picture ? '/' + page.props.profile_picture : null);
            if (profilePic) {
                const profilePicPath = profilePic.startsWith('/') 
                    ? profilePic 
                    : '/' + profilePic;
                profilePicturePreview.value = profilePicPath;
            }
        },
    });
};


const form = useForm({
    name: '',
    email: '',
    password: '',
    phone_country_code: 'PK',
    phone_number: '',
    primary_learning_goal: '',
    preferred_topics: [],
    profile_picture: null,
    agree_to_terms: false,
});

onMounted(() => {
    if (authUser.value) {
        form.name = authUser.value.name || '';
        form.email = authUser.value.email || '';
        form.phone_number = authUser.value.phone_number || '';
        form.phone_country_code = authUser.value.phone_country_code || 'PK';
        form.primary_learning_goal = authUser.value.primary_learning_goal || '';
        form.preferred_topics = Array.isArray(authUser.value.preferred_topic_ids) 
            ? authUser.value.preferred_topic_ids 
            : [];
        
        // Load profile picture if exists
        if (authUser.value.profile_picture) {
            // Handle both cases: path with or without leading slash
            const profilePicPath = authUser.value.profile_picture.startsWith('/') 
                ? authUser.value.profile_picture 
                : '/' + authUser.value.profile_picture;
            profilePicturePreview.value = profilePicPath;
        }
    }
});

const submit = () => {
    // Transform form data - exclude profile_picture if no file is selected (it's optional)
    form.transform((data) => {
        const transformed = { ...data };
        
        // Don't send profile_picture if no file is selected (optional field)
        if (!form.profile_picture) {
            delete transformed.profile_picture;
        }
        
        return transformed;
    }).post('/register', {
        onFinish: () => {
            if (!authUser.value) {
                form.reset('password');
            }
            form.transform((data) => data);
        },
    });
};

const props = defineProps({
    topics: Array,
});

const searchTerm = ref('');
const visibleTopicsCount = ref(9);

const filteredTopics = computed(() => {
    if (!searchTerm.value) {
        return props.topics;
    }
    return props.topics.filter(topic =>
        topic.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const visibleTopics = computed(() => {
    return filteredTopics.value.slice(0, visibleTopicsCount.value);
});

const nameInitial = computed(() => {
    return form.name ? form.name.charAt(0).toUpperCase() : '';
});

function showMoreTopics() {
    visibleTopicsCount.value += 6;
}

function showLessTopics() {
    visibleTopicsCount.value = 9;
}

function toggleTopic(topicId) {
    const index = form.preferred_topics.indexOf(topicId);
    if (index === -1) {
        form.preferred_topics.push(topicId);
    } else {
        form.preferred_topics.splice(index, 1);
    }
    
    // Auto-save preferred topics when toggled (only if user is authenticated)
    if (authUser.value) {
        savePreferredTopics();
    }
}

// Debounce timers
let phoneNumberTimer = null;
let learningGoalTimer = null;

// Auto-save phone number
const savePhoneNumber = () => {
    if (!authUser.value || !form.phone_number) return;
    
    router.patch('/phone-number', {
        phone_number: form.phone_number,
        phone_country_code: form.phone_country_code,
    }, {
        preserveState: true,
        preserveScroll: true,
        only: [],
    });
};

// Auto-save primary learning goal
const savePrimaryLearningGoal = () => {
    if (!authUser.value || !form.primary_learning_goal) return;
    
    router.patch('/career-goal', {
        primary_learning_goal: form.primary_learning_goal,
    }, {
        preserveState: true,
        preserveScroll: true,
        only: [],
    });
};

// Auto-save preferred topics
const savePreferredTopics = () => {
    if (!authUser.value) return;
    
    router.patch('/preferred-topics', {
        preferred_topic_ids: form.preferred_topics,
    }, {
        preserveState: true,
        preserveScroll: true,
        only: [],
    });
};

// Watch phone number with debounce
watch(() => form.phone_number, (newValue) => {
    if (!authUser.value) return;
    
    if (phoneNumberTimer) {
        clearTimeout(phoneNumberTimer);
    }
    
    phoneNumberTimer = setTimeout(() => {
        if (newValue && newValue.trim() !== '') {
            savePhoneNumber();
        }
    }, 1000); // 1 second debounce
});

// Watch phone country code with debounce
watch(() => form.phone_country_code, (newValue) => {
    if (!authUser.value || !form.phone_number) return;
    
    if (phoneNumberTimer) {
        clearTimeout(phoneNumberTimer);
    }
    
    phoneNumberTimer = setTimeout(() => {
        if (form.phone_number && form.phone_number.trim() !== '') {
            savePhoneNumber();
        }
    }, 1000);
});

// Watch primary learning goal with debounce
watch(() => form.primary_learning_goal, (newValue) => {
    if (!authUser.value) return;
    
    if (learningGoalTimer) {
        clearTimeout(learningGoalTimer);
    }
    
    learningGoalTimer = setTimeout(() => {
        if (newValue && newValue.trim() !== '') {
            savePrimaryLearningGoal();
        }
    }, 1000); // 1 second debounce
});

const topicColors = ['#f5b014', '#448cff', '#9b59b6', '#34d399', '#ef4444', '#6366f1'];
const getTopicColor = (index) => topicColors[index % topicColors.length];
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="form-container">
            <div class="form-row" style="justify-content: center; margin-bottom: 1rem;">
                <div class="relative">
                    <div @click="selectProfilePicture" class="profile-picture-container">
                        <img v-if="profilePicturePreview" :src="profilePicturePreview" class="profile-picture-image" />
                        <span v-else class="profile-picture-initial">{{ nameInitial }}</span>
                    </div>
                    <div @click="selectProfilePicture" class="camera-icon-container">
                        <svg class="camera-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <input type="file" ref="profilePictureInput" @change="onProfilePictureChange" class="hidden" accept="image/*">
                </div>
            </div>
            <InputError class="form-error" :message="form.errors.profile_picture" style="text-align: center; margin-top: -1rem; margin-bottom: 1rem;" />
            <div class="flex flex-row justify-center items-center form-title">
                <h1>Step {{ step }} to 2</h1>
            </div>
            <h1 class="form-title">Empower Your Learning Journey</h1>
            <p class="form-subtitle">
                Welcome to MBM University. Please fill out the form below to start your journey and begin learning today.
            </p>

            <form @submit.prevent="submit" class="form-body" novalidate>
                <div v-if="step === 1">
                    <!-- Row 1: Name and Email for new users -->
                    <div class="form-row">
                        <div class="form-group input_box_signup" :class="{ 'form-group-error': form.errors.name }">
                            <InputLabel for="name" value="Name" class="form-label" />
                            <TextInput
                                id="name"
                                type="text"
                                class="form-input input_box_outline"
                                v-model="form.name"
                                
                                autofocus
                                autocomplete="name"
                                :disabled="!!authUser"
                            />
                            <InputError class="form-error" :message="form.errors.name" />
                        </div>
                        <div class="form-group input_box_signup" :class="{ 'form-group-error': form.errors.email }">
                            <InputLabel for="email" value="Email" class="form-label" />
                            <TextInput
                                id="email"
                                type="email"
                                class="form-input input_box_outline"
                                v-model="form.email"
                                
                                autocomplete="username"
                                :disabled="!!authUser"
                            />
                            <InputError class="form-error" :message="form.errors.email" />
                        </div>
                    </div>
                    <div class="form-row">
                         <div class="form-group input_box_signup" :class="{ 'form-group-error': form.errors.phone_number }">
                            <InputLabel for="phone_number" value="Phone Number" class="form-label" style="margin-bottom: 0px; margin-top: 0px;"/>
                            <div class="phone-input-group">
                                <select v-model="form.phone_country_code" class="form-input country-code-select">
                                    <option value="PK">PK</option>
                                    <option value="US">US</option>
                                    <option value="UK">UK</option>
                                    <option value="CA">CA</option>
                                    <option value="AU">AU</option>
                                    <option value="NZ">NZ</option>
                                    <option value="ZA">ZA</option>
                                    <option value="IN">IN</option>
                                </select>
                                <TextInput
                                    id="phone_number"
                                    type="tel"
                                    class="form-input phone-number-input input_box_outline"
                                    v-model="form.phone_number"
                                    placeholder="0301 1234857"
                                    
                                    autocomplete="tel-national"
                                    style="border: none;"
                                />
                            </div>
                            <InputError class="form-error" :message="form.errors.phone_number" />
                        </div>
                    </div>

                    <!-- Row 3: Password for new users -->
                    <div v-if="!authUser" class="form-row">
                        <div class="form-group input_box_signup" :class="{ 'form-group-error': form.errors.password }">
                            <InputLabel for="password" value="Password" class="form-label" />
                            <div style="position: relative;">
                                <TextInput
                                    id="password"
                                    :type="passwordFieldType"
                                    class="form-input input_box_outline"
                                    v-model="form.password"
                                    
                                    autocomplete="new-password"
                                />
                                <span class="password-eye-icon" @click="togglePasswordVisibility"><img src="/images/view_icon.svg"/></span>
                            </div>
                            <InputError class="form-error" :message="form.errors.password" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Learning Goals and Topics -->
                <div v-if="step === 2">
                     
                    <!-- Row 4: Primary Learning Goal -->
                    <div class="form-row">
                        <div class="form-group input_box_signup" :class="{ 'form-group-error': form.errors.primary_learning_goal }">
                            <InputLabel for="primary_learning_goal" value="Primary Learning Goal" class="form-label" />
                            <TextInput
                                id="primary_learning_goal"
                                type="text"
                                class="form-input input_box_outline"
                                v-model="form.primary_learning_goal"
                                
                                autocomplete="off"
                            />
                            <InputError class="form-error" :message="form.errors.primary_learning_goal" />
                        </div>
                    </div>

                    <!-- Row 6: Preferred Topics -->
                    <div class="form-row">
                        <div class="form-group" :class="{ 'form-group-error': form.errors.preferred_topics }">
                            <InputLabel value="Preferred Topics" class="form-label" style="margin-bottom: 10px; margin-left: 0; background-color: none !important; background: none !important; color: #ffffff;" />
                            <div class="topics-container">
                                <div class="topics-search-container">
                                    <svg aria-hidden="true" class="search-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <input v-model="searchTerm" class="topics-search-input" placeholder="Find a role" />
                                </div>

                                <div class="topics-grid">
                                    <div v-for="(topic, index) in visibleTopics"
                                        :key="topic.id"
                                        class="topic-card"
                                        :class="{ 'selected': form.preferred_topics.includes(topic.id) }"
                                        @click="toggleTopic(topic.id)">
                                        <div class="flex flex-row topic-card-content">
                                            <div class="topic-icon">
                                                <img v-if="topic.logo_url" :src="topic.logo_url" :alt="topic.name" class="topic-logo-image"/>
                                                <svg v-else-if="index % 3 === 0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                                <svg v-else-if="index % 3 === 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                            </div>
                                            <span class="topic-name">{{ topic.name }}</span>
                                        </div>
                                        
                                        <span class="topic-add-icon">{{ form.preferred_topics.includes(topic.id) ? '✓' : '+' }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-row">
                                    <a v-if="!searchTerm && visibleTopicsCount < filteredTopics.length" href="#" class="view-more-roles" @click="showMoreTopics">+ View more topics</a>
                                    <a v-if="!searchTerm && visibleTopicsCount > 9" href="#" class="view-more-roles" @click="showLessTopics">- View less topics</a>
                                </div>
                            </div>
                            <InputError class="form-error" :message="form.errors.preferred_topics" />
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group terms-group">
                        <input
                            type="checkbox"
                            id="agree_terms"
                            v-model="form.agree_to_terms"
                            
                            class="form-checkbox"
                        />
                        <label for="agree_terms" class="terms-label">
                            I agree to the <a href="/privacy-policy" target="_blank" rel="noopener noreferrer" class="form-link">Privacy Policy</a> & <a href="/terms-of-services" target="_blank" rel="noopener noreferrer" class="form-link">Terms Of Services</a>
                        </label>
                        <InputError class="form-error" :message="form.errors.agree_to_terms" />
                    </div>
                </div>


                <!-- Buttons -->
                <div class="form-actions">
                    <Link
                        v-if="authUser"
                        href="/logout"
                        method="post"
                        as="button"
                        type="button"
                        class="login-button-link logout-button"
                    >
                        Logout
                    </Link>
                    <button
                        v-if="step > 1"
                        type="button"
                        @click.prevent="prevStep"
                        class="login-button-link"
                    >
                        Back
                    </button>
                    <PrimaryButton
                        v-if="step < 2"
                        @click.prevent="nextStep"
                        class="submit-button"
                        style="background-color: #1898e5;"
                    >
                        Next
                    </PrimaryButton>
                    <PrimaryButton
                        v-if="step === 2"
                        class="submit-button"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        style="background-color: #1898e5;"
                    >
                        {{ authUser ? 'Welcome to MBM University' : 'Sign Up' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 40px; /* Added more horizontal padding */
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif; /* Modern sans-serif font stack */
    background-color: transparent;
    max-width: 950px; /* Increased max-width */
    margin: 2rem auto; /* Centering and margin */
    margin-top: 0px;
}

.form-title {
    font-size: 28px; /* Adjusted as per image */
    font-weight: 600; /* Semi-bold */
    color: #ffffff; /* Darker gray */
    margin-bottom: 8px; /* Adjusted margin */
    text-align: left;
    width: 100%;
}

.form-subtitle {
    font-size: 14px;
    color: #c9c9c9; /* Medium gray */
    margin-bottom: 30px;
    text-align: left;
    width: 100%;
}

.form-body {
    width: 100%;
    text-align: start;
}

.form-row {
    display: flex;
    gap: 25px; /* Gap between columns */
    margin-bottom: 20px; /* Space between rows */
}

.form-group {
    flex: 1; /* Each group takes equal space in a row */
    display: flex;
    flex-direction: column;
}
.input_box_signup{
    border: 1px solid #D1D5DB;
    border-left: 4px solid #9CA3AF;
    border-radius: 6px;
    justify-content: center;
    background-color: #ffffff;
    display: flex;
}

.form-label {
    display: block;
    
    margin-top: 1px;
    font-size: 14px;
    font-weight: 500;
    color: #000000; /* Slightly lighter than title */
    margin-left: 12px;
    background-color: #ffffff;
}

.form-input,
.form-select {
    width: 100%;
    padding: 10px 12px;
    /* border: 1px solid #D1D5DB; Light gray border */
    /* border-radius: 6px; */
    font-size: 14px;
    color: #000000;
    background-color: #ffffff !important;
    line-height: 1.5;
    /* border-left: 4px solid #9CA3AF; Prominent left border as in image */
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border: none;
    padding-top: 0px;
}

.form-input:focus,
.form-select:focus {
    /* Indigo focus color */
    border: none;
    outline: 0;
    box-shadow: none;
}

.form-select {
    appearance: none; /* Remove default arrow */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236B7280'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem; /* Make space for custom arrow */
}

.password-eye-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6B7280;
    cursor: pointer;
}

.password-rules {
    margin-top: 8px;
    font-size: 0.8rem; /* Smaller text for rules */
    color: #EF4444; /* Red color for errors/rules */
    display: grid; /* Use grid for two columns of rules if needed */
    grid-template-columns: repeat(2, 1fr); /* Two columns */
    gap: 4px 8px; /* Gap between rules */
}
.password-rules span {
    display: flex;
    align-items: center;
}
.rule-cross {
    margin-right: 4px;
    font-weight: bold;
}


.phone-input-group {
    display: flex;
    align-items: center;
}

.country-code-select {
    width: auto; /* Adjust width automatically */
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none; /* Remove right border to merge with text input */
    padding-right: 0.5rem; /* Adjust padding for flag */
}

.phone-number-input {
    flex-grow: 1;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 1px solid #D1D5DB; /* Restore left border as it was removed by general .form-input */
}
/* Specific override for phone number input's left border because of grouping */
.phone-input-group .form-input.phone-number-input {
    
}
.phone-input-group .form-input.country-code-select {
     /* border-left: 4px solid #9CA3AF; Ensure prominent left border on select too */
}


.terms-group {
    margin-top: 40px; /* Reduced top margin */
    margin-bottom: 40px; /* Space before buttons */
    flex-direction: row;
    align-items: center;
}

.form-checkbox {
    width: 16px;
    height: 16px;
    margin-right: 8px;
    border-radius: 4px;
    border: 1px solid #D1D5DB;
    accent-color: #000000; /* Indigo for checkbox */
    padding: 10px;

}
.form-checkbox:checked {
    background-color: #000000; /* Indigo */
    border-color: #000000; /* Indigo */
    outline: none;
    box-shadow: none;
}
.form-checkbox[type="checkbox"]:focus,
.form-checkbox[type="checkbox"]:focus-visible {
    outline: none;
    border: none;
    box-shadow: none;
}

.terms-label {
    font-size: 14px;
    color: #ffffff;
    font-weight: normal; /* Normal weight for terms label */
    margin-bottom: 0px;
}

.form-link {
    color: #4F46E5; /* Indigo */
    text-decoration: none;
}
.form-link:hover {
    text-decoration: underline;
}

.form-error {
    margin-top: 6px;
    font-size: 0.875em;
    color: #EF4444; /* Red for errors */
}

.form-actions {
    display: flex;
   gap: 20px; /* Space between buttons */
    align-items: center;
    justify-content: flex-end;
    margin-top: 10px;
}

.submit-button,
.login-button-link {
    padding: 12px 25px; /* Larger padding */
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.submit-button {
    background-color: #868e96; /* Muted gray from image */
    color: white;
    border: 1px solid #868e96; /* Matching border */
}

.submit-button:hover {
    background-color: #6c757d; /* Darker gray on hover */
    border-color: #6c757d;
}

.submit-button.opacity-25 {
    opacity: 0.5; /* More visible opacity for disabled */
    cursor: not-allowed;
}


.login-button-link {
    background-color: #FFFFFF; /* White background */
    color: #4B5563; /* Dark gray text */
    border: 1px solid #D1D5DB; /* Light gray border */
    text-decoration: none; /* Remove underline from Link */
        padding: 9px 35px;
}

.login-button-link:hover {
    background-color: #F9FAFB; /* Slightly off-white on hover */
    border-color: #9CA3AF; /* Slightly darker border on hover */
    color: #1F2937; /* Darker text on hover */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column; /* Stack columns on smaller screens */
        gap: 0; /* Remove gap when stacked, rely on form-group margin */
    }
    .form-group {
        margin-bottom: 20px; /* Add margin back for stacked items */
    }
    .form-row .form-group:last-child {
        margin-bottom: 0; /* No bottom margin for the last item in a stacked row */
    }
    .password-rules {
        grid-template-columns: 1fr; /* Single column for password rules on small screens */
    }
    .form-actions {
        /* Stack buttons */
        gap: 15px; /* Add gap between stacked buttons */
    }
    .submit-button,
    .login-button-link {
        width: 100%; /* Full width buttons when stacked */
    }
    .form-container {
        padding: 20px;
    }
}
.input_box_outline:focus {
    border: none; /* Indigo focus color */
    outline: 0;
    box-shadow: none;

}

.file-input {
    padding: 8px;
    border-radius: 4px;
    padding-top: 5px;
    /* border: 1px solid #ddd; */
}
.file-input:focus {
    outline: none;
    /* border-color: #4F46E5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); */
}
.form-input.file-input {
    /* border-left: 4px solid #9CA3AF; */
}

.form-group-error {
    border: 1px solid #EF4444;
    border-left: 4px solid #EF4444;
}
.shadow-sm{
    box-shadow: none !important;
}

.topics-container {
    position: relative;
    width: 100%;
    padding: 2px 2px;
    /* min-height: 38px;
    max-height: 120px;
    overflow-y: auto; */
}

.topics-search-container {
    display: flex;
    align-items: center;
    padding: 5px;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    border-left: 4px solid #9CA3AF;
    background-color: #ffffff;
}

.search-icon {
    width: 16px;
    height: 16px;
    margin-right: 8px;
    color: #000000;
}

.topics-search-input {
    flex-grow: 1;
    border: none;
    outline: none;
    padding: 5px;
    font-size: 14px;
    min-width: 120px;
    max-width: 100%;
    background-color: #ffffff;
    color: #000000;
}

.topics-search-input:focus {
    outline: none;
    border: none;
    box-shadow: none;
}

.topics-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    min-height: 38px;
    margin-top: 10px;
    padding-top: 0px;
    width: 100%;
}

.topic-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 8px;
    justify-content: space-between;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    cursor: pointer;
    width: calc(33.33% - 10px);
}
.topic-card:hover {
    transform: scale(1.05);
    transition: transform 0.4s ease-in-out;
}

.topic-icon {
    width: 30px;
    height: 30px;
    justify-content: center;
    align-items: center;
    display: flex;
    background-color: transparent !important;
}

.topic-logo-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.topic-name {
    font-size: 18px;
    font-weight: 500;
    color: #ffffff;
    margin-left: 10px;
    justify-content: center;
    align-items: center;
    display: flex;
}

.topic-add-icon {
    font-size: 12px;
    font-weight: 500;
    color: #ffffff;
}

.view-more-roles {
    display: block;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #4F46E5;
    text-decoration: none;
    cursor: pointer;
}
.topic-card-content {
   
    align-items: center;
    
}

.selected {
    background-color: #3a6fd7;
}

.profile-picture-container {
    width: 8rem; 
    height: 8rem;
    background-color: #4CAF50; 
    border-radius: 9999px; 
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    position: relative;
    color: white;
}

.profile-picture-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-picture-initial {
    font-size: 4rem;
    font-weight: bold;
}

.camera-icon-container {
    position: absolute;
    bottom: 0.5rem; 
    right: 0.5rem; 
    background-color: white;
    border-radius: 9999px;
    padding: 0.5rem; 
    cursor: pointer;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.camera-icon {
    width: 1.5rem;
    height: 1.5rem;
    color: #4A5568; 
}

.hidden {
    display: none;
}

select{
    background-image: none !important;
}
</style>