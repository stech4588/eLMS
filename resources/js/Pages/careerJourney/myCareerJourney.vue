<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';

const page = usePage();
const user = computed(() => {
    const authUser = page.props.auth.user;
    return {
        ...authUser,
        badges: [
            { name: 'Top Learner', icon_url: '/images/badge1.svg' },
            { name: 'Fast Learner', icon_url: '/images/badge2.svg' },
            { name: 'Subject Master', icon_url: '/images/badge3.svg' },
            { name: 'Rising Star', icon_url: '/images/badge4.svg' },
        ]
    };
});

const leaderboard = ref([]);

onMounted(() => {
    fetchLeaderboard();
});

async function fetchLeaderboard() {
    try {
        const response = await fetch(route('api.leaderboard'));
        const data = await response.json();
        leaderboard.value = data;
    } catch (error) {
        console.error('Error fetching leaderboard:', error);
    }
}

const rankedLeaderboard = computed(() => {
    // Filter out the current user from the fetched leaderboard to avoid duplicates
    const otherUsers = leaderboard.value.filter(u => u.id !== user.value.id);

    const allUsers = [
        ...otherUsers,
        {
            id: user.value.id,
            name: user.value.name,
            points: user.value.points,
            badges: user.value.badges,
        },
    ];

    const sortedUsers = allUsers.sort((a, b) => b.points - a.points);
    
    return sortedUsers.map((user, index) => ({
        ...user,
        rank: index + 1,
    }));
});

const props = defineProps({
    preferredTopics: Array,
    allTopics: Array,
});

const isEditing = ref(false);
const goalForm = useForm({
    primary_learning_goal: user.value.primary_learning_goal,
});

function startEditing() {
    goalForm.primary_learning_goal = user.value.primary_learning_goal;
    isEditing.value = true;
}

function cancelEditing() {
    isEditing.value = false;
    goalForm.reset();
}

function saveCareerGoal() {
    goalForm.patch(route('career-goal.update'), {
        onSuccess: () => {
            isEditing.value = false;
        },
    });
}

const topicForm = useForm({
    preferred_topic_ids: props.preferredTopics.map(t => t.id),
});

const searchTerm = ref('');

const availableTopics = computed(() => {
    return props.allTopics.filter(topic => {
        const isNotSelected = !topicForm.preferred_topic_ids.includes(topic.id);
        const matchesSearch = topic.name.toLowerCase().includes(searchTerm.value.toLowerCase());
        return isNotSelected && matchesSearch;
    }).slice(0, 10);
});

function getTopicName(topicId) {
    const topic = props.allTopics.find(t => t.id === topicId);
    return topic ? topic.name : '';
}

function addTopic(topic) {
    if (!topicForm.preferred_topic_ids.includes(topic.id)) {
        topicForm.preferred_topic_ids.push(topic.id);
    }
    searchTerm.value = '';
}

function removeTopic(topicId) {
    topicForm.preferred_topic_ids = topicForm.preferred_topic_ids.filter(id => id !== topicId);
}

function updateTopics() {
    topicForm.patch(route('preferred-topics.update'));
}
</script>

<template>
    <Head title="My Career Journey" />

    <AuthenticatedLayout>
        <div class="career-journey-container">
            <div class="career-journey-wrapper">
                <div class="career-journey-content dark:bg-dark-bg-secondary dark:text-white">
                    <div class="career-journey-title">My Career Journey</div>

                    <div class="journey-badges-section">
                        <h3 class="journey-badges-title">My Badges</h3>
                        <div class="journey-badges-list">
                            <div v-for="(badge, index) in user.badges" :key="index" class="journey-badge-item">
                                <img :src="badge.icon_url" :alt="badge.name" class="journey-badge-icon" />
                                <span class="journey-badge-name">{{ badge.name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-sections-container">
                        <!-- Profile Card -->
                        <div class="profile-card">
                            <img :src="user.profile_photo_url || '/images/profile.svg'" alt="myCareerJourney" class="profile-image" style="width: 85px;border-radius: 50%;"/>
                            <div class="profile-name">{{ user.name }}</div>
                            <div class="profile-title">{{ user.type }}</div>
                        </div>
                        
                        <!-- Points Card -->
                        <div class="points-card">
                            <div class="points-content">
                                <div class="points-section">
                                <div class="points-header">
                                    <span class="points-title">My Points</span>
                                </div>
                                <div class="points-value">
                                    {{ user.points }}
                                    </div>
                                </div>
                            </div>
                            <div class="points-footer">
                                Keep learning to earn more points and badges!
                            </div>
                        </div>

                        <!-- Career Goal Card -->
                        <div class="career-goal-card">
                            <div class="goal-content" >
                                <div class="goal-header">
                                    <span class="goal-title">Career Goal</span>
                                    <img src="/images/pen_icon.svg" alt="pen" class="edit-icon dark_career_focus_option" @click="startEditing" v-if="!isEditing"/>
                                </div>
                                <div v-if="!isEditing" class="goal-description">
                                    {{ user.primary_learning_goal }}
                                </div>
                                <div v-else>
                                    <form @submit.prevent="saveCareerGoal">
                                         <input type="text" v-model="goalForm.primary_learning_goal" class="add-skill-input dark:bg-dark-bg-secondary" style="width: 100%; padding-left: 10px;height: 40px;"  />
                                         <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                                              <button type="submit" class="save-btn">Save</button>
                                          <button type="button" @click="cancelEditing" class="cancel-btn">Cancel</button>
                                         </div>
                                         
                                      </form>
                                </div>
                            </div>

                            <div class="goal-footer">
                                We are dedicated to delivering high-quality content with care and professionalism.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Learning Plan Section -->
                <div class="learning-plan-section dark:bg-dark-bg-secondary dark:text-white">
                    <div>
                         <div class="learning-plan-title">Choose a focus to unlock your personalized learning plan</div>
                        <p class="learning-plan-subtitle">We'll create a plan to help you there.</p>
                    </div>
                    <form @submit.prevent="updateTopics" class="topics-form">
                        <div class="topics-input-wrapper">
                            <div class="selected-topics-container">
                                <span v-for="topicId in topicForm.preferred_topic_ids" :key="topicId" class="topic-tag dark:bg-dark-bg-secondary">
                                    <span>{{ getTopicName(topicId) }}</span>
                                    <button @click.prevent="removeTopic(topicId)" class="remove-tag">&times;</button>
                                </span>
                                <input 
                                    type="text" 
                                    v-model="searchTerm"
                                    placeholder="I want to..." 
                                    class="add-skill-input dark:bg-dark-bg-secondary" 
                                    style="border: none;"
                                />
                            </div>
                            <button type="submit" class="submit-topics-btn">
                                <img src="/images/arrow_add_icon.svg" alt="add" class="add-skill-icon" />
                            </button>
                        </div>
                    </form>
                       
                    <div class="focus-options-container">
                        <div v-for="topic in availableTopics" :key="topic.id" class="focus-option" @click="addTopic(topic)">
                            <img class="dark_career_focus_option" src="/images/bulb_icon.svg"/><span>{{ topic.name }}</span>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Leaderboard Section -->
            <div class="leaderboard-section dark:bg-dark-bg-secondary dark:text-white">
                <h2 class="leaderboard-title">Leaderboard</h2>
                <table class="leaderboard-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Points</th>
                            <th>Badges</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="person in rankedLeaderboard" :key="person.id" :class="{ 'current-user-highlight': person.id === user.id }">
                            <td>{{ person.rank }}</td>
                            <td class="user-info">
                                <img :src="person.profile_photo_url || '/images/profile.svg'" alt="Profile" class="leaderboard-profile-image" />
                                <span>{{ person.name }}</span>
                            </td>
                            <td>{{ person.points }}</td>
                            <td>
                                <div class="leaderboard-badges">
                                    <img v-for="(badge, index) in person.badges" :key="index" :src="badge.icon_url" class="leaderboard-badge-icon" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="footer_upload_video dark:bg-dark-bg-secondary dark:text-white" style="display: flex; justify-content: space-between; padding: 20px; align-items: baseline; margin-top: 30px;">
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

.journey-badges-section {
    margin-top: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e0e0e0;
}
.dark .journey-badges-section {
    border-bottom-color: #5a5a5a;
}
.journey-badges-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
}
.journey-badges-list {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: center;
}
.journey-badge-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    width: 100px;
}
.journey-badge-icon {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;
}
.journey-badge-name {
    font-size: 14px;
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
    border-radius: 8px;
    padding: 20px;
}
@media (max-width: 1080px) {
    .profile-card {
        width: 100%;
    }
}

.profile-name {
    font-size: 24px;
    margin-top: 10px;
}

.profile-title {
    margin-top: 5px;
    color: #666;
}

/* Points Card Styles */
.points-card {
    border: 1px solid gray;
    width: 490px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
@media (max-width: 780px) {
    .points-card {
        width: 100%;
    }
}

.points-content {
    padding: 20px;
    padding-bottom: 0px;
    padding-top: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
}

.points-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
}

.points-header {
    font-size: 20px;
    font-weight: 600;
}

.points-value {
    font-size: 48px;
    font-weight: bold;
    text-align: center;
    margin-top: 10px;
}

.points-footer {
    background-color: #D9D9D966;
    padding: 8px 13px 8px 13px;
    border-top: 1px solid gray;
    text-align: center;
}

/* Career Goal Card Styles */
.career-goal-card {
    border: 1px solid gray;
    width: 490px;
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
    padding-bottom: 0px;
    padding-top: 10px;
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
    cursor: pointer;
}

.goal-description {
    margin-top: 10px;
    border-top: 1px solid gray;
}

.goal-footer {
    background-color: #D9D9D966;
    padding: 8px 13px 8px 13px;
    border-top: 1px solid gray;
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
.submit-topics-btn:hover .add-skill-icon {
    background-color: #c7c7c766;
    filter: brightness(0.5);
}
.focus-option{
    border-radius: 24px;
    border: 1px solid gray;
    padding: 10px;
    padding-left: 24px;
    padding-right: 80px;
    display: flex;
    gap: 15px;
    cursor: pointer;
    overflow: hidden;
}
.focus-option span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.focus-option:hover{
    border: 1px solid #148ad9;
    transform: scale(1.05);
    transition: transform 0.3s ease;
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
.footer_upload_video {
    background-color: white;
}
@media (max-width: 770px) {
    .footer_upload_video{
        flex-direction: column;
        
    }
}

.topics-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.topics-input-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
    width: 100%;
}
.selected-topics-container {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    align-items: center;
    min-height: 40px;
    padding: 5px;
    border: 1px solid #8a8686;
    border-radius: 24px;
    width: 100%;
}
.topic-tag {
    display: inline-flex;
    align-items: center;
    background: #f0f0f0;
    padding: 5px 10px;
    border-radius: 4px;
    margin: 2px;
    max-width: 200px;
    position: relative;
}
.dark .topic-tag{
    background: #696969;
}

.topic-tag span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    margin-right: 8px;
}
.topic-tag:hover{
    border: 1px solid #148ad9;
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

.remove-tag {
    margin-left: 4px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 16px;
    padding: 0;
    line-height: 1;
    color: #555;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
}


.dark .remove-tag {
    color: #fff;
}

.submit-topics-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.save-btn, .cancel-btn {
    padding: 0px 6px;
    border-radius: 5px;
    border: 1px solid transparent;
    cursor: pointer;
    margin-right: 10px;
}
.save-btn {
    background-color: #148ad9;
    color: white;
}
.cancel-btn {
    background-color: #bbbbbb;
    color: white;
}
.dark .dark_career_focus_option{
    filter: invert(1);
}
.leaderboard-section {
    background: white;
    padding: 30px;
    margin-top: 30px;
}

.leaderboard-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
}

.leaderboard-table {
    width: 100%;
    border-collapse: collapse;
}

.leaderboard-table th, .leaderboard-table td {
    border: 1px solid #ddd;
    padding: 12px 15px;
    text-align: left;
    vertical-align: middle;
}

.leaderboard-table th {
    background-color: #f4f6f8;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    font-size: 14px;
}

.dark .leaderboard-table th {
    background-color: #4a4a4a;
    color: #fff;
}
.dark .leaderboard-table td {
    border-color: #5a5a5a;
}
.leaderboard-badges {
    display: flex;
    gap: 5px;
}

.leaderboard-badge-icon {
    width: 24px;
    height: 24px;
}
.current-user-highlight {
    background-color: #e0efff;
    font-weight: bold;
}
.dark .current-user-highlight {
    background-color: #3a5a8a;
}
.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.leaderboard-profile-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}
</style>