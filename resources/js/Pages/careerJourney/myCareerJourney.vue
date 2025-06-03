<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="My Career Journey" />

    <AuthenticatedLayout>
        <div class="career-journey-container">
            <div class="career-journey-wrapper">
                <div class="career-journey-content">
                    <div class="career-journey-title">My Career Journey</div>
                    
                    <div class="profile-sections-container">
                        <!-- Profile Card -->
                        <div class="profile-card">
                            <img src="/images/profile.svg" alt="myCareerJourney" class="profile-image" />
                            <div class="profile-name">James j.</div>
                            <div class="profile-title">Senior Developer</div>
                        </div>
                        
                        <!-- Career Goal Card -->
                        <div class="career-goal-card">
                            <div class="goal-content">
                                <div class="goal-header">
                                    <span class="goal-title">Career Goal</span>
                                    <img src="/images/pen_icon.svg" alt="pen" class="edit-icon" />
                                </div>
                                <div class="goal-description">
                                    Grow and advance as <strong>Senior Developer</strong>
                                </div>
                            </div>

                            <div class="goal-footer">
                                Lorem ipsum dolor sit amet consectetur.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Learning Plan Section -->
                <div class="learning-plan-section">
                    <div>
                         <div class="learning-plan-title">Choose a focus to unlock your personalized learning plan</div>
                        <p class="learning-plan-subtitle">We'll create a plan to help you there.</p>
                    </div>
                    <div style=" display: flex;gap: 10px;">
                        <input type="text" placeholder="I want to..." class="add-skill-input" />
                        <div>
                            <img src="/images/arrow_add_icon.svg" alt="add" class="add-skill-icon" />
                        </div>
                    </div>
                       
                        
                        <div class="focus-options-container">
                            
                            <div class="focus-option"><img src="/images/bulb_icon.svg"/>Focus on top designs</div>
                            <div class="focus-option"><img src="/images/bulb_icon.svg"/>Select Skills</div>
                            <div class="focus-option"><img src="/images/bulb_icon.svg"/>Develop Skills to manage teams</div>
                            <div class="focus-option"><img src="/images/bulb_icon.svg"/>Focus on top UI Ux designs</div>
                            <div class="focus-option"><img src="/images/bulb_icon.svg"/>Explore your interest</div>
                            <div class="focus-option"><img src="/images/bulb_icon.svg"/>Explore your Career</div>
                        </div>
                    </div>
            </div>
        </div>
        <footer class="footer_upload_video" style="background-color: white; display: flex; justify-content: space-between; padding: 20px; align-items: baseline; margin-top: 30px;">
            <div>
                Language(Eng)
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
                About
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
               Become an instructor
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
                Privacy Policy
            </div>
            <div style="font-size: 40px; font-weight: 400;">.</div>
            <div>
               Accessibility
            </div>
        </footer>
    </AuthenticatedLayout>
</template>

<style >
/* Container Styles */
.career-journey-container {
    padding: 0;
}

.career-journey-wrapper {
    margin: 0 auto;
}

.career-journey-content {
    background: white;
    padding: 30px;
    margin-bottom: 30px;
}

/* Title Styles */
.career-journey-title {
    font-size: 36px;
    font-weight: 600;
}

/* Profile Sections Container */
.profile-sections-container {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    margin-bottom: 20px;
    gap: 20px;
}
@media (max-width: 1080px) {
    .profile-sections-container {
        flex-direction: column;
        align-items: center;
                gap: 20px;
    }
}

/* Profile Card Styles */
.profile-card {
    border: 1px solid gray;
    width: 490px;
    height: 194px;
    border-radius: 8px;
    padding: 20px;
}
@media (max-width: 1080px) {
    .profile-card {
        width: 100%;
    }
}

.profile-image {
    /* Add any specific image styles here */
}

.profile-name {
    font-size: 24px;
    margin-top: 10px;
}

.profile-title {
    margin-top: 5px;
    color: #666;
}

/* Career Goal Card Styles */
.career-goal-card {
    border: 1px solid gray;
    width: 490px;
    height: 194px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
@media (max-width: 780px) {
    .career-goal-card {
        width: 100%;
    }
}

.goal-content {
    padding: 20px;
}

.goal-header {
    font-size: 20px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.edit-icon {
    /* Add any specific icon styles here */
}

.goal-description {
    margin-top: 10px;
}

.goal-footer {
    background-color: #D9D9D966;
    padding: 11px 20px 30px 20px;
    border-top: 1px solid gray;
}
.home_page_style {
    padding: 0;
}

.learning-plan-section{
    padding: 20px;
    background: white;
        gap: 40px;
    display: flex;
    flex-direction: column;
    padding-bottom: 50px;
}
.learning-plan-title
{
font-size: 24px;
font-weight: 600;

}
.learning-plan-subtitle{
    font-size: 24px;
 color: #4D4D4D;
}
.add-skill-input{
    width: 80%;
    height: 50px;
    border-radius: 24px;
    border: 1px solid gray;
    padding-left: 20px;
    
}
.add-skill-input:focus{
    outline: none;
    border: 1px solid #4D4D4D;
    box-shadow: none; 
}
.add-skill-icon{
    background-color: #7E7E7E33;
    border-radius: 50%;
    padding: 15px 10px;
}
.focus-option{
    border-radius: 24px;
    border: 1px solid gray;
    padding: 10px;
        padding-left: 24px;
    padding-right: 80px;
    display: flex;
    gap: 15px;
}
@media (max-width: 340px) {
    .focus-option {
        padding-right: 20px;
    }
}
.focus-options-container{
    display: flex;
    gap: 10px;
    justify-content: flex-start;
    align-items: center;
    flex-wrap: wrap;
}
@media (max-width: 770px) {
    .footer_upload_video{
        flex-direction: column;
        
    }
}
</style>