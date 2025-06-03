<template>
    <Head title="Add New Video" />

    <AuthenticatedLayout>


        <div class="py-12 main_upload_video" style="display: flex; ">
            <div v-if="currentStep == 2" style="width: 223px; background-color: white; padding-top: 20px; padding-bottom: 20px; flex-direction: column;display: flex;gap: 10px; height: max-content;">
                <div 
                    v-for="(video, index) in videosData" 
                    :key="index"
                    @click="selectVideoToEdit(index)"
                    style="width: 100%;padding: 10px 30px; font-size: 16px; font-weight: 600; cursor: pointer;"
                    :style="index === currentEditingVideoIndex ? { backgroundColor: '#9fd3f5', borderLeft: '2px solid #148ad9' } : {}"
                >
                    Video {{ index + 1 }} 
                    <!-- <span v-if="video.title">: {{ video.title }}</span> -->
                </div>
                <div 
                    @click="addNewVideoSlot"
                    style="width: 100%;padding: 10px 18px; color: #2C15F5; display: flex; gap:5px; font-size: 16px; font-weight: 600; cursor: pointer;"
                >
                    <img src="/images/blue_add_icon.svg" alt="Add Icon" />
                    Add Videos
                </div>
             </div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="bg-white border-b border-gray-200">

                        <div v-if="currentStep >= 2" class="mb-1 p-6 ">
                            <h2 class="vedio_title font-semibold leading-tight text-black-600">
                                <span v-if="form.course_title">Course: {{ form.course_title }} - </span>
                                Video {{ currentEditingVideoIndex + 1 }}
                                <span v-if="videosData[currentEditingVideoIndex] && videosData[currentEditingVideoIndex].title">: {{ videosData[currentEditingVideoIndex].title }}</span>
                                <span v-else-if="videosData[currentEditingVideoIndex] && !videosData[currentEditingVideoIndex].title"> (Untitled)</span>
                            </h2>
                        </div>

                        

                        <!-- Step Indicator -->
                        <div v-if="currentStep >= 2" class="flex justify-center p-6 ">
                            <div class="flex items-center w-full">
                                <div 
                                    :class="{
                                        'border-4 border-black text-white': currentStep >= 2,
                                        'bg-black': currentStep < 2
                                    }" 
                                    class="flex items-center justify-center w-6 h-6 rounded-full">
                                    <span class="check_text text-sm">Details</span>
                                </div>
                                
                                <div 
                                    :class="{
                                        'bg-black': currentStep >= 3,
                                        'bg-black': currentStep < 3
                                    }" 
                                    class="w-1/2 h-1  bg-black">
                                </div>
                                <div 
                                    :class="{
                                        'border-4 border-black text-white': currentStep >= 3,
                                        'bg-black': currentStep < 3
                                    }" 
                                    class="flex items-center justify-center w-6 h-6 rounded-full">
                                    <span class="check_text text-sm">Checks</span>
                                </div>
                                
                                <div 
                                    :class="{
                                        'bg-black': currentStep >= 4,
                                        'bg-black': currentStep < 4
                                    }" 
                                    class="w-1/2 h-1  bg-black">
                                </div>
                                <div 
                                    :class="{
                                        'border-4 border-black text-white': currentStep >= 4,
                                        'bg-black': currentStep < 4
                                    }" 
                                    class="flex items-center justify-center w-6 h-6 rounded-full">
                                    <span class="check_text text-sm">Visibility</span>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Upload Video -->
                        <div v-if="currentStep === 1" class="text-center">
                            <div class="upload_header">
                                <div class="Upload_text">Upload Course</div>
                                <div class="upload_left_icons">
                                    <img src="/images/guide_icon.svg" />
                                    <img src="/images/cross_icon.svg" />
                                </div>
                            </div>
                            <div class="md:col-span-2 upload_video_section">
                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Course Title <img src="/images/question_mark.svg"/></label>
                                        <input 
                                            type="text" 
                                            id="title" 
                                            v-model="form.course_title" 
                                            class="w-full p-2 border-none"
                                            placeholder="UI/UX Designing Course"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        />
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.course_title.length }}/100</span>
                                        </div>
                                    </div>
                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Description <img src="/images/question_mark.svg"/></label>
                                        <input 
                                            id="description" 
                                            v-model="form.course_description" 
                                            rows="5"
                                            class="w-full p-2 border-none"
                                            placeholder="Lorem ipsum dolor sit amet consectetur..."
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        ></input>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.course_description.length }}/5000</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Additional Description <img src="/images/question_mark.svg"/></label>
                                        <input 
                                            id="additional_description" 
                                            v-model="form.additional_description" 
                                            rows="5"
                                            class="w-full p-2 border-none"
                                            placeholder="Lorem ipsum dolor sit amet consectetur..."
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        ></input>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.additional_description.length }}/5000</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Recomendations <img src="/images/question_mark.svg"/></label>
                                        <input 
                                            type="text" 
                                            id="recomendations" 
                                            v-model="form.recomendations" 
                                            class="w-full p-2 border-none"
                                            placeholder="UI/UX Designing Course Recomendations"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        />
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.recomendations.length }}/100</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="course_price" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Course Price <img src="/images/question_mark.svg"/></label>
                                        <input
                                            type="number"
                                            id="course_price"
                                            v-model="form.course_price"
                                            class="w-full p-2 border-none"
                                            placeholder="Enter Course Price"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        />
                                        <!-- Optional: display character count or validation for price -->
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="certificates" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Certificates <img src="/images/question_mark.svg"/></label>
                                        <select
                                            id="certificates"
                                            v-model="form.certificates"
                                            @change="handleCertificateChange"
                                            class="custom-select" style="border: none;color: #4D4D4D; width: 100%;  border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        >
                                            <option value="" disabled selected hidden>Select Certificate</option>
                                            <option v-for="cert in props.certificates" :key="cert.value" :value="cert.value">
                                                {{ cert.text }}
                                            </option>
                                            <option value="_add_new_certificate_">-- Add New Certificate --</option>
                                        </select>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="industry" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Industry <img src="/images/question_mark.svg"/></label>
                                        <select
                                            id="industry"
                                            v-model="form.industry"
                                            class="custom-select" style="border: none;color: #4D4D4D; width: 100%;  border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        >
                                            <option value="" disabled selected hidden>Select Industry</option>
                                            <option v-for="industry in props.industries" :key="industry.value" :value="industry.value">
                                                {{ industry.text }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="course_type" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Course type <img src="/images/question_mark.svg"/></label>
                                        <select
                                            id="course_type"
                                            v-model="form.course_type"
                                            class="custom-select" style="border: none;color: #4D4D4D; width: 100%;  border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        >
                                            <option value="" disabled selected hidden>Select Course Type</option>
                                            <option v-for="courseType in props.courseTypes" :key="courseType.value" :value="courseType.value" >
                                                {{ courseType.text }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end mt-6 space-x-4">
                                        <!-- <button 
                                            @click="prevStep" 
                                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                            Back
                                        </button> -->
                                        <button 
                                            @click="nextStep" 
                                            class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700" style="background-color: #148ad9; color: white; font-size: 14px; border-radius: 20px; font-weight: 600;">
                                            Next
                                        </button>
                                    </div>
                                    
                            </div>



                        </div>

                        <!-- Step 2: Video Details -->
                        <div v-if="currentStep === 2" class="p-6" style="padding-top: 0;">
                            <h3 class="mb-6 text-lg font-medium">Details</h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="md:col-span-2">
                                    <div class="mb-6" style="border: 1px solid grey; border-radius: 15px; padding: 5px;">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Video Title <img src="/images/question_mark.svg"/></label>
                                        <input 
                                            type="text" 
                                            id="video_title_step2" 
                                            v-model="currentVideoFormPart2.title" 
                                            class="w-full p-2 border-none"
                                            placeholder="Enter title for this video"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;"
                                        />
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ currentVideoFormPart2.title.length }}/100</span>
                                        </div>
                                    </div>
                                    <div class="mb-6" style="border: 1px solid grey; border-radius: 15px; padding: 5px;">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Description <img src="/images/question_mark.svg"/></label>
                                        <textarea 
                                            id="video_description_step2" 
                                            v-model="currentVideoFormPart2.description" 
                                            rows="5"
                                            class="w-full p-2 border-none"
                                            placeholder="Enter description for this video"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;"
                                        ></textarea>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ currentVideoFormPart2.description.length }}/5000</span>
                                        </div>
                                    </div>
                                    
                                   <div class="relative">
                                        <label for="thumbnail" class="block mb-2 font-medium flex" style=" color: black; font-size: 16px; font-weight: 600;">Thumbnail </label>
                                        <input 
                                            type="file" 
                                            id="thumbnail-upload" 
                                            ref="thumbnailUploadInput"
                                            class="hidden" 
                                            accept="image/*" 
                                            @change="handleThumbnailUpload"
                                        />
                                        <label 
                                            for="thumbnail-upload" 
                                            class="block  p-2 text-center   cursor-pointer hover:bg-gray-50"
                                            style="background-color: #9fd3f5; width: 178px; height: 79px; text-align: center; justify-content: center; align-items: center; display: flex; border-radius: 8px; color: black; font-size: 12px; font-weight: 600;"
                                        >
                                            Add your Thumbnail
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-6">
                                        
                                        <div class="border border-gray-300 rounded-md">
                                            <!-- Hidden file input for this preview's upload button -->
                                            <input type="file" ref="videoUploadInputForPreview" @change="handleVideoUploadFromRightPanel" accept="video/*" class="hidden">

                                            <!-- Visual Preview Area -->
                                            <div class=""> 
                                                <template v-if="!videosData[currentEditingVideoIndex] || !videosData[currentEditingVideoIndex].videoFile">
                                                    <!-- Show Upload Video Button if no video in form -->
                                                    
                                                          <button @click="triggerVideoUploadFromRightPanel" class="px-4 py-2 text-black flex items-center justify-center w-full bg-[#9fd3f5]" style="height: 150px; width: 100%;">
                                                              Upload Video for Video {{ currentEditingVideoIndex + 1 }}
                                                           </button>
                                                     
                                                </template>
                                                <template v-else>
                                                    <!-- Video is in form, now check for thumbnail -->
                                                    <template v-if="activeThumbnailPreviewForRightPanel">
                                                        <img :src="activeThumbnailPreviewForRightPanel" alt="Thumbnail preview" class="w-full h-auto" />
                                                    </template>
                                                    <template v-else>
                                                        <!-- Video exists, but no thumbnail, show video player -->
                                                        <video v-if="activeVideoPreviewForRightPanel" :src="activeVideoPreviewForRightPanel" controls class="w-full h-auto" style="max-height: 200px; display: block;"></video>
                                                        <!-- Fallback if videoPreview isn't ready but form.video is -->
                                                        <div v-else class="flex items-center justify-center w-full bg-gray-100" style="height: 170px;">Video processing...</div>
                                                    </template>
                                                </template>
                                            </div>

                                            <!-- Details Section (Video Link & File Name) - only if video is present in form -->
                                            <div v-if="videosData[currentEditingVideoIndex] && videosData[currentEditingVideoIndex].videoFile" style="background-color: #BEBCBC; font-size: 10px; padding: 5px;">
                                                <div class="flex justify-between" >
                                                    Video link
                                                    <img src="/images/copy_icon.svg" alt="Copy Icon" class=" cursor-pointer" />
                                                </div>
                                                <div style="color: #2C15F5; word-break: break-all;">
                                                    {{ videosData[currentEditingVideoIndex].videoFile.name ? 'vid.example.com/' + videosData[currentEditingVideoIndex].videoFile.name : 'Generating link...' }}
                                                </div>
                                                <div >
                                                    File Name
                                                </div>
                                                <div style="word-break: break-all;">
                                                    {{ videosData[currentEditingVideoIndex].videoFile.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="mb-6">
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                v-model="form.comments_enabled" 
                                                class="mr-2"
                                            />
                                            <span>Enable Comments</span>
                                        </label>
                                    </div> -->
                                </div>
                            </div>
                            <div class="flex justify-end mt-6 space-x-4">
                                <button 
                                    @click="prevStep" 
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" style=" color: black; font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Back
                                </button>
                                <button 
                                    @click="nextStep" 
                                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700" style="background-color: #148ad9; color: white; font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Checks -->
                        <div v-if="currentStep === 3" class="p-6 upload_video_section" >
                            <h3 class="mb-6 text-xl font-semibold">Video Summary & Checks</h3>

                            <div v-if="!videosData || videosData.length === 0" class="text-center text-gray-500 py-10">
                                <p class="mb-2 text-lg">No videos have been configured yet.</p>
                                <p>Please go back to Step 2 to add video details.</p>
                            </div>

                            <div v-else>
                                <div v-for="(video, index) in videosData" :key="index" class="mb-8 p-4 border border-gray-200 rounded-lg shadow ">
                                    <h4 class="text-lg font-semibold mb-2">
                                        Video {{ index + 1 }}: {{ video.title || '(Untitled)' }}
                                    </h4>
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">Description:</p>
                                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ video.description || '(Not provided)' }}</p>
                                    </div>
                                  
                                </div>
                            </div>

                            <div class="flex justify-end mt-8 space-x-4">
                                <button 
                                    @click="prevStep" 
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" style="  font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Back
                                </button>
                                <button 
                                    @click="submitForm" 
                                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700" 
                                    style="background-color: #148ad9; color: white; font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Publish
                                </button>
                                

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Popup -->
        <div v-if="showCertificatePopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="p-6 bg-white rounded-lg shadow-xl" style="width: 400px;">
                <h3 class="mb-4 text-lg font-semibold">Add New Certificate</h3>
                <div class="mb-4">
                    <label for="newCertTitle" class="block mb-1 text-sm font-medium text-gray-700">Certificate Title:</label>
                    <input type="text" id="newCertTitle" v-model="newCertificate.title" class="w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="newCertDesc" class="block mb-1 text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="newCertDesc" v-model="newCertificate.description" rows="3" class="w-full p-2 border border-gray-300 rounded-md"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button @click="showCertificatePopup = false" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                    <button @click="submitNewCertificate" class="px-4 py-2 text-white bg-[#148ad9] rounded-md hover:bg-[#148ad9]">Add Certificate</button>
                </div>
            </div>
        </div>

        <footer class="footer_upload_video" style="background-color: white; display: flex; justify-content: space-between; padding: 20px; align-items: baseline; ">
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { ref, reactive, onMounted, computed, defineProps } from 'vue';

const props = defineProps({
    certificates: {
        type: Array,
        default: () => []
    },
    industries: {
        type: Array,
        default: () => []
    },
    courseTypes: {
        type: Array,
        default: () => []
    },
});

const currentStep = ref(1);
const uploadedVideo = ref(null);
const videoPreview = ref(null);
const thumbnailPreview = ref(null);

const form = useForm({
    course_title: '',
    course_description: '',
    additional_description: '',
    recomendations: '',
    certificates: '',
    industry: '',
    course_type: '',
    course_price: '',
});

const availableCertificates = ref([
]);

const availableIndustries = ref([
    { value: 'tech', text: 'Technology' },
    { value: 'finance', text: 'Finance' },
    // Add more industries here
]);

const availableCourseTypes = ref([
    { value: 'beginner', text: 'Beginner' },
    { value: 'intermediate', text: 'Intermediate' },
    { value: 'advanced', text: 'Advanced' },
    // Add more course types here
]);

const showCertificatePopup = ref(false);
const newCertificate = reactive({
    title: '',
    description: ''
});

const videosData = ref([]);
const currentEditingVideoIndex = ref(-1);

const currentVideoFormPart2 = reactive({
    title: '',
    description: '',
    playlist: '',
    visibility: 'private', // Added visibility here as it was used in saveCurrentVideoDetails
});

const activeVideoPreviewForRightPanel = ref(null);
const activeThumbnailPreviewForRightPanel = ref(null);

// Refs for template refs
const thumbnailUploadInput = ref(null);
const videoUploadInputForPreview = ref(null);


const saveCurrentVideoDetails = () => {
    if (currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        videosData.value[currentEditingVideoIndex.value].title = currentVideoFormPart2.title;
        videosData.value[currentEditingVideoIndex.value].description = currentVideoFormPart2.description;
        videosData.value[currentEditingVideoIndex.value].playlist = currentVideoFormPart2.playlist;
        videosData.value[currentEditingVideoIndex.value].visibility = currentVideoFormPart2.visibility;
    }
};

const populateVideoDetailsForm = (index) => {
    if (index >= 0 && videosData.value[index]) {
        const video = videosData.value[index];
        currentVideoFormPart2.title = video.title || '';
        currentVideoFormPart2.description = video.description || '';
        currentVideoFormPart2.playlist = video.playlist || '';
        currentVideoFormPart2.visibility = video.visibility || 'private';

        activeVideoPreviewForRightPanel.value = video.videoFilePreview || null;
        activeThumbnailPreviewForRightPanel.value = video.thumbnailFilePreview || null;
    } else {
        currentVideoFormPart2.title = '';
        currentVideoFormPart2.description = '';
        currentVideoFormPart2.playlist = '';
        currentVideoFormPart2.visibility = 'private'; // Reset visibility
        activeVideoPreviewForRightPanel.value = null;
        activeThumbnailPreviewForRightPanel.value = null;
    }
};

const selectVideoToEdit = (index) => {
    if (index === currentEditingVideoIndex.value) return;

    saveCurrentVideoDetails();
    currentEditingVideoIndex.value = index;
    populateVideoDetailsForm(index);
};

const addNewVideoSlot = () => {
    saveCurrentVideoDetails();

    const newVideoData = {
        videoFile: null,
        videoFilePreview: null,
        title: '',
        description: '',
        thumbnailFile: null,
        thumbnailFilePreview: null,
        playlist: '',
        visibility: 'private',
    };
    videosData.value.push(newVideoData);
    currentEditingVideoIndex.value = videosData.value.length - 1;
    populateVideoDetailsForm(currentEditingVideoIndex.value);
    // if(currentStep.value < 2) currentStep.value = 2;
};

const handleVideoUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        if (videosData.value.length === 0) {
            addNewVideoSlot();
        } else if (currentEditingVideoIndex.value === -1) {
             currentEditingVideoIndex.value = 0;
        }
        if(currentEditingVideoIndex.value < 0 && videosData.value.length > 0) {
            currentEditingVideoIndex.value = 0;
        }

        if (videosData.value[currentEditingVideoIndex.value]) {
            const currentVideo = videosData.value[currentEditingVideoIndex.value];
            if (currentVideo.videoFilePreview) {
                URL.revokeObjectURL(currentVideo.videoFilePreview);
            }
            currentVideo.videoFile = file;
            currentVideo.videoFilePreview = URL.createObjectURL(file);
            activeVideoPreviewForRightPanel.value = currentVideo.videoFilePreview;
        }
        nextStep();
    }
};

const handleThumbnailUpload = (e) => {
    const file = e.target.files[0];
    if (file && currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        const currentVideo = videosData.value[currentEditingVideoIndex.value];
        if (currentVideo.thumbnailFilePreview) {
            URL.revokeObjectURL(currentVideo.thumbnailFilePreview);
        }
        currentVideo.thumbnailFile = file;
        currentVideo.thumbnailFilePreview = URL.createObjectURL(file);
        activeThumbnailPreviewForRightPanel.value = currentVideo.thumbnailFilePreview;

        if (thumbnailUploadInput.value) {
            thumbnailUploadInput.value.value = '';
        }
    }
};

const nextStep = () => {
    if (currentStep.value === 1 && videosData.value.length === 0) {
        addNewVideoSlot();
    } else if (currentStep.value >=2 && currentEditingVideoIndex.value !== -1) {
        saveCurrentVideoDetails();
    }
    currentStep.value++;
};

const prevStep = () => {
    if (currentStep.value >=2 && currentEditingVideoIndex.value !== -1) {
         saveCurrentVideoDetails();
    }
    currentStep.value--;
};

const triggerVideoUploadFromRightPanel = () => {
    if (currentEditingVideoIndex.value === -1 && videosData.value.length === 0){
        addNewVideoSlot();
    } else if (currentEditingVideoIndex.value === -1 && videosData.value.length > 0) {
        currentEditingVideoIndex.value = 0;
        populateVideoDetailsForm(0);
    }
    if (videoUploadInputForPreview.value) {
        videoUploadInputForPreview.value.click();
    }
};

const handleVideoUploadFromRightPanel = (e) => {
    const file = e.target.files[0];
    if (file && currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        const currentVideo = videosData.value[currentEditingVideoIndex.value];
        if (currentVideo.videoFilePreview && currentVideo.videoFilePreview.startsWith('blob:')) {
            URL.revokeObjectURL(currentVideo.videoFilePreview);
        }
        currentVideo.videoFile = file; 
        currentVideo.videoFilePreview = URL.createObjectURL(file);
        activeVideoPreviewForRightPanel.value = currentVideo.videoFilePreview;
        
        if (videoUploadInputForPreview.value) {
            videoUploadInputForPreview.value.value = '';
        }
    }
};

const handleCertificateChange = () => {
    if (form.certificates === '_add_new_certificate_') {
        newCertificate.title = '';
        newCertificate.description = '';
        form.certificates = '';
        showCertificatePopup.value = true;
    }
};

const submitNewCertificate = () => {
    if (!newCertificate.title.trim()) {
        alert('Certificate title cannot be empty.');
        return;
    }
    const newCert = {
        value: newCertificate.title.toLowerCase().replace(/\s+/g, '-') + '-' + Date.now(),
        text: newCertificate.title.trim(),
        description: newCertificate.description.trim()
    };
    

    alert('New certificate created locally. Please ensure your backend saves this and refreshes the certificate list.'); // Placeholder alert
    

    form.certificates = newCert.value; // This will select the newly "added" certificate.
    showCertificatePopup.value = false;
};

const submitForm = async () => {
    saveCurrentVideoDetails();

    const formData = new FormData();

    // Append course details
    formData.append('title', form.course_title);
    formData.append('description', form.course_description);
    formData.append('price', form.course_price);
    // Add other course fields from the 'form' object as necessary
    formData.append('additional_description', form.additional_description);
    formData.append('recomendations', form.recomendations);
    formData.append('certificates', form.certificates);
    formData.append('industry', form.industry);
    formData.append('course_type', form.course_type);

    // Append videos data
    if (videosData.value && videosData.value.length > 0) {
        videosData.value.forEach((video, index) => {
            formData.append(`videos[${index}][title]`, video.title || '');
            formData.append(`videos[${index}][description]`, video.description || '');
            if (video.videoFile instanceof File) {
                formData.append(`videos[${index}][videoFile]`, video.videoFile);
            }
            formData.append(`videos[${index}][order]`, (index + 1).toString());
            // Example for appending a thumbnail if it exists
            if (video.thumbnailFile instanceof File) {
                formData.append(`videos[${index}][thumbnailFile]`, video.thumbnailFile);
            }
        });
    } else {
        // If there are no videos, we might need to send an empty array or a specific flag 
        // depending on backend validation (e.g., 'videos' => 'present|array').
        // Sending an empty array indicator if backend expects 'videos' key even if empty.
        formData.append('videos', JSON.stringify([])); 
    }

    try {
        router.post(route('courses.storeWithVideos'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: (page) => {
                // Inertia will automatically follow the redirect from the backend.
                // A client-side alert for success can be shown if desired, 
                // but the flashed message on the redirected page is often preferred.
                // console.log('Form submitted successfully, server responded with:', page);
                // If you have a global notification system that reads from $page.props.flash, it would pick up the success message.
                // For now, let's assume the redirect and server-flashed message are sufficient.
                router.visit(route('coursess')); // This is likely redundant now.
            },
            onError: (errors) => {
                console.error('Error submitting form:', errors);
                // The 'errors' object here will typically contain validation errors passed by Inertia.
                // If you have a component that displays $page.props.errors, it will show them.
                // For a general error message not tied to a field, you might need to check $page.props.flash.error
                let errorMessage = 'Submission failed. Please check the form for errors.';
                if (errors && typeof errors === 'object' && Object.keys(errors).length > 0) {
                    // Construct a message from validation errors
                    const errorDetails = Object.entries(errors)
                        .map(([field, message]) => {
                            const msgStr = Array.isArray(message) ? message.join(', ') : message;
                            return `${field}: ${msgStr}`;
                        })
                        .join('; ');
                    errorMessage = `Please correct the following errors: ${errorDetails}`;
                } else if (page && page.props && page.props.flash && page.props.flash.error) {
                     // This part might not be directly available in the `errors` argument of `onError`.
                     // General errors flashed by the server might need to be accessed via $page.props.flash.error 
                     // in the template or a global handler.
                     // For now, we just log it, as Inertia typically re-renders the page with new props.
                     console.error('Server flashed error:', page.props.flash.error);
                     errorMessage = page.props.flash.error; // Or a more generic message
                }
                // Displaying a generic alert. In a real app, you'd likely update the UI to show errors near fields or in a notification area.
                alert(errorMessage);
            }
        });
    } catch (error) {
        console.error('An unexpected error occurred during form submission:', error);
        alert('An unexpected error occurred. Please try again.');
    }
};

onMounted(() => {
    if (videosData.value.length === 0) {
        addNewVideoSlot();
    } else if (currentEditingVideoIndex.value === -1 && videosData.value.length > 0) {
        selectVideoToEdit(0);
    }
});

</script>

<style >
.upload_left_icons{
    display: flex;
    gap: 10px;
    
}
.upload_header{
    display: flex;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #7E7E7E;
}
.Upload_text{
    font-size: 24px;
    font-weight: 600;
}
.select_file_btn{
    border-radius: 24px;
    font-size: 16px;
    font-weight: 600;
}
.upload_video_section{
    padding-left: 70px;
    padding-right: 70px;
    padding-top: 40px;
    padding-bottom: 60px;
    width: 800px;
    
}
@media (max-width: 1200px) {
    .upload_video_section{
        width: 600px;
    }
}
@media (max-width: 1000px) {
    .upload_video_section{
        width: 500px;
    }
}
@media (max-width: 900px) {
    .upload_video_section{
        width: 400px;
    }
}
@media (max-width: 450px) {
    .upload_video_section{
        width: 300px;
    }
}
.select_file_icon_btn{
    padding: 25px;
    border-radius: 50%;
}
.head_select_file_icon_btn{
    justify-content: center;
    display: flex;
    margin-bottom: 20px;
}
.check_text{
    color: black;
    margin-bottom: 45px;
   
}
.vedio_title{
    font-size: 20px;
}

.custom-select {
    padding: 0.5rem;
    border: 1px solid #D1D5DB;
    border-radius: 0.375rem;
    color: #4D4D4D;
    width: 211px;
}

.custom-select:focus {
    outline: none;
    border-color: #9CA3AF;
    box-shadow: 0 0 0 1px rgba(59, 178, 246, 0.5);
   
}
.custom-select option {
    background-color: #6ac4fc;

}
.custom-select option:hover {
    background-color: #5299c5 !important;
    
}
.custom-radio {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 24px;
  height: 24px;
  border: 0.5px solid #000 !important;
  border-radius: 50%;
  background: #fff;
  margin-right: 12px;
  margin-top: 4px;
  cursor: pointer;
  outline: none !important;
  box-shadow: none !important;
}

.custom-radio:checked {
  background-image: url('/images/check_black.svg');
  background-repeat: no-repeat;
  background-position: center;
  background-size: 14px 14px;
  border: 0.5px solid #000 !important;
  background-color: #fff !important;
}

.custom-radio:focus {
  outline: none !important;
  box-shadow: none !important;
  border: 0.5px solid #000 !important;
}
.home_page_style{
    
    justify-content: space-between;
    display: flex;
    flex-direction: column;
    padding-bottom: 0;
    
    background-color: #1898e5;
    padding-left: 0;
    padding-right: 0;
    
}
@media (max-width: 770px) {
    .footer_upload_video{
        flex-direction: column;
        
    }
    .main_upload_video{
        flex-direction: column !important;
                gap: 20px;
        justify-content: center;
        align-items: center;

    }
    .Visibility_step{
        flex-direction: column !important;
    }
    .upload_video_section{
        padding-left: 30px;
    padding-right: 30px;
    }
}
</style>