<template>
    <Head title="Become an Instructor" />

    
        

        <div class="instructor-page-container">
            <div class="instructor-page-wrapper">
                <div class="instructor-card">
                    <div class="instructor-content-container">

                        <!-- Left Column -->
                        <div class="instructor-left-column">
                            <div style="display: flex; flex-direction: column; gap: 40px;">
                                 <h1 class="instructor-main-heading">Become a MBM Learning Instructor</h1>
                            <p class="instructor-text">Do you have a passion for teaching and expertise in your field? Join the global community of MBM Learning Instructors and share your knowledge with millions of learners worldwide.</p>
                            </div>
                           
                            <p class="instructor-text" style="margin-bottom: 1rem;">Lorem ipsum dolor sit amet consectetur</p>
                            <ul class="instructor-list">
                                <li>Lorem ipsum dolor sit amet consectetur</li>
                                <li>Lorem ipsum dolor sit amet consectetur</li>
                                <li>Lorem ipsum dolor sit amet consectetur</li>
                                <li>Lorem ipsum dolor sit amet consecteturLorem ipsum dolor sit amet consectetur</li>
                            </ul>

                            <h2 class="instructor-subheading">About Working With us:</h2>
                            <p class="instructor-text">
                                Lorem ipsum dolor sit amet consectetur. Convallis aenaan sed aenean vel sem lorem amet neque tristique. Gravida neque sit neque nec. Blandit ac diam ornare orci. Amet porttitor lobortis amet nascetur tempor. Aliquam scelerisque tellus enim nisi dui interdum <a href="#" class="instructor-link">Here</a>
                            </p>

                            <div class="instructor-quote">
                                "As an instructor, you're passionate about your subject matter. The thing I love about working with MBM is, they help you bring it to life in really exciting ways."
                                <p class="instructor-quote-author">—Lisa Earle McLeod, Sales Leadership Consultant & MBM Learning Instructor</p>
                            </div>

                            <h2 class="instructor-subheading">Apply Now!</h2>
                            <p class="instructor-text">
                                Lorem ipsum dolor sit amet consectetur. Convallis aenaan sed aenean vel sem lorem amet neque tristique. Gravida neque sit neque nec. Blandit ac diam ornare orci. Amet porttitor lobortis amet nascetur tempor. Aliquam scelerisque tellus enim nisi dui interdum <a href="#" class="instructor-link">Here</a>
                            </p>
                        </div>

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
                            <button class="instructor-linkedin-btn">AutoFill with MBM</button>

                            <form @submit.prevent="submit">
                                <div class="instructor-form-group" style="margin-top: 10px;">
                                    
                                    <input type="text" v-model="form.first_name" class="instructor-form-input" placeholder="First Name*">
                                </div>
                                <div class="instructor-form-group">
                                    
                                    <input type="text" v-model="form.last_name" class="instructor-form-input" placeholder="Last Name*">
                                </div>
                                <div class="instructor-form-group">
                                    
                                    <input type="email" v-model="form.email" class="instructor-form-input" placeholder="Email*">
                                </div>
                                <div class="instructor-form-group">
                                    
                                    <input type="url" v-model="form.linkedin_url" class="instructor-form-input" placeholder="LinkedIn Profile URL*">
                                </div>
                                <div class="instructor-form-group" >
                                    
                                    <select class="instructor-form-select" v-model="form.followers">
                                        <option value="" disabled>How Many Followers do you have?</option>
                                        <option>0-1,000</option>
                                        <option>1,001-10,000</option>
                                        <option>10,001-50,000</option>
                                        <option>50,001-100,000</option>
                                        <option>100,000+</option>
                                    </select>
                                </div>

                                <div class="instructor-form-group" style="margin-top: 30px;">
                                    <label class="instructor-form-label">Are you a part of any of the following MBM programs? <span class="instructor-form-note">(Please check all that apply.) is a required field*</span></label>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="li_influencer" value="LinkedIn Influencer" v-model="form.mbm_programs" class="instructor-checkbox"> <label for="li_influencer" class="instructor-checkbox-label">LinkedIn Influencer</label></div>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="managed_creator" value="Managed Power Creator" v-model="form.mbm_programs" class="instructor-checkbox"> <label for="managed_creator" class="instructor-checkbox-label">Managed Power Creator</label></div>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="top_voice" value="Top Voice" v-model="form.mbm_programs" class="instructor-checkbox"> <label for="top_voice" class="instructor-checkbox-label">Top Voice</label></div>
                                    <div class="instructor-checkbox-group"><input type="checkbox" id="other_linkedin" value="Other" v-model="form.mbm_programs" class="instructor-checkbox"> <label for="other_linkedin" class="instructor-checkbox-label">Other (working with LinkedIn in any other capacity)</label></div>
                                </div>

                                <div class="instructor-form-group" style="margin-top: 30px;">
                                    <label class="instructor-form-label">Which language would you like to teach a course in? <span class="instructor-form-note">(Please select one.) is a required field*</span></label>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_eng" value="English" v-model="form.teaching_language" class="instructor-radio"> <label for="lang_eng" class="instructor-radio-label">Eng</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_french" value="French" v-model="form.teaching_language" class="instructor-radio"> <label for="lang_french" class="instructor-radio-label">French</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_spanish" value="Spanish" v-model="form.teaching_language" class="instructor-radio"> <label for="lang_spanish" class="instructor-radio-label">Spanish</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="language" id="lang_japanese" value="Japanese" v-model="form.teaching_language" class="instructor-radio"> <label for="lang_japanese" class="instructor-radio-label">Japanese</label></div>
                                </div>

                                <div class="instructor-form-group" style="margin-top: 30px;">
                                     <label class="instructor-form-label">Where did you hear of this opportunity? is a required field*</label>
                                    <div class="instructor-radio-group"><input type="radio" name="opportunity_source" id="source_search_engine" value="Search engine" v-model="form.opportunity_source" class="instructor-radio"> <label for="source_search_engine" class="instructor-radio-label">Search engine</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="opportunity_source" id="source_friend" value="Friend or co-worker" v-model="form.opportunity_source" class="instructor-radio"> <label for="source_friend" class="instructor-radio-label">Friend or co-worker</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="opportunity_source" id="source_social" value="Social media" v-model="form.opportunity_source" class="instructor-radio"> <label for="source_social" class="instructor-radio-label">Social media (LinkedIn, Twitter, Facebook, Instagram)</label></div>
                                    <div class="instructor-radio-group"><input type="radio" name="opportunity_source" id="source_other" value="Other" v-model="form.opportunity_source" class="instructor-radio"> <label for="source_other" class="instructor-radio-label">Other</label></div>
                                </div>

                                <!-- Placeholder for repeated text inputs -->
                                <div class="instructor-form-group">
                                    
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
                                </div>
                                 <div class="instructor-form-group">
                                    
                                    <input type="text" class="instructor-form-input instructor-disabled-input" placeholder="Where did you hear of this opportunity? is a required field*">
                                </div>

                                <button type="submit" class="instructor-submit-btn">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

export default {
    components: {
        Head,
    },
    setup() {
        const form = useForm({
            first_name: '',
            last_name: '',
            email: '',
            linkedin_url: '',
            followers: '',
            mbm_programs: [],
            teaching_language: '',
            opportunity_source: '',
            profile_picture: null,
        });

        const profilePicturePreview = ref(null);
        const profilePictureInput = ref(null);

        const nameInitial = computed(() => {
            return form.first_name ? form.first_name.charAt(0).toUpperCase() : '';
        });

        function selectProfilePicture() {
            profilePictureInput.value.click();
        }

        function onProfilePictureChange(e) {
            const file = e.target.files[0];
            if (file) {
                form.profile_picture = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePicturePreview.value = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function submit() {
            form.post(route('instructor.register'));
        }

        return { form, profilePicturePreview, profilePictureInput, nameInitial, selectProfilePicture, onProfilePictureChange, submit };
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
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
    
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
    background-color: #BEBCBC;
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
    background-color: #BEBCBC;
    color: black;
    padding: 7px 1.5rem;
    border-radius: 0.25rem;
    border: none;
    cursor: pointer;
    font-weight: bold;
}
.home_page_style{
    padding: 0;
    background-color: #1898e5;
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
</style>