<template>
    <Head title="Become an Instructor" />

    
        

        <div class="instructor-page-container">
            <div class="instructor-page-wrapper">
                <div class="instructor-card">
                    <div style="border-bottom: 1px solid #414141;">
                     <a :href="user ? '/dashboard' : '/'">   
                        <img src="/images/MBM_Uni.png" alt="logo" class="" style="margin-left: 55px; width: 100px; height: 100px;">
                        </a>
                    </div>
                    <div class="instructor-content-container" style="text-align: start;">

                        <!-- Left Column -->
                        <div class="instructor-left-column">
                            <div style="display: flex; flex-direction: column; gap: 40px;">
                                 <h1 class="instructor-main-heading">Become a MBM Learning Instructor</h1>
                            <p class="instructor-text">Do you have a passion for teaching and expertise in your field? Join the global community of MBM Learning Instructors and share your knowledge with millions of learners worldwide.</p>
                            </div>
                           
                            <p class="instructor-text" style="margin-bottom: 1rem;">At MBM Learning, we believe that great education starts with great instructors. As a subject matter expert, you’ll have the opportunity to:</p>
                            <ul class="instructor-list">
                                <li>Teach what you love and reach a global audience.</li>
                                <li>Collaborate with a world-class production team.</li>
                                <li>Create impactful, high-quality learning content.</li>
                                <li>Grow your personal brand and influence in your industry.</li>
                            </ul>

                            <h2 class="instructor-subheading">About Working With us:</h2>
                            <p class="instructor-text">
                                Working with MBM Learning goes beyond simply recording courses—it’s a collaborative experience where you’ll team up with an expert group dedicated to helping you refine your content and message. They’ll guide you in delivering your knowledge in an engaging, easy-to-digest format while ensuring your course connects with the right learners at the perfect time. <a href="#" class="instructor-link">Here</a>
                            </p>

                            <div class="instructor-quote">
                                "As an instructor, you're passionate about your subject matter. The thing I love about working with MBM is, they help you bring it to life in really exciting ways."
                                <p class="instructor-quote-author">—Lisa Earle McLeod, Sales Leadership Consultant & MBM Learning Instructor</p>
                            </div>

                            <h2 class="instructor-subheading">Apply Now!</h2>
                            <p class="instructor-text">
                                If you're ready to inspire, educate, and make an impact, we’d love to hear from you.

📩 Start your journey with MBM Learning today. Apply to become an instructor! <a href="#" class="instructor-link">Here</a>
                            </p>
                        </div>

                        <!-- Right Column -->
                        <div class="instructor-right-column">
                            <h2 class="instructor-form-heading">Instructor Application</h2>
                            <button class="instructor-linkedin-btn">AutoFill with LinkedIn</button>

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

                                <div class="instructor-form-group">
                                    <input type="password" class="instructor-form-input" placeholder="Password*" v-model="form.password" required>
                                </div>
                                <div v-if="form.errors.password" class="instructor-form-error" style="color: red; font-size: 0.875em; margin-top: 0.25rem;">{{ form.errors.password }}</div>

                                <div class="instructor-form-group">
                                    <input type="password" class="instructor-form-input" placeholder="Confirm Password*" v-model="form.password_confirmation" required>
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

const user = usePage().props.auth?.user
export default {
    components: {
        // AuthenticatedLayout, // Assuming this is not used for a public registration form
        Head,
        Link,
    },
    data() {
        return {
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
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(route('instructor.register'), {
                onFinish: () => {
                    // Only reset passwords if submission was successful (no errors)
                    if (!this.form.hasErrors) {
                        this.form.reset('password', 'password_confirmation');
                    }
                },
            });
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
    
}

.instructor-page-wrapper {
    /* max-width: 80rem;
    margin-left: auto;
    margin-right: auto; */
    width: 100%;
    
}

.instructor-card {
    background-color: white;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    
}

.instructor-content-container {
    display: flex;
    padding: 1.5rem;
}
@media (max-width: 768px) {
    .instructor-content-container {
        flex-direction: column;
    }
}

/* Left column styles */
.instructor-left-column {
    flex: 1;
    padding-right: 2rem;
}

.instructor-main-heading {
    font-size: 40px;
    font-weight: 600;
    margin-bottom: 1rem;
    line-height: normal;
}

.instructor-text {
    margin-bottom: 2rem;
    color: black;
    line-height: normal;
}

.instructor-list {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1.5rem;
    color: black;
        line-height: normal;
}

.instructor-subheading {
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 2rem;
    margin-bottom: 0.5rem;
}

.instructor-link {
    color: #2C15F5;

}

.instructor-quote {
   
    padding: 1rem;
    margin-bottom: 1.5rem;
   text-align: center;
   line-height: normal;
}

.instructor-quote-author {
   
    
}

/* Right column styles */
.instructor-right-column {
    flex: 1;
    padding-left: 1rem;
    background-color: #BEBCBC4D;
    padding: 1.5rem;
    border-radius: 0.375rem;
}

.instructor-form-heading {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.instructor-linkedin-btn {
    background-color: #1898e5;
    color: white;
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
    background-color: #A0AEC0;
}

/* Form elements */
.instructor-form-group {
    margin-bottom: 1rem;
}

.instructor-form-label {
    display: block;
    margin-bottom: 0.25rem;
    color: black;
    font-size: 0.875rem;
}

.instructor-form-note {
    color: black;
}

.instructor-form-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #7E7E7E;
    background-color: #bebcbc00;

}

.instructor-form-input:focus {
  outline: none;
  border: 1px solid #424242; /* keep dark gray on focus */
  box-shadow: none;       /* remove any glow or highlight */
}

.instructor-form-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #7E7E7E;
    border-radius: 0.25rem;
    background-color: #bebcbc00;
}
.instructor-form-select:focus {
  outline: none;
  border: 1px solid #424242; /* keep dark gray on focus */
  box-shadow: none;       /* remove any glow or highlight */
}

.instructor-checkbox-group {
    margin-bottom: 0.25rem;
}

.instructor-checkbox {
    margin-right: 0.5rem;
    background-color: #bebcbc00;
}

.instructor-checkbox-label {
    color: #4D4D4D;
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
    color: #4D4D4D;
    font-size: 0.875rem;
}

.instructor-disabled-input {
    
    
}

.instructor-submit-btn {
    background-color: #1898e5;
    color: black;
    padding: 7px 1.5rem;
    border-radius: 0.25rem;
    border: none;
    cursor: pointer;
    font-weight: bold;
}
.home_page_style{
    padding: 0;
}
</style>