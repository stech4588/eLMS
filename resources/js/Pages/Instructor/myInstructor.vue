<template>
    <Head title="Become an Instructor" />

    
        

        <div class="instructor-page-container">
            <div class="instructor-page-wrapper">
                <div class="instructor-card">
                    <div class="instructor-card-header">
                     <a :href="user ? '/dashboard' : '/'">   
                        <img src="/images/MBM_Uni.png" alt="logo" class="" style=" width: 100px; height: 100px;filter: grayscale(100%) brightness(0);">
                        </a>
                    </div>
                    <div class="instructor-content-container" style="text-align: start;">

                        <!-- Left Column -->
                        <!-- <div class="instructor-left-column">
                            <div style="display: flex; flex-direction: column; gap: 40px;">
                                 <h1 class="instructor-main-heading">Become a MBM Learning Instructor</h1>
                            <p class="instructor-text">Do you have a passion for teaching and expertise in your field? Join the global community of MBM Learning Instructors and share your knowledge with millions of learners worldwide.</p>
                            </div>
                           
                            <p class="instructor-text" style="margin-bottom: 1rem;">At MBM Learning, we believe that great education starts with great instructors. As a subject matter expert, you'll have the opportunity to:</p>
                            <ul class="instructor-list">
                                <li>Teach what you love and reach a global audience.</li>
                                <li>Collaborate with a world-class production team.</li>
                                <li>Create impactful, high-quality learning content.</li>
                                <li>Grow your personal brand and influence in your industry.</li>
                            </ul>

                            <h2 class="instructor-subheading">About Working With us:</h2>
                            <p class="instructor-text">
                                Working with MBM Learning goes beyond simply recording courses—it's a collaborative experience where you'll team up with an expert group dedicated to helping you refine your content and message. They'll guide you in delivering your knowledge in an engaging, easy-to-digest format while ensuring your course connects with the right learners at the perfect time. <a href="#" class="instructor-link">Here</a>
                            </p>

                            <div class="instructor-quote">
                                "As an instructor, you're passionate about your subject matter. The thing I love about working with MBM is, they help you bring it to life in really exciting ways."
                                <p class="instructor-quote-author">—Lisa Earle McLeod, Sales Leadership Consultant & MBM Learning Instructor</p>
                            </div>

                            <h2 class="instructor-subheading">Apply Now!</h2>
                            <p class="instructor-text">
                                If you're ready to inspire, educate, and make an impact, we'd love to hear from you.

📩 Start your journey with MBM Learning today. Apply to become an instructor! <a href="#" class="instructor-link">Here</a>
                            </p>
                        </div> -->

                        <!-- Right Column -->
                        <div class="instructor-right-column">
                            <h2 class="instructor-form-heading">Instructor Application</h2>
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
                            <div v-if="form.errors.profile_picture" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem; text-align: center; margin-top: -1rem; margin-bottom: 1rem;">{{ form.errors.profile_picture }}</div>
                            <!-- <button class="instructor-linkedin-btn">AutoFill with LinkedIn</button> -->

                            <form @submit.prevent="submit">
                                <div class="instructor-form-group" style="margin-top: 10px;">
                                    <input type="text" class="instructor-form-input" placeholder="Name*" v-model="form.name" required>
                                </div>
                                <div v-if="form.errors.name" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.name }}</div>

                                <div class="instructor-form-group">
                                    <input type="text" class="instructor-form-input" placeholder="Phone number*" v-model="form.phone_number" required>
                                </div>
                                <div v-if="form.errors.phone_number" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.phone_number }}</div>

                                <div class="instructor-form-group">
                                    <input type="email" class="instructor-form-input" placeholder="Email*" v-model="form.email" required>
                                </div>
                                <div v-if="form.errors.email" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.email }}</div>

                                <div class="instructor-form-group" style="position: relative;">
                                    <input :type="showPassword ? 'text' : 'password'" class="instructor-form-input" placeholder="Password*" v-model="form.password" required>
                                    <img src="/images/view_icon.svg" alt="Toggle visibility" class="view-icon" @click="togglePassword" />
                                </div>
                                <div v-if="form.errors.password" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.password }}</div>

                                <div class="instructor-form-group" style="position: relative;">
                                    <input :type="showPasswordConfirmation ? 'text' : 'password'" class="instructor-form-input" placeholder="Confirm Password*" v-model="form.password_confirmation" required>
                                    <img src="/images/view_icon.svg" alt="Toggle visibility" class="view-icon" @click="togglePasswordConfirmation" />
                                </div>
                                <!-- No separate error for password_confirmation, usually covered by password 'confirmed' rule -->

                                <div class="instructor-form-group">
                                    <input type="url" class="instructor-form-input" placeholder="LinkedIn Profile URL*" v-model="form.linkedin_url" required>
                                </div>
                                <div v-if="form.errors.linkedin_url" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.linkedin_url }}</div>

                                <div class="instructor-form-group">
                                    <select class="instructor-form-select" v-model="form.followers" required>
                                        <option :value="null" disabled>How Many Followers do you have?</option>
                                        <option value="0-1,000">0-1,000</option>
                                        <option value="1,001-10,000">1,001-10,000</option>
                                        <option value="10,001-50,000">10,001-50,000</option>
                                        <option value="50,001-100,000">50,001-100,000</option>
                                        <option value="100,000+">100,000+</option>
                                    </select>
                                </div>
                                <div v-if="form.errors.followers" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.followers }}</div>

                                <div class="instructor-form-group" style="margin-top: 30px;">
                                    <label class="instructor-form-label">Are you a part of any of the following LinkedIn programs? <span class="instructor-form-note">(Please check all that apply.)</span></label>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="li_influencer" class="instructor-checkbox" value="LinkedIn Influencer" v-model="form.linkedin_programs"> <label for="li_influencer" class="instructor-checkbox-label">LinkedIn Influencer</label></div>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="managed_creator" class="instructor-checkbox" value="Managed Power Creator" v-model="form.linkedin_programs"> <label for="managed_creator" class="instructor-checkbox-label">Managed Power Creator</label></div>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="top_voice" class="instructor-checkbox" value="Top Voice" v-model="form.linkedin_programs"> <label for="top_voice" class="instructor-checkbox-label">Top Voice</label></div>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="other_linkedin" class="instructor-checkbox" value="Other LinkedIn" v-model="form.linkedin_programs"> <label for="other_linkedin" class="instructor-checkbox-label">Other (working with LinkedIn in any other capacity)</label></div>
                                </div>
                                <div v-if="form.errors.linkedin_programs" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.linkedin_programs }}</div>

                                <div class="instructor-form-group" style="margin-top: 30px;">
                                    <label class="instructor-form-label">Which language would you like to teach a course in? <span class="instructor-form-note">(Please select one.) is a required field*</span></label>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_eng" class="instructor-radio" value="Eng" v-model="form.teaching_language"> <label for="lang_eng" class="instructor-radio-label">Eng</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_french" class="instructor-radio" value="French" v-model="form.teaching_language"> <label for="lang_french" class="instructor-radio-label">French</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_spanish" class="instructor-radio" value="Spanish" v-model="form.teaching_language"> <label for="lang_spanish" class="instructor-radio-label">Spanish</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_japanese" class="instructor-radio" value="Japanese" v-model="form.teaching_language"> <label for="lang_japanese" class="instructor-radio-label">Japanese</label></div>
                                </div>
                                <div v-if="form.errors.teaching_language" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.teaching_language }}</div>

                                <div class="instructor-form-group" style="margin-top: 30px;">
                                     <label class="instructor-form-label">Where did you hear of this opportunity? is a required field*</label>
                                    <div class="instructor-radio-group"><input type="checkbox" name="opportunity_source" id="source_search_engine" class="instructor-radio"> <label for="source_search_engine" class="instructor-radio-label">Search engine</label></div>
                                    <div class="instructor-radio-group"><input type="checkbox" name="opportunity_source" id="source_friend" class="instructor-radio"> <label for="source_friend" class="instructor-radio-label">Friend or co-worker</label></div>
                                    <div class="instructor-radio-group"><input type="checkbox" name="opportunity_source" id="source_social" class="instructor-radio"> <label for="source_social" class="instructor-radio-label">Social media (LinkedIn, Twitter, Facebook, Instagram)</label></div>
                                    <div class="instructor-radio-group"><input type="checkbox" name="opportunity_source" id="source_other" class="instructor-radio"> <label for="source_other" class="instructor-radio-label">Other</label></div>
                                </div>

                                <!-- Placeholder for repeated text inputs -->
                                <div class="instructor-form-group">
                                    
                                    <input type="text" class="instructor-form-input instructor-disabled-input" placeholder="Where did you hear of this opportunity? is a required field*">
                                </div>
                                 <!-- <div class="instructor-form-group">
                                    
                                    <input type="text" class="instructor-form-input instructor-disabled-input" placeholder="Where did you hear of this opportunity? is a required field*">
                                </div>
                                 <div class="instructor-form-group">
                                    
                                    <input type="text" class="instructor-form-input instructor-disabled-input" placeholder="Where did you hear of this opportunity? is a required field*">
                                </div>
                                 <div class="instructor-form-group">
                                   
                                    <input type="text" class="instructor-form-input instructor-disabled-input" placeholder="Where did you hear of this opportunity? is a required field*">
                                </div>
                                 <div class="instructor-form-group">
                                    
                                    <input type="text" class="instructor-form-input instructor-disabled-input" placeholder="Where did you hear of this opportunity? is a required field*">
                                </div> -->

                                <button type="submit" class="instructor-submit-btn" :disabled="form.processing">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

export default {
    components: {
        // AuthenticatedLayout, // Assuming this is not used for a public registration form
        Head,
        Link,
    },
    data() {
        return {
            profilePicturePreview: null,
            showPassword: false,
            showPasswordConfirmation: false,
            form: useForm({
                name: '',
                phone_number: '',
                email: '',
                password: '',
                password_confirmation: '',
                linkedin_url: '',
                followers: null, // Default to null for the select placeholder
                linkedin_programs: [], // Initialize as an empty array for checkboxes
                teaching_language: '', // Initialize as empty for radio buttons
                profile_picture: null,
            }),
        };
    },
    computed: {
        user() {
            return usePage().props.auth?.user;
        },
        nameInitial() {
            return this.form.name ? this.form.name.charAt(0).toUpperCase() : '';
        }
    },
    methods: {
        submit() {
            // Log form submission start
            console.log('=== Frontend: Instructor Registration Started ===');
            console.log('Form Data:', {
                name: this.form.name,
                email: this.form.email,
                phone_number: this.form.phone_number,
                linkedin_url: this.form.linkedin_url,
                followers: this.form.followers,
                linkedin_programs: this.form.linkedin_programs,
                teaching_language: this.form.teaching_language,
                has_profile_picture: !!this.form.profile_picture,
                profile_picture_size: this.form.profile_picture ? this.form.profile_picture.size : 0,
            });
            console.log('Form Processing State:', this.form.processing);
            console.log('Form Errors Before Submit:', this.form.errors);

            this.form.post('/instructor/register', {
                preserveScroll: false,
                onStart: () => {
                    console.log('=== Frontend: Request Started ===');
                    console.log('Form is processing:', this.form.processing);
                },
                onProgress: (event) => {
                    console.log('=== Frontend: Request Progress ===', {
                        progress: event.progress,
                        total: event.total,
                        percentage: event.progress?.percentage
                    });
                },
                onSuccess: (page) => {
                    console.log('=== Frontend: Request Success ===');
                    console.log('Response Status:', page?.status || 'unknown');
                    console.log('Response Data:', page);
                    console.log('Form Errors After Success:', this.form.errors);
                    console.log('Form Has Errors:', this.form.hasErrors);
                },
                onError: (errors) => {
                    console.error('=== Frontend: Request Error ===');
                    console.error('Error Object:', errors);
                    console.error('Form Errors:', this.form.errors);
                    console.error('Form Has Errors:', this.form.hasErrors);
                    console.error('Error Keys:', Object.keys(errors || {}));
                    
                    // Log each error individually
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            console.error(`Error [${key}]:`, errors[key]);
                        });
                    }

                    // Log validation errors
                    if (this.form.errors) {
                        console.error('Validation Errors:', JSON.stringify(this.form.errors, null, 2));
                    }
                },
                onFinish: () => {
                    console.log('=== Frontend: Request Finished ===');
                    console.log('Form Processing State:', this.form.processing);
                    console.log('Form Has Errors:', this.form.hasErrors);
                    console.log('Form Errors:', this.form.errors);
                    
                    // Only reset passwords if submission was successful (no errors)
                    if (!this.form.hasErrors) {
                        console.log('Resetting password fields');
                        this.form.reset('password', 'password_confirmation');
                    } else {
                        console.warn('Not resetting password fields due to errors');
                    }
                },
                onCancel: () => {
                    console.warn('=== Frontend: Request Cancelled ===');
                },
            }).catch((error) => {
                // Catch any unexpected errors
                console.error('=== Frontend: Unexpected Error ===');
                console.error('Error Type:', error?.constructor?.name);
                console.error('Error Message:', error?.message);
                console.error('Error Stack:', error?.stack);
                console.error('Full Error Object:', error);
                
                // Try to extract more details
                if (error?.response) {
                    console.error('Error Response:', error.response);
                    console.error('Error Response Status:', error.response?.status);
                    console.error('Error Response Data:', error.response?.data);
                }
                
                if (error?.request) {
                    console.error('Error Request:', error.request);
                }
            });
        },
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        togglePasswordConfirmation() {
            this.showPasswordConfirmation = !this.showPasswordConfirmation;
        },
        selectProfilePicture() {
            this.$refs.profilePictureInput.click();
        },
        onProfilePictureChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.form.profile_picture = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.profilePicturePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
    },
    mounted() {
        // lifecycle hooks
    }
};
</script>

<style >
/* Main layout */
.header-title {
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 600;
    color: #1f2937;
}

.instructor-page-container {
    min-height: 100vh;
    background-color: #f3f4f6;
    padding: 1.5rem 0;
}

.instructor-page-wrapper {
    /* max-width: 80rem;
    margin-left: auto;
    margin-right: auto; */
    width: 100%;
    
}

.instructor-card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.instructor-content-container {
    display: flex;
    padding: 1.5rem;
    justify-content: center;
}
@media (max-width: 768px) {
    .instructor-content-container {
        flex-direction: column;
    }
}

.instructor-card-header {
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: center;
}

/* Left column styles */
.instructor-left-column {
    flex: 1;
    padding-right: 2rem;
    color: #111827;
}

.instructor-main-heading {
    font-size: 40px;
    font-weight: 600;
    margin-bottom: 1rem;
    line-height: normal;
    color: #111827;
}

.instructor-text {
    margin-bottom: 2rem;
    color: #374151;
    line-height: normal;
}

.instructor-list {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1.5rem;
    color: #374151;
    line-height: normal;
}

.instructor-subheading {
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 2rem;
    margin-bottom: 0.5rem;
    color: #111827;
}

.instructor-link {
    color: #111827;
    text-decoration: underline;
}

.instructor-link:hover {
    color: #374151;
}

.instructor-quote {
    padding: 1rem;
    margin-bottom: 1.5rem;
    text-align: center;
    line-height: normal;
    color: #374151;
    background-color: #f9fafb;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.instructor-quote-author {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Right column styles */
.instructor-right-column {
    /* flex: 1; */
    padding-left: 1rem;
    width:50%;
    border: 1px solid #e5e7eb;
    padding: 1.5rem;
    border-radius: 0.375rem;
    background-color: #f9fafb;
}

.instructor-form-heading {
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
    color: #111827;
}

.instructor-linkedin-btn {
    background-color: #111827;
    color: #ffffff;
    padding: 0.5rem 1rem;
    margin-top: 20px;
    border: none;
    margin-bottom: 1.5rem;
    cursor: pointer;
    width: 50%;
    font-size: 17px;
    font-weight: 600;
}
.instructor-linkedin-btn:hover {
    background-color: #374151;
}

/* Form elements */
.instructor-form-group {
    margin-bottom: 1rem;
}

.instructor-form-label {
    display: block;
    margin-bottom: 0.25rem;
    color: #111827;
    font-size: 0.875rem;
}

.instructor-form-note {
    color: #6b7280;
}

.instructor-form-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    background-color: #ffffff;
    color: #111827;
}

.instructor-form-input::placeholder {
    color: #9ca3af;
    opacity: 1;
}

.instructor-form-input:focus {
    outline: none;
    border-color: #374151;
    box-shadow: 0 0 0 1px #374151;
    color: #111827;
}

.instructor-form-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    background-color: #ffffff;
    color: #111827;
}
.instructor-form-select option {
    color: #111827;
    background-color: #ffffff;
}
.instructor-form-select:focus {
    outline: none;
    border-color: #374151;
    box-shadow: none;
    color: #111827;
}

.instructor-checkbox-group {
    margin-bottom: 0.25rem;
}

.instructor-checkbox {
    margin-right: 0.5rem;
    background-color: #bebcbc00;
}

.instructor-checkbox-label {
    color: #374151;
    font-size: 0.875rem;
}

.instructor-radio-group {
    margin-bottom: 0.25rem;
}

.instructor-radio {
    margin-right: 0.5rem;
    background-color: #bebcbc00;
}
.instructor-radio[type="checkbox"] {
     /* #bebcbc00 is fully transparent */
    color: black;
    
}
.instructor-radio[type="checkbox"]:focus,
.instructor-radio[type="checkbox"]:focus-visible {
    
    box-shadow: none;
}
.instructor-checkbox[type="checkbox"] {
     /* #bebcbc00 is fully transparent */
    color: black;
    
}
.instructor-checkbox[type="checkbox"]:focus,
.instructor-checkbox[type="checkbox"]:focus-visible {
    
    box-shadow: none;
}

.instructor-radio-label {
    color: #374151;
    font-size: 0.875rem;
}

.instructor-disabled-input {
    
    
}

.instructor-submit-btn {
    background-color: #111827;
    color: #ffffff;
    padding: 7px 1.5rem;
    border-radius: 0.25rem;
    border: none;
    cursor: pointer;
    font-weight: bold;
}
.instructor-submit-btn:hover:not(:disabled) {
    background-color: #374151;
}
.home_page_style{
    padding: 0;
}

.relative {
    position: relative;
}

.profile-picture-container {
    width: 8rem;
    height: 8rem;
    background-color: #e5e7eb;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    position: relative;
    color: #374151;
    margin: 0 auto;
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
    background-color: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 9999px;
    padding: 0.5rem;
    cursor: pointer;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.camera-icon {
    width: 1.5rem;
    height: 1.5rem;
    color: #374151;
}

.hidden {
    display: none;
}
.view-icon {
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    cursor: pointer;
    width: 1.25rem;
    height: 1.25rem;
}
</style>