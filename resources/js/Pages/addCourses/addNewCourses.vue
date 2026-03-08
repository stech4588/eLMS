<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout>

        <!-- Global loader overlay while draft with media is being saved/uploaded -->
        <div
            v-if="isSavingDraft"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-60"
        >
            <div class="flex flex-col items-center justify-center bg-white dark:bg-[#1A2C38] rounded-xl px-8 py-6 shadow-lg min-w-[300px]">
                <div class="relative mb-4">
                    <div class="h-16 w-16 rounded-full border-4 border-gray-200"></div>
                    <div class="absolute inset-0 h-16 w-16 rounded-full border-4 border-green-500 border-t-transparent animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xs font-bold text-gray-900 dark:text-gray-200">{{ uploadProgress }}%</span>
                    </div>
                </div>
                <p class="text-gray-700 dark:text-gray-200 font-semibold text-sm mb-3 text-center">
                    Uploading & saving your draft...
                </p>
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                    <div 
                        class="bg-[#22c55e] dark:bg-[#22c55e] h-2.5 rounded-full transition-all duration-300 ease-out"
                        :style="{ width: uploadProgress + '%' }"
                    ></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    Please wait, do not close this page.
                </p>
            </div>
        </div>

        <div class="py-12 main_upload_video" style="display: flex; justify-content: center;">
            <div v-if="currentStep == 3" style="width: 223px; background-color: white; padding-top: 20px; padding-bottom: 20px; flex-direction: column;display: flex;gap: 10px; height: max-content;" class="add_course_dark_left_videos dark:bg-[#1A2C38]">
                <template v-if="sectionsData.length > 0">
                    <div v-for="(section, sectionIdx) in sectionsData" :key="section.id || 's-' + sectionIdx" class="flex flex-col gap-1">
                        <div class="font-semibold text-gray-800 dark:text-white px-2 py-1 text-sm">{{ section.title || '(Untitled section)' }}</div>
                        <div
                            v-for="entry in getVideosInSection(sectionIdx)"
                            :key="entry.globalIndex"
                            style="width: 100%; font-size: 14px; font-weight: 600; display: flex; align-items: center;"
                            :class="entry.globalIndex === currentEditingVideoIndex ? 'add_course_dark_left_videos_item_active' : ''"
                            :style="entry.globalIndex === currentEditingVideoIndex ? { backgroundColor: '#dcfce7', borderLeft: '2px solid #22c55e' } : {}"
                        >
                            <span @click="selectVideoToEdit(entry.globalIndex)" style="flex-grow: 1; padding: 8px 16px; cursor: pointer;" class="add_course_dark_left_videos_item_text truncate" :title="entry.video.title">
                                {{ entry.video.title || 'Video ' + (entry.globalIndex + 1) }}
                            </span>
                            <button @click.stop="removeVideo(entry.globalIndex)" style="background:transparent; border:none; cursor:pointer; padding-right: 12px;" title="Remove video">
                                <img src="/images/cross_icon.svg" alt="Remove" style="height: 12px; width: 12px;" class="dark_dropdown_arrow" />
                            </button>
                        </div>
                        <div
                            @click="addNewVideoSlot(sectionIdx)"
                            style="width: 100%; padding: 8px 16px; color: #2C15F5; display: flex; gap:5px; font-size: 14px; font-weight: 600; cursor: pointer;"
                            class="add_course_dark_text"
                        >
                            <img src="/images/blue_add_icon.svg" alt="Add Icon" class="add_course_dark_icons"/>
                            Add Video
                        </div>
                    </div>
                </template>
                <p v-else class="px-2 text-sm text-gray-500 dark:text-gray-400">Add sections in Step 2 first.</p>
             </div>
            <div class=" max-w-7xl sm:px-1 lg:px-8" style="width: 100%;">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="bg-white border-b border-gray-200 dark:bg-[#1A2C38] dark:border-dark-border-secondary dark:text-white">

                        <div v-if="currentStep >= 3" class="mb-1 p-6 ">
                            <h2 class="vedio_title font-semibold leading-tight text-black-600">
                                <span v-if="form.course_title">Course: {{ form.course_title }} - </span>
                                Video {{ currentEditingVideoIndex + 1 }}
                                <span v-if="videosData[currentEditingVideoIndex] && videosData[currentEditingVideoIndex].title">: {{ videosData[currentEditingVideoIndex].title }}</span>
                                <span v-else-if="videosData[currentEditingVideoIndex] && !videosData[currentEditingVideoIndex].title"> (Untitled)</span>
                            </h2>
                        </div>



                        <!-- Step Indicator -->
                        <div v-if="currentStep >= 2" class="flex justify-center p-6 ">
                            <div class="flex items-center w-full flex-wrap gap-1">
                                <div :class="{ 'border-4 border-black text-white': currentStep >= 2, 'bg-black': currentStep < 2 }" class="flex items-center justify-center w-6 h-6 rounded-full"><span class="check_text text-sm">Sections</span></div>
                                <div class="w-1/2 h-1 bg-black min-w-[20px]"></div>
                                <div :class="{ 'border-4 border-black text-white': currentStep >= 3, 'bg-black': currentStep < 3 }" class="flex items-center justify-center w-6 h-6 rounded-full"><span class="check_text text-sm">Videos</span></div>
                                <div class="w-1/2 h-1 bg-black min-w-[20px]"></div>
                                <div :class="{ 'border-4 border-black text-white': currentStep >= 4, 'bg-black': currentStep < 4 }" class="flex items-center justify-center w-6 h-6 rounded-full"><span class="check_text text-sm">Visibility</span></div>
                            </div>
                        </div>

                        <!-- Step 1: Upload Video -->
                        <div v-if="currentStep === 1" class="text-center">
                            <div class="upload_header dark:bg-[#1A2C38] dark:text-white">
                                <div class="Upload_text">{{ isEditingCourse ? 'Edit Course' : 'Upload Course' }}</div>
                                <!-- <div class="upload_left_icons">
                                    <img src="/images/guide_icon.svg" />
                                    <img src="/images/cross_icon.svg" />
                                </div> -->
                            </div>
                            <div class="md:col-span-2 upload_video_section dark:bg-[#1A2C38] dark:text-white" style="width: 100%;">
                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Course Title<span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            id="title"
                                            v-model="form.course_title"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38] dark:text-white"
                                            placeholder="UI/UX Designing Course"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        />
                                        <p v-if="errors.course_title" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.course_title }}</p>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.course_title.length }}/100</span>
                                        </div>
                                    </div>
                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Description <span style="color: red;">*</span></label>
                                        <input
                                            id="description"
                                            v-model="form.course_description"
                                            rows="5"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38]"
                                            placeholder="Enter Course Description..."
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        ></input>
                                        <p v-if="errors.course_description" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.course_description }}</p>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.course_description.length }}/5000</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Additional Description <span style="color: red;">*</span></label>
                                        <input
                                            id="additional_description"
                                            v-model="form.additional_description"
                                            rows="5"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38]"
                                            placeholder="Enter Additional Description..."
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        ></input>
                                        <p v-if="errors.additional_description" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.additional_description }}</p>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.additional_description.length }}/5000</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="title" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Recomendations <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            id="recomendations"
                                            v-model="form.recomendations"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38] dark:text-white"
                                            placeholder="Enter Recomendations..."
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        />
                                        <p v-if="errors.recomendations" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.recomendations }}</p>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ form.recomendations.length }}/100</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="course_price" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Course Price <span style="color: red;">*</span></label>
                                        <input
                                            type="number"
                                            id="course_price"
                                            v-model="form.course_price"
                                            min="0"
                                            step="0.01"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38] dark:text-white"
                                            placeholder="Enter Course Price (e.g. 49.00)"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important; border: 1px solid grey; border-radius: 15px; padding: 20px;"
                                        />
                                        <p v-if="errors.course_price" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.course_price }}</p>
                                    </div>



                                    <div class="mb-6" style="">
                                        <label for="certificates" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Certificates <span style="color: red;">*</span></label>
                                        <div class="custom-dropdown" @click="toggleDropdown('certificates')" :class="{ 'active': activeDropdown === 'certificates' }">
                                            <div class="selected-option dark:bg-[#1A2C38]"style="border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;">
                                                <span>{{ getSelectedText('certificates') || 'Select Certificate' }}</span>
                                                <div class="dropdown-arrow dark:bg-[#1A2C38] dark:text-white">
                                                    <img src="/images/dropdown_arrow.svg" alt="dropdown" class="dark_dropdown_arrow" />
                                                </div>
                                            </div>
                                            <div class="dropdown-options" v-if="activeDropdown === 'certificates'">
                                                <div
                                                    v-for="cert in props.certificates"
                                                    :key="cert.value"
                                                    class="dropdown-option"
                                                    @click="selectOption('certificates', cert.value, cert.text)"
                                                >
                                                    {{ cert.text }}
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="errors.certificates" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.certificates }}</p>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="topic" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Topic <span style="color: red;">*</span></label>
                                        <div class="custom-dropdown" @click="toggleDropdown('topic')" :class="{ 'active': activeDropdown === 'topic' }">
                                            <div class="selected-option dark:bg-[#1A2C38]" style="border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;">
                                                <span>{{ getSelectedText('topic') || 'Select Topic' }}</span>
                                                <div class="dropdown-arrow">
                                                    <img src="/images/dropdown_arrow.svg" alt="dropdown" class="dark_dropdown_arrow" />
                                                </div>
                                            </div>
                                            <div class="dropdown-options" v-if="activeDropdown === 'topic'">
                                                <div
                                                    v-for="topic in props.topics"
                                                    :key="topic.value"
                                                    class="dropdown-option"
                                                    @click="selectOption('topic', topic.value, topic.text)"
                                                >
                                                    {{ topic.text }}
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="errors.topic" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.topic }}</p>
                                    </div>



                                    <div class="mb-6" style="">
                                        <label for="industry" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Industry <span style="color: red;">*</span></label>
                                        <div class="custom-dropdown" @click="toggleDropdown('industry')" :class="{ 'active': activeDropdown === 'industry' }">
                                            <div class="selected-option dark:bg-[#1A2C38]"style="border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;">
                                                <span>{{ getSelectedText('industry') || 'Select Industry' }}</span>
                                                <div class="dropdown-arrow">
                                                    <img src="/images/dropdown_arrow.svg" alt="dropdown" class="dark_dropdown_arrow" />
                                                </div>
                                            </div>
                                            <div class="dropdown-options" v-if="activeDropdown === 'industry'">
                                                <div
                                                    v-for="industry in props.industries"
                                                    :key="industry.value"
                                                    class="dropdown-option"
                                                    @click="selectOption('industry', industry.value, industry.text)"
                                                >
                                                    {{ industry.text }}
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="errors.industry" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.industry }}</p>
                                    </div>

                                    <div class="mb-6" style="">
                                        <label for="course_type" class="block mb-2 font-medium flex" style="gap: 10px; color: #7E7E7E;">Course type <span style="color: red;">*</span></label>
                                        <div class="custom-dropdown" @click="toggleDropdown('course_type')" :class="{ 'active': activeDropdown === 'course_type' }">
                                            <div class="selected-option dark:bg-[#1A2C38]" style="border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;">
                                                <span>{{ getSelectedText('course_type') || 'Select Course Type' }}</span>
                                                <div class="dropdown-arrow">
                                                    <img src="/images/dropdown_arrow.svg" alt="dropdown" class="dark_dropdown_arrow" />
                                                </div>
                                            </div>
                                            <div class="dropdown-options" v-if="activeDropdown === 'course_type'">
                                                <div
                                                    v-for="courseType in props.courseTypes"
                                                    :key="courseType.value"
                                                    class="dropdown-option"
                                                    @click="selectOption('course_type', courseType.value, courseType.text)"
                                                >
                                                    {{ courseType.text }}
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="errors.course_type" class="text-red-500 text-sm mt-1" style="text-align: start;">{{ errors.course_type }}</p>
                                    </div>

                                    <div class="flex justify-between items-center mt-6 space-x-4">
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <span v-if="isSavingDraft" class="flex items-center gap-1">
                                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l5-5-5-5v4a12 12 0 00-12 12h4z"></path>
                                                </svg>
                                                Saving...
                                            </span>
                                            <span v-else-if="lastSavedAt" class="text-green-600">
                                                Draft saved
                                            </span>
                                        </div>
                                        <button
                                            @click="nextStep"
                                            class="px-4 py-2 text-white bg-[#22c55e] rounded-md hover:bg-[#16a34a]" style="background-color: #22c55e; color: white; font-size: 14px; border-radius: 20px; font-weight: 600; margin-top: 37px;">
                                            Next
                                        </button>
                                    </div>

                            </div>



                        </div>

                        <!-- Step 2: Add Sections -->
                        <div v-if="currentStep === 2" class="p-6">
                            <h3 class="mb-4 text-lg font-medium dark:text-white">Course Sections</h3>
                            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Add sections to organize your course. Each video will belong to a section.</p>
                            <div class="mb-6 flex flex-wrap gap-2">
                                <input
                                    v-model="newSectionTitle"
                                    type="text"
                                    placeholder="Section title (e.g. Building Your Business)"
                                    class="flex-1 min-w-[200px] rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#1A2C38] px-4 py-2 dark:text-white"
                                    @keyup.enter="addSection"
                                />
                                <button
                                    type="button"
                                    @click="addSection"
                                    class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                                >
                                    Add Section
                                </button>
                            </div>
                            <div v-if="sectionsData.length > 0" class="space-y-2">
                                <h4 class="text-sm font-semibold dark:text-white">Section List:</h4>
                                <div
                                    v-for="(section, idx) in sectionsData"
                                    :key="section.id || 'temp-' + idx"
                                    class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-[#1A2C38] px-4 py-2"
                                >
                                    <span class="dark:text-white">{{ idx + 1 }}. {{ section.title || '(Untitled)' }}</span>
                                    <button
                                        type="button"
                                        @click="removeSection(idx)"
                                        class="text-red-600 hover:text-red-700"
                                        title="Remove section"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500 dark:text-gray-400">No sections yet. Add one above.</p>
                            <div class="flex justify-end mt-6 space-x-4">
                                <button
                                    type="button"
                                    @click="prevStep"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500"
                                    style="font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Back
                                </button>
                                <button
                                    type="button"
                                    @click="nextStep"
                                    class="px-4 py-2 text-white bg-[#22c55e] rounded-md hover:bg-[#16a34a]"
                                    style="background-color: #22c55e; font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Video Details -->
                        <div v-if="currentStep === 3" class="p-6" style="padding-top: 0;">
                            <h3 class="mb-6 text-lg font-medium">Details</h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="md:col-span-2">
                                    <div class="mb-6" style="border: 1px solid grey; border-radius: 15px; padding: 5px;">
                                        <label for="title" class="block mb-2 font-medium flex add_course_dark_text" style="gap: 10px; color: #7E7E7E;">Video Title <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            id="video_title_step2"
                                            v-model="currentVideoFormPart2.title"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38] dark:text-white"
                                            placeholder="Enter title for this video"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;  border-radius: 15px; padding: 20px;"
                                        />
                                        <p v-if="videosData[currentEditingVideoIndex]?.errors?.title" class="text-red-500 text-sm mt-1 px-2" style="text-align: start;">{{ videosData[currentEditingVideoIndex].errors.title }}</p>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ currentVideoFormPart2.title.length }}/100</span>
                                        </div>
                                    </div>
                                    <div class="mb-6" style="border: 1px solid grey; border-radius: 15px; padding: 5px;">
                                        <label for="title" class="block mb-2 font-medium flex add_course_dark_text" style="gap: 10px; color: #7E7E7E;">Description <span style="color: red;">*</span></label>
                                        <textarea
                                            id="video_description_step2"
                                            v-model="currentVideoFormPart2.description"
                                            rows="5"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38] dark:text-white"
                                            placeholder="Enter description for this video"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;  border-radius: 15px; padding: 20px;"
                                        ></textarea>
                                        <p v-if="videosData[currentEditingVideoIndex]?.errors?.description" class="text-red-500 text-sm mt-1 px-2" style="text-align: start;">{{ videosData[currentEditingVideoIndex].errors.description }}</p>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span>{{ currentVideoFormPart2.description.length }}/5000</span>
                                        </div>
                                    </div>

                                    <div class="mb-6" style="border: 1px solid grey; border-radius: 15px; padding: 5px;">
                                        <label for="takeaway_notes" class="block mb-2 font-medium flex add_course_dark_text" style="gap: 10px; color: #7E7E7E;">Takeaway Notes</label>
                                        <textarea
                                            id="takeaway_notes"
                                            v-model="currentVideoFormPart2.takeaway_notes"
                                            rows="5"
                                            class="w-full p-2 border-none dark:bg-[#1A2C38] dark:text-white"
                                            placeholder="Enter takeaway notes for this video"
                                            style="outline: none !important;
                                                box-shadow: none !important;
                                                border: none !important;  border-radius: 15px; padding: 20px;"
                                        ></textarea>
                                        <div class="flex justify-end mt-1 text-sm text-gray-500">
                                            <span v-if="currentVideoFormPart2.takeaway_notes">{{ currentVideoFormPart2.takeaway_notes.length }}/5000</span>
                                        </div>
                                    </div>

                                <!-- Per-Video Quiz -->
                                <div class="mb-6 rounded-xl border border-gray-300 dark:border-gray-600 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="block mb-1 font-semibold add_course_dark_text">Per‑Video Quiz</label>
                                            <p class="text-sm text-gray-600 add_course_dark_text" v-if="videosData[currentEditingVideoIndex]?.quiz">
                                                {{ videosData[currentEditingVideoIndex].quiz.questions?.length || 0 }} questions configured
                                            </p>
                                            <p class="text-sm text-gray-500 add_course_dark_text" v-else>
                                                Optional: Add a short quiz to show after this video.
                                            </p>
                                        </div>
                                        <div class="flex items-center" style="gap: 8px;">
                                            <button v-if="videosData[currentEditingVideoIndex]?.quiz" @click="openVideoQuizModal(true)" class="px-3 py-1.5 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Edit Quiz</button>
                                            <button v-if="videosData[currentEditingVideoIndex]?.quiz" @click="removeVideoQuiz()" class="px-3 py-1.5 text-white bg-red-600 rounded-lg hover:bg-red-700">Remove</button>
                                            <button v-else @click="openVideoQuizModal(false)" class="px-3 py-1.5 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Add Quiz</button>
                                        </div>
                                    </div>
                                </div>

                                   <div class="relative">
                                        <label for="thumbnail" class="block mb-2 font-medium flex add_course_dark_text" style=" color: black; font-size: 16px; font-weight: 600;">Thumbnail </label>
                                        <input
                                            type="file"
                                            id="thumbnail-upload"
                                            ref="thumbnailUploadInput"
                                            class="hidden"
                                            accept="image/*"
                                            @change="handleThumbnailUpload"
                                        />
                                        <p v-if="videosData[currentEditingVideoIndex]?.errors?.thumbnailFile" class="text-red-500 text-sm mt-1 text-center" style="display: flex; justify-content: flex-start; text-align: start;">{{ videosData[currentEditingVideoIndex].errors.thumbnailFile }}</p>
                                        <template v-if="activeThumbnailPreviewForRightPanel">
                                            <div class="relative mb-2" style="width: 178px;">
                                                <img 
                                                    :src="activeThumbnailPreviewForRightPanel" 
                                                    alt="Thumbnail preview" 
                                                    class="w-full h-auto rounded-md border border-gray-300"
                                                    style="max-height: 150px; object-fit: contain;"
                                                />
                                                <button
                                                    @click.stop="removeThumbnail"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"
                                                    style="font-size: 14px;"
                                                    title="Remove thumbnail"
                                                >
                                                    ×
                                                </button>
                                            </div>
                                            <label
                                                for="thumbnail-upload"
                                                class="block p-2 text-center cursor-pointer hover:bg-gray-50 add_course_dark_input_box"
                                                style="background-color: #9fd3f5; width: 178px; height: 40px; text-align: center; justify-content: center; align-items: center; display: flex; border-radius: 8px; color: black; font-size: 12px; font-weight: 600;"
                                            >
                                                Replace Thumbnail
                                            </label>
                                        </template>
                                        <template v-else>
                                            <label
                                                for="thumbnail-upload"
                                                class="block  p-2 text-center   cursor-pointer hover:bg-gray-50 add_course_dark_input_box"
                                                style="background-color: #9fd3f5; width: 178px; height: 79px; text-align: center; justify-content: center; align-items: center; display: flex; border-radius: 8px; color: black; font-size: 12px; font-weight: 600;"
                                            >
                                                Add your Thumbnail
                                            </label>
                                        </template>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-6">

                                        <div class="border border-gray-300 rounded-md">
                                            <!-- Hidden file input for this preview's upload button -->
                                            <input type="file" ref="videoUploadInputForPreview" @change="handleVideoUploadFromRightPanel" accept="video/*" class="hidden">

                                            <!-- Visual Preview Area -->
                                            <div class="">
                                                <template v-if="!videosData[currentEditingVideoIndex] || (!videosData[currentEditingVideoIndex].videoFile && !videosData[currentEditingVideoIndex].videoFilePreview)">
                                                    <!-- Show Upload Video Button if no video in form -->
                                                    <button
                                                        @click="triggerVideoUploadFromRightPanel"
                                                        class="px-4 py-2 text-black flex items-center justify-center w-full bg-[#9fd3f5] add_course_dark_input_box"
                                                        style="height: 150px; width: 100%;"
                                                    >
                                                        Upload Video for Video {{ currentEditingVideoIndex + 1 }}
                                                    </button>
                                                    <p v-if="videosData[currentEditingVideoIndex]?.errors?.videoFile" class="text-red-500 text-sm mt-1 text-center">
                                                        {{ videosData[currentEditingVideoIndex].errors.videoFile }}
                                                    </p>
                                                </template>
                                                <template v-else>
                                                    <!-- Video exists, show video player -->
                                                    <video v-if="activeVideoPreviewForRightPanel" :src="activeVideoPreviewForRightPanel" controls class="w-full h-auto" style="max-height: 200px; display: block;"></video>
                                                    <!-- Fallback if videoPreview isn't ready but form.video is -->
                                                    <div v-else class="flex items-center justify-center w-full bg-gray-100" style="height: 170px;">Video processing...</div>
                                                    <button
                                                        type="button"
                                                        @click="triggerVideoUploadFromRightPanel"
                                                        class="mt-3 w-full px-3 py-2 text-sm font-semibold text-white bg-[#22c55e] rounded-md hover:bg-[#16a34a]"
                                                    >
                                                        Replace Video
                                                    </button>
                                                </template>
                                            </div>

                                            <!-- Details Section (Video Link & File Name) - only if video is present in form -->
                                            <div
                                                v-if="videosData[currentEditingVideoIndex] && (videosData[currentEditingVideoIndex].videoFile || videosData[currentEditingVideoIndex].videoFilePreview)"
                                                style="background-color: #BEBCBC; font-size: 10px; padding: 5px;"
                                                class="add_course_dark_input_box"
                                            >
                                                <div class="flex justify-between" >
                                                    Video link
                                                    <img src="/images/copy_icon.svg" alt="Copy Icon" class=" cursor-pointer" />
                                                </div>
                                                <div style="color: #2C15F5; word-break: break-all;">
                                                    <template v-if="videosData[currentEditingVideoIndex].videoFile">
                                                        {{ 'vid.example.com/' + videosData[currentEditingVideoIndex].videoFile.name }}
                                                    </template>
                                                    <template v-else>
                                                        {{ videosData[currentEditingVideoIndex].videoFilePreview }}
                                                    </template>
                                                </div>
                                                <div >
                                                    File Name
                                                </div>
                                                <div style="word-break: break-all;">
                                                    <template v-if="videosData[currentEditingVideoIndex].videoFile">
                                                        {{ videosData[currentEditingVideoIndex].videoFile.name }}
                                                    </template>
                                                    <template v-else>
                                                        {{ videosData[currentEditingVideoIndex].videoFilePreview?.split('/').pop() }}
                                                    </template>
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
                                    class="px-4 py-2 text-white bg-[#22c55e] rounded-md hover:bg-[#16a34a]" style="background-color: #22c55e; color: white; font-size: 14px; border-radius: 20px; font-weight: 600;">
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- Step 3 intentionally skipped (per-video quiz handled in Step 2) -->

                        <!-- Step 4: Visibility (Summary) -->
                        <div v-if="currentStep === 4" class="p-6 upload_video_section" >
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
                                       <p class="text-sm font-medium text-gray-700 add_course_dark_text">Description:</p>
                                       <p class="text-sm text-gray-600 whitespace-pre-wrap add_course_dark_text">{{ video.description || '(Not provided)' }}</p>
                                   </div>

                               </div>
                           </div>

                           <div class="flex flex-col items-end mt-8 space-y-4">
                               <!-- Incomplete data warning -->
                               <div v-if="!isCourseComplete" class="w-full p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                   <div class="flex items-start">
                                       <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                           <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                       </svg>
                                       <div class="flex-1">
                                           <h4 class="text-sm font-semibold text-yellow-800 mb-1">Incomplete Course Data</h4>
                                           <p class="text-sm text-yellow-700 mb-2">You still haven't completed all required data. The course will remain as draft until all fields are filled.</p>
                                           <div class="text-xs text-yellow-600">
                                               <strong>Missing:</strong> {{ incompleteFieldsMessage.join(', ') }}
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               
                               <div class="flex space-x-4">
                                   <button
                                       @click="prevStep"
                                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" style="  font-size: 14px; border-radius: 20px; font-weight: 600;">
                                       Back
                                   </button>
                                   <button
                                       @click="submitForm"
                                       :disabled="!isCourseComplete"
                                       :class="[
                                           'px-4 py-2 text-white rounded-md',
                                           isCourseComplete 
                                               ? 'bg-blue-600 hover:bg-blue-700 cursor-pointer' 
                                               : 'bg-gray-400 cursor-not-allowed opacity-60'
                                       ]"
                                       style="font-size: 14px; border-radius: 20px; font-weight: 600;"
                                       :title="!isCourseComplete ? 'Please complete all required fields before publishing' : ''">
                                       Publish
                                   </button>
                               </div>
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
                    <button @click="submitNewCertificate" class="px-4 py-2 text-white bg-[#22c55e] rounded-md hover:bg-[#16a34a]">Add Certificate</button>
                </div>
            </div>
        </div>

        <!-- Video Quiz Builder Modal -->
        <div v-if="showVideoQuizModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="closeVideoQuizModal" style="z-index:500;">
            <div class="p-6 bg-white rounded-lg shadow-xl w-full" style="max-width: 800px; max-height: 85vh;">
                <h3 class="mb-4 text-xl font-semibold">{{ isEditingVideoQuiz ? 'Edit' : 'Add' }} Quiz for Video {{ currentEditingVideoIndex + 1 }}</h3>
                <div class="max-h-[65vh] overflow-y-auto pr-1">
                <div class="mb-4">
                    <label class="block mb-2 font-medium">Quiz Title</label>
                    <input type="text" v-model="videoQuizForm.title" class="w-full p-2 border rounded-md" style="color:black;">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-medium">Quiz Description</label>
                    <textarea v-model="videoQuizForm.description" class="w-full p-2 border rounded-md" style="color:black;"></textarea>
                </div>
                <h4 class="mb-3 text-lg font-semibold">Questions</h4>
                    <div v-for="(question, qIndex) in videoQuizForm.questions" :key="qIndex" class="mb-4 p-4 border rounded-md">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block font-medium">Question {{ qIndex + 1 }}</label>
                        <button @click="removeVideoQuizQuestion(qIndex)" class="text-red-500 hover:text-red-700">Remove</button>
                    </div>
                    <input type="text" v-model="question.question_text" class="w-full p-2 border rounded-md mb-2" style="color:black;" placeholder="Enter question text">
                    <h5 class="mb-2 font-semibold">Answers</h5>
                    <div v-for="(answer, aIndex) in question.answers" :key="aIndex" class="flex items-center mb-2">
                        <input
                            type="radio"
                            :name="'video_quiz_correct_' + qIndex"
                            :value="aIndex"
                            :checked="answer.is_correct === true"
                            @change="setVideoQuizCorrectAnswer(qIndex, aIndex)"
                            class="mr-2"
                        >
                        <input type="text" v-model="answer.answer_text" class="w-full p-2 border rounded-md" style="color:black;" placeholder="Answer text">
                        <button @click="removeVideoQuizAnswer(qIndex, aIndex)" class="ml-2 text-red-500 hover:text-red-700">Remove</button>
                    </div>
                    <button @click="addVideoQuizAnswer(qIndex)" class="text-gray-900 hover:text-gray-700">Add Answer</button>
                </div>
                <button @click="addVideoQuizQuestion" class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">Add Question</button>
                </div>

                <div class="flex justify-end mt-6 space-x-2">
                    <button @click="closeVideoQuizModal" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                    <button @click="saveVideoQuiz" class="px-4 py-2 text-white bg-[#22c55e] rounded-md hover:bg-[#16a34a]">Save Quiz</button>
                </div>
            </div>
        </div>

        <footer class="footer_upload_video dark:bg-[#1A2C38] dark:text-white" style=" display: flex; justify-content: space-between; padding: 20px; align-items: baseline; ">
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
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, reactive, onMounted, computed, defineProps, watch } from 'vue';
import Swal from 'sweetalert2';

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
    topics: {
        type: Array,
        default: () => []
    },
    course: {
        type: Object,
        default: null,
    },
    courseSections: {
        type: Array,
        default: () => [],
    },
    courseVideos: {
        type: Array,
        default: () => [],
    },
    courseQuiz: {
        type: Object,
        default: null,
    },
    isEditing: {
        type: Boolean,
        default: false,
    },
});

const errors = ref({});
const currentStep = ref(1);
const uploadedVideo = ref(null);
const videoPreview = ref(null);
const thumbnailPreview = ref(null);

const form = reactive({
    course_title: '',
    course_description: '',
    additional_description: '',
    recomendations: '',
    certificates: '',
    industry: '',
    topic: '',
    course_type: '',
    course_price: '',
    visibility: 'private',
});

const draftCourseId = ref(null);
const autoSaveTimer = ref(null);
const isSavingDraft = ref(false);
const uploadProgress = ref(0); // Track upload progress percentage
const lastSavedAt = ref(null);
// Flag to avoid auto‑save loops when we update state from server responses
const isSyncingDraftFromServer = ref(false);
// Lightweight signature of the last payload we actually sent to the draft API
const lastSentDraftSignature = ref(null);

const isEditingCourse = computed(() => !!props.course);
const pageTitle = computed(() => isEditingCourse.value ? 'Edit Course' : 'Add New Video');
const removedVideoIds = ref([]);
const removedSectionIds = ref([]);

// Sections: { id?, title, order } (id null for new sections)
const sectionsData = ref([]);
const newSectionTitle = ref('');

// Check if course is ready to publish
const isCourseComplete = computed(() => {
    // Check course fields
    const requiredCourseFields = {
        course_title: form.course_title,
        course_description: form.course_description,
        additional_description: form.additional_description,
        recomendations: form.recomendations,
        certificates: form.certificates,
        industry: form.industry,
        topic: form.topic,
        course_type: form.course_type,
    };
    
    for (const [field, value] of Object.entries(requiredCourseFields)) {
        if (!value || (typeof value === 'string' && value.trim() === '')) {
            return false;
        }
    }
    
    // Check videos
    if (videosData.value.length === 0) {
        return false;
    }
    
    // Check each video
    for (const video of videosData.value) {
        if (!video.title || video.title.trim() === '') return false;
        if (!video.description || video.description.trim() === '') return false;
        if (!video.videoFile && !video.videoFilePreview) return false;
        if (!video.thumbnailFile && !video.thumbnailFilePreview) return false;
    }
    
    return true;
});

const incompleteFieldsMessage = computed(() => {
    const missing = [];
    
    // Check course fields
    if (!form.course_title || form.course_title.trim() === '') missing.push('Course Title');
    if (!form.course_description || form.course_description.trim() === '') missing.push('Description');
    if (!form.additional_description || form.additional_description.trim() === '') missing.push('Additional Description');
    if (!form.recomendations || form.recomendations.trim() === '') missing.push('Recommendations');
    if (!form.certificates) missing.push('Certificates');
    if (!form.industry) missing.push('Industry');
    if (!form.topic) missing.push('Topic');
    if (!form.course_type) missing.push('Course Type');
    
    // Check videos
    if (videosData.value.length === 0) {
        missing.push('At least one video');
    } else {
        videosData.value.forEach((video, index) => {
            const videoNum = index + 1;
            if (!video.title || video.title.trim() === '') missing.push(`Video ${videoNum} Title`);
            if (!video.description || video.description.trim() === '') missing.push(`Video ${videoNum} Description`);
            if (!video.videoFile && !video.videoFilePreview) missing.push(`Video ${videoNum} File`);
            if (!video.thumbnailFile && !video.thumbnailFilePreview) missing.push(`Video ${videoNum} Thumbnail`);
        });
    }
    
    return missing;
});

const quizForm = reactive({
    title: '',
    description: '',
    questions: [],
});

// Per-video quiz builder state
const showVideoQuizModal = ref(false);
const isEditingVideoQuiz = ref(false);
const videoQuizForm = reactive({
    title: '',
    description: '',
    questions: [],
});

watch(() => form.course_price, (newValue) => {
    if (newValue < 0) {
        form.course_price = 0;
    }
});

// Helper to log video upload errors to the backend
const logVideoUploadError = async (payload) => {
    try {
        // Validate payload to prevent errors
        if (!payload || typeof payload !== 'object') {
            console.error('Invalid payload for logVideoUploadError:', payload);
            return;
        }

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        // Ensure all required fields are present and valid
        const safePayload = {
            source: payload.source || 'unknown',
            message: String(payload.message || 'Unknown error'),
            course_id: payload.course_id || null,
            video_index: typeof payload.video_index === 'number' ? payload.video_index : null,
            file_name: payload.file_name || null,
            extra: payload.extra && typeof payload.extra === 'object' ? payload.extra : {},
        };

        await fetch(route('courses.logUploadError'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(safePayload),
        });
    } catch (e) {
        console.error('Failed to send upload error log:', e);
    }
};

// Helper to reset video input state when an upload fails
const handleFailedVideoUpload = (friendlyMessage = 'Video upload failed. Please try again.') => {
    try {
        // Clear any unsaved video files (blobs) from the current editing video
        if (currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
            const video = videosData.value[currentEditingVideoIndex.value];

            // Revoke blob URL if present
            if (video.videoFilePreview && typeof video.videoFilePreview === 'string' && video.videoFilePreview.startsWith('blob:')) {
                URL.revokeObjectURL(video.videoFilePreview);
            }

            video.videoFile = null;
            video.videoFilePreview = null;
            video.duration_in_seconds = null;

            // Attach a user-facing error on this video
            if (!video.errors) {
                video.errors = {};
            }
            video.errors.videoFile = friendlyMessage;
        }

        // Clear the underlying file input
        if (videoUploadInputForPreview.value) {
            videoUploadInputForPreview.value.value = '';
        }

        // Show a SweetAlert error to the user
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Upload Error',
                text: friendlyMessage,
            });
        } else {
            console.error('Upload error:', friendlyMessage);
        }
    } catch (e) {
        console.error('Failed to handle failed video upload state:', e);
    }
};

// Cooldown (ms) after a successful save to prevent watch-driven save loops
const SAVE_DRAFT_COOLDOWN_MS = 2500;

// Auto-save functionality
const autoSaveDraft = () => {
    // Don't start a new auto-save while we're syncing server changes
    if (isSavingDraft.value || isSyncingDraftFromServer.value) return;

    // Prevent immediate re-save after a successful save (stops continuous API loop)
    if (lastSavedAt.value && (Date.now() - lastSavedAt.value.getTime()) < SAVE_DRAFT_COOLDOWN_MS) {
        return;
    }
    
    // Clear existing timer
    if (autoSaveTimer.value) {
        clearTimeout(autoSaveTimer.value);
    }
    
    // Debounce: 2.5s so we don't fire too often and avoid loops from server response updates
    autoSaveTimer.value = setTimeout(async () => {
        // Only show the full-screen loader when there is media (video/thumbnail) being uploaded.
        // We check all videos, regardless of the current step, so every new upload (first, second, third, ...)
        // will always trigger the loader while its draft is being saved.
        const hasPendingMedia =
            Array.isArray(videosData.value) &&
            videosData.value.some(
                (video) =>
                    video &&
                    (video.videoFile instanceof File || video.thumbnailFile instanceof File)
            );

        // For text-only auto-saves (step 1 fields etc.), do not block the whole UI
        isSavingDraft.value = hasPendingMedia;
        
        try {
            // Save current video details if on step 3 (videos)
            if (currentStep.value === 3 && currentEditingVideoIndex.value !== -1) {
                saveCurrentVideoDetails();
            }

            // Build a light "signature" of the current draft (without file blobs) to avoid
            // calling the API when nothing meaningful has changed since the last save.
            const currentDraftSignature = JSON.stringify({
                course: {
                    title: form.course_title || '',
                    description: form.course_description || '',
                    additional_description: form.additional_description || '',
                    recomendations: form.recomendations || '',
                    certificates: form.certificates || '',
                    industry: form.industry || '',
                    course_type: form.course_type || '',
                    topic: form.topic || '',
                    price: form.course_price || 0,
                },
                sections: (sectionsData.value || []).map((s, i) => ({ id: s.id, title: s.title, order: s.order ?? i })),
                videos: currentStep.value >= 3
                    ? (videosData.value || []).map((video, index) => ({
                        // Only non-file fields participate in the signature
                        local_index: index,
                        id: video.id || null,
                        title: video.title || '',
                        description: video.description || '',
                        takeaway_notes: video.takeaway_notes || '',
                        order: video.order ?? index + 1,
                        duration_in_seconds: video.duration_in_seconds ?? null,
                        // Quiz (non-file data)
                        quiz: video.quiz
                            ? {
                                title: video.quiz.title || '',
                                description: video.quiz.description || '',
                                questions: (video.quiz.questions || []).map((q) => ({
                                    question_text: q.question_text || '',
                                    answers: (q.answers || []).map((a) => ({
                                        answer_text: a.answer_text || '',
                                        is_correct: !!a.is_correct,
                                    })),
                                })),
                            }
                            : null,
                    }))
                    : [],
                removed_video_ids: removedVideoIds.value || [],
                removed_section_ids: removedSectionIds.value || [],
            });

            // If there is no new media and the signature is unchanged, skip hitting the API
            if (!hasPendingMedia && currentDraftSignature === lastSentDraftSignature.value) {
                return;
            }

            const formData = new FormData();
            formData.append('title', form.course_title || '');
            formData.append('description', form.course_description || '');
            formData.append('additional_description', form.additional_description || '');
            formData.append('recomendations', form.recomendations || '');
            formData.append('certificates', form.certificates || '');
            formData.append('industry', form.industry || '');
            formData.append('course_type', form.course_type || '');
            formData.append('topic', form.topic || '');
            formData.append('price', form.course_price || 0);
            
            if (draftCourseId.value) {
                formData.append('course_id', draftCourseId.value);
            }

            // Sections
            sectionsData.value.forEach((section, idx) => {
                formData.append(`sections[${idx}][title]`, section.title || '');
                formData.append(`sections[${idx}][order]`, String(section.order ?? idx));
                if (section.id) formData.append(`sections[${idx}][id]`, section.id);
            });
            removedSectionIds.value.forEach((id) => formData.append('removed_section_ids[]', id));
            
            // Include removed video IDs for deletion
            if (removedVideoIds.value.length > 0) {
                removedVideoIds.value.forEach((id) => {
                    formData.append('removed_video_ids[]', id);
                });
            }
            
            // Include videos data if on step 3 or later
            if (currentStep.value >= 3 && videosData.value.length > 0) {
                videosData.value.forEach((video, index) => {
                    formData.append(`videos[${index}][title]`, video.title || '');
                    formData.append(`videos[${index}][description]`, video.description || '');
                    formData.append(`videos[${index}][takeaway_notes]`, video.takeaway_notes || '');
                    formData.append(`videos[${index}][order]`, (index + 1).toString());
                    if (video.course_section_id) {
                        formData.append(`videos[${index}][course_section_id]`, video.course_section_id);
                    } else if (video.section_index !== undefined && video.section_index !== null) {
                        formData.append(`videos[${index}][section_index]`, String(video.section_index));
                    }
                    
                    if (video.id) {
                        formData.append(`videos[${index}][id]`, video.id);
                    }
                    
                    // Include video file if it's a new file (not already saved)
                    // Only send if videoFile exists and video doesn't have an ID (new video) or videoFilePreview is a blob (new upload)
                    if (video.videoFile instanceof File) {
                        formData.append(`videos[${index}][videoFile]`, video.videoFile);
                        // Include duration if available
                        if (video.duration_in_seconds) {
                            formData.append(`videos[${index}][duration_in_seconds]`, video.duration_in_seconds.toString());
                        }
                    }
                    
                    // Include thumbnail file if it's a new file
                    if (video.thumbnailFile instanceof File) {
                        formData.append(`videos[${index}][thumbnailFile]`, video.thumbnailFile);
                    }
                    
                    // Include quiz data if present - send as JSON string to avoid nested array issues
                    if (video.quiz && video.quiz.title) {
                        try {
                            const quizData = {
                                title: video.quiz.title || '',
                                description: video.quiz.description || '',
                                questions: (video.quiz.questions || []).map(q => ({
                                    question_text: q.question_text || '',
                                    answers: (q.answers || []).map(a => ({
                                        answer_text: a.answer_text || '',
                                        is_correct: !!a.is_correct
                                    }))
                                }))
                            };
                            formData.append(`videos[${index}][quiz]`, JSON.stringify(quizData));
                        } catch (error) {
                            console.error('Error serializing quiz data:', error);
                        }
                    }
                });
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Use XMLHttpRequest to track upload progress
            const data = await new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                
                // Track upload progress
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        uploadProgress.value = percentComplete;
                    }
                });
                
                // Handle completion
                xhr.addEventListener('load', () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const responseData = JSON.parse(xhr.responseText);
                            resolve({ ok: true, status: xhr.status, json: async () => responseData });
                        } catch (e) {
                            reject(new Error('Failed to parse response'));
                        }
                    } else {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            resolve({ ok: false, status: xhr.status, json: async () => errorData });
                        } catch (e) {
                            resolve({ ok: false, status: xhr.status, json: async () => ({ message: 'Unknown error' }) });
                        }
                    }
                });
                
                // Handle errors
                xhr.addEventListener('error', () => {
                    reject(new Error('Network error occurred'));
                });
                
                xhr.addEventListener('abort', () => {
                    reject(new Error('Upload aborted'));
                });
                
                // Open and send request
                xhr.open('POST', route('courses.saveDraft'));
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken || '');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.send(formData);
            });
            
            if (!data.ok) {
                const errorData = await data.json();
                const message = errorData.message || 'Draft auto-save failed while uploading video.';
                console.error('Auto-save failed:', message);

                // Log to backend for observability
                try {
                    const currentVideo = 
                        currentEditingVideoIndex.value >= 0 &&
                        videosData.value &&
                        Array.isArray(videosData.value) &&
                        videosData.value[currentEditingVideoIndex.value]
                            ? videosData.value[currentEditingVideoIndex.value]
                            : null;

                    await logVideoUploadError({
                        source: 'backend',
                        message,
                        course_id: draftCourseId.value || null,
                        video_index: typeof currentEditingVideoIndex.value === 'number' ? currentEditingVideoIndex.value : null,
                        file_name: currentVideo && currentVideo.videoFile && currentVideo.videoFile instanceof File
                            ? currentVideo.videoFile.name
                            : null,
                        extra: {
                            status: data.status,
                        },
                    });
                } catch (logError) {
                    console.error('Failed to log upload error:', logError);
                }

                // If this auto-save included media, reset the video input state
                if (hasPendingMedia) {
                    handleFailedVideoUpload(message);
                }
                uploadProgress.value = 0; // Reset progress on error
                return;
            }
            
            const responseData = await data.json();
            
            if (responseData.success && responseData.course_id) {
                // While we apply server updates, suppress watcher‑triggered auto‑saves
                isSyncingDraftFromServer.value = true;
                try {
                    if (!draftCourseId.value) {
                        draftCourseId.value = responseData.course_id;
                    }
                    
                    // Update video IDs if returned from backend (to prevent duplicate creation)
                    if (responseData.video_ids && typeof responseData.video_ids === 'object' && responseData.video_ids !== null) {
                        try {
                            Object.keys(responseData.video_ids).forEach((indexStr) => {
                                const index = parseInt(indexStr);
                                const videoId = responseData.video_ids[index];
                                if (videosData.value && Array.isArray(videosData.value) && videosData.value[index] && videoId) {
                                    // Always update video ID to prevent duplicates
                                    videosData.value[index].id = videoId;
                                    // Clear videoFile after successful save to prevent re-upload
                                    if (videosData.value[index].videoFile) {
                                        videosData.value[index].videoFile = null;
                                    }
                                    if (videosData.value[index].thumbnailFile) {
                                        videosData.value[index].thumbnailFile = null;
                                    }
                                }
                            });
                        } catch (e) {
                            console.error('Error updating video IDs:', e);
                        }
                    }

                    // Update section IDs if returned from backend
                    if (Array.isArray(responseData.section_ids) && responseData.section_ids.length > 0) {
                        responseData.section_ids.forEach((id, idx) => {
                            if (sectionsData.value[idx]) sectionsData.value[idx].id = id;
                        });
                        // Update videos' course_section_id from section_index
                        videosData.value.forEach((v) => {
                            if (v.section_index != null && sectionsData.value[v.section_index]?.id) {
                                v.course_section_id = sectionsData.value[v.section_index].id;
                                v.section_index = undefined;
                            }
                        });
                    }

                    if (removedSectionIds.value.length > 0) removedSectionIds.value = [];
                    
                    // Clear removed video IDs after successful save
                    if (removedVideoIds.value.length > 0) {
                        removedVideoIds.value = [];
                    }

                    // Update local "last sent" signature only after a successful save
                    lastSentDraftSignature.value = currentDraftSignature;
                    lastSavedAt.value = new Date();
                } finally {
                    isSyncingDraftFromServer.value = false;
                }
            } else {
                const message = responseData.message || 'Draft auto-save failed while uploading video (no success flag).';
                console.error('Auto-save failed:', message);

                try {
                    const currentVideo = 
                        currentEditingVideoIndex.value >= 0 &&
                        videosData.value &&
                        Array.isArray(videosData.value) &&
                        videosData.value[currentEditingVideoIndex.value]
                            ? videosData.value[currentEditingVideoIndex.value]
                            : null;

                    await logVideoUploadError({
                        source: 'backend',
                        message,
                        course_id: draftCourseId.value || null,
                        video_index: typeof currentEditingVideoIndex.value === 'number' ? currentEditingVideoIndex.value : null,
                        file_name: currentVideo && currentVideo.videoFile && currentVideo.videoFile instanceof File
                            ? currentVideo.videoFile.name
                            : null,
                        extra: {
                            success: responseData.success ?? null,
                        },
                    });
                } catch (logError) {
                    console.error('Failed to log upload error:', logError);
                }

                if (hasPendingMedia) {
                    handleFailedVideoUpload(message);
                }
                uploadProgress.value = 0; // Reset progress on error
            }
        } catch (error) {
            console.error('Auto-save failed:', error);

            const message =
                (error && error.message) ||
                'Unexpected error occurred while auto-saving draft and uploading video.';

            try {
                const currentVideo = 
                    currentEditingVideoIndex.value >= 0 &&
                    videosData.value &&
                    Array.isArray(videosData.value) &&
                    videosData.value[currentEditingVideoIndex.value]
                        ? videosData.value[currentEditingVideoIndex.value]
                        : null;

                await logVideoUploadError({
                    source: 'frontend',
                    message,
                    course_id: draftCourseId.value || null,
                    video_index: typeof currentEditingVideoIndex.value === 'number' ? currentEditingVideoIndex.value : null,
                    file_name: currentVideo && currentVideo.videoFile && currentVideo.videoFile instanceof File
                        ? currentVideo.videoFile.name
                        : null,
                    extra: {},
                });
            } catch (logError) {
                console.error('Failed to log upload error:', logError);
            }

            if (hasPendingMedia) {
                handleFailedVideoUpload(message);
            }
            uploadProgress.value = 0; // Reset progress on error
        } finally {
            isSavingDraft.value = false;
            uploadProgress.value = 0; // Reset progress when done
        }
    }, 2500); // Debounce 2.5s to avoid continuous save-draft loop
};

// Watch form fields for auto-save (Step 1 - Course details)
watch([
    () => form.course_title,
    () => form.course_description,
    () => form.additional_description,
    () => form.recomendations,
    () => form.certificates,
    () => form.industry,
    () => form.topic,
    () => form.course_type,
], () => {
    if (currentStep.value === 1 && !isSyncingDraftFromServer.value) {
        autoSaveDraft();
    }
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
    takeaway_notes: '',
    playlist: '',
    visibility: 'private', // Added visibility here as it was used in saveCurrentVideoDetails
});

// Watch video form fields for auto-save (Step 2 - Video details)
watch([
    () => currentVideoFormPart2.title,
    () => currentVideoFormPart2.description,
    () => currentVideoFormPart2.takeaway_notes,
], () => {
    if (currentStep.value === 3 && currentEditingVideoIndex.value !== -1 && !isSyncingDraftFromServer.value) {
        autoSaveDraft();
    }
});

// Watch videosData only for structural changes (add/remove video) to avoid continuous
// save-draft from every deep change (e.g. videoFile, id) which caused API loops.
// Quiz/thumbnail/file changes trigger explicit autoSaveDraft() in their handlers.
watch(
    () => (currentStep.value >= 2 ? videosData.value.length : 0),
    () => {
        if (currentStep.value >= 2 && !isSyncingDraftFromServer.value) {
            autoSaveDraft();
        }
    }
);

const activeVideoPreviewForRightPanel = ref(null);
const activeThumbnailPreviewForRightPanel = ref(null);

// Refs for template refs
const thumbnailUploadInput = ref(null);
const videoUploadInputForPreview = ref(null);

// Add these new refs and functions
const activeDropdown = ref(null);
const selectedOptions = reactive({
    certificates: { value: '', text: '' },
    topic: { value: '', text: '' },
    industry: { value: '', text: '' },
    course_type: { value: '', text: '' }
});

const getOptionsForDropdown = (dropdownName) => {
    const sources = {
        certificates: props.certificates || [],
        topic: props.topics || [],
        industry: props.industries || [],
        course_type: props.courseTypes || [],
    };
    return sources[dropdownName] || [];
};

const setSelectedOptionById = (dropdownName, value) => {
    const isEmptyValue = value === null || value === undefined || value === '';
    if (isEmptyValue) {
        selectedOptions[dropdownName] = { value: '', text: '' };
        form[dropdownName] = '';
        return;
    }
    const options = getOptionsForDropdown(dropdownName);
    const match = options.find((option) => String(option.value) === String(value));
    if (match) {
        selectedOptions[dropdownName] = { value: match.value, text: match.text };
        form[dropdownName] = match.value;
    } else {
        selectedOptions[dropdownName] = { value: '', text: '' };
        form[dropdownName] = '';
    }
};

const toggleDropdown = (dropdownName) => {
    activeDropdown.value = activeDropdown.value === dropdownName ? null : dropdownName;
};

const selectOption = (dropdownName, value, text) => {
    selectedOptions[dropdownName] = { value, text };
    form[dropdownName] = value;
    activeDropdown.value = null;
};

const getSelectedText = (dropdownName) => {
    return selectedOptions[dropdownName].text;
};

const initializeEditingState = () => {
    if (!props.course) {
        return;
    }

    draftCourseId.value = props.course.id;
    form.course_title = props.course.title || '';
    form.course_description = props.course.description || '';
    form.additional_description = props.course.additional_description || '';
    form.recomendations = props.course.recomendations || '';
    form.course_price = props.course.price ?? '';
    form.certificates = props.course.certificate_id ?? '';
    form.industry = props.course.industry_id ?? '';
    form.course_type = props.course.course_type_id ?? '';
    form.topic = props.course.topic_id ?? '';

    setSelectedOptionById('certificates', props.course.certificate_id);
    setSelectedOptionById('industry', props.course.industry_id);
    setSelectedOptionById('course_type', props.course.course_type_id);
    setSelectedOptionById('topic', props.course.topic_id);

    removedVideoIds.value = [];
    removedSectionIds.value = [];

    sectionsData.value = (props.courseSections || []).map((s, i) => ({
        id: s.id,
        title: s.title || '',
        order: s.order ?? i,
    }));

    const orderedVideos = (props.courseVideos || [])
        .slice()
        .sort((a, b) => ((a.order ?? 0) - (b.order ?? 0)));

    videosData.value = orderedVideos.map((video, index) => ({
        id: video.id,
        course_section_id: video.course_section_id ?? null,
        section_index: undefined,
        title: video.title || '',
        description: video.description || '',
        takeaway_notes: video.takeaway_notes || '',
        videoFile: null,
        videoFilePreview: video.video_url || video.video_path || null,
        thumbnailFile: null,
        thumbnailFilePreview: video.thumbnail_url || video.thumbnail_path || null,
        playlist: '',
        visibility: 'private',
        duration_in_seconds: video.duration_in_seconds ?? video.duration ?? null,
        order: video.order ?? index + 1,
        errors: {},
        quiz: video.quiz
            ? {
                title: video.quiz.title || '',
                description: video.quiz.description || '',
                questions: (video.quiz.questions || []).map((question) => ({
                    question_text: question.question_text || '',
                    answers: (question.answers || []).map((answer) => ({
                        answer_text: answer.answer_text || '',
                        is_correct: !!answer.is_correct,
                    })),
                })),
            }
            : null,
    }));

    if (videosData.value.length > 0) {
        currentEditingVideoIndex.value = 0;
        populateVideoDetailsForm(0);
    } else {
        currentEditingVideoIndex.value = -1;
    }

    if (props.courseQuiz) {
        quizForm.title = props.courseQuiz.title || '';
        quizForm.description = props.courseQuiz.description || '';
        quizForm.questions = (props.courseQuiz.questions || []).map((question) => ({
            question_text: question.question_text || '',
            answers: (question.answers || []).map((answer) => ({
                answer_text: answer.answer_text || '',
                is_correct: !!answer.is_correct,
            })),
        }));
    } else {
        quizForm.title = '';
        quizForm.description = '';
        quizForm.questions = [];
    }

    currentStep.value = 1;
};

const saveCurrentVideoDetails = () => {
    if (currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        videosData.value[currentEditingVideoIndex.value].title = currentVideoFormPart2.title;
        videosData.value[currentEditingVideoIndex.value].description = currentVideoFormPart2.description;
        videosData.value[currentEditingVideoIndex.value].takeaway_notes = currentVideoFormPart2.takeaway_notes;
        videosData.value[currentEditingVideoIndex.value].playlist = currentVideoFormPart2.playlist;
        videosData.value[currentEditingVideoIndex.value].visibility = currentVideoFormPart2.visibility;
        
        // Trigger auto-save after saving video details
        if (currentStep.value >= 3) {
            autoSaveDraft();
        }
    }
};

const populateVideoDetailsForm = (index) => {
    if (index >= 0 && videosData.value[index]) {
        const video = videosData.value[index];
        // Populate form fields
        currentVideoFormPart2.title = video.title || '';
        currentVideoFormPart2.description = video.description || '';
        currentVideoFormPart2.takeaway_notes = video.takeaway_notes || '';
        currentVideoFormPart2.playlist = video.playlist || '';
        currentVideoFormPart2.visibility = video.visibility || 'private';
        
        // Set preview URLs - handle both asset URLs and paths
        if (video.videoFilePreview) {
            activeVideoPreviewForRightPanel.value = video.videoFilePreview;
        } else if (video.video_url) {
            activeVideoPreviewForRightPanel.value = video.video_url;
        } else {
            activeVideoPreviewForRightPanel.value = null;
        }
        
        if (video.thumbnailFilePreview) {
            activeThumbnailPreviewForRightPanel.value = video.thumbnailFilePreview;
        } else if (video.thumbnail_url) {
            activeThumbnailPreviewForRightPanel.value = video.thumbnail_url;
        } else {
            activeThumbnailPreviewForRightPanel.value = null;
        }
    } else {
        currentVideoFormPart2.title = '';
        currentVideoFormPart2.description = '';
        currentVideoFormPart2.takeaway_notes = '';
        currentVideoFormPart2.playlist = '';
        currentVideoFormPart2.visibility = 'private';
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

const addNewVideoSlot = (sectionIdx = 0) => {
    saveCurrentVideoDetails();
    const section = sectionsData.value[sectionIdx];
    // When adding first video from "Upload Video" panel, preserve any text already entered in the form
    const isFirstVideo = videosData.value.length === 0;
    const newVideoData = {
        videoFile: null,
        videoFilePreview: null,
        title: isFirstVideo ? (currentVideoFormPart2.title || '') : '',
        description: isFirstVideo ? (currentVideoFormPart2.description || '') : '',
        takeaway_notes: isFirstVideo ? (currentVideoFormPart2.takeaway_notes || '') : '',
        thumbnailFile: null,
        thumbnailFilePreview: null,
        playlist: isFirstVideo ? (currentVideoFormPart2.playlist || '') : '',
        visibility: isFirstVideo ? (currentVideoFormPart2.visibility || 'private') : 'private',
        duration_in_seconds: null,
        errors: {},
        quiz: null,
        course_section_id: section?.id ?? null,
        section_index: section ? sectionIdx : undefined,
    };
    videosData.value.push(newVideoData);
    currentEditingVideoIndex.value = videosData.value.length - 1;
    populateVideoDetailsForm(currentEditingVideoIndex.value);

    if (currentStep.value >= 3 && draftCourseId.value) {
        autoSaveDraft();
    }
};

function getVideoSectionIndex(video) {
    if (video.course_section_id != null) {
        const idx = sectionsData.value.findIndex(s => s.id === video.course_section_id);
        return idx >= 0 ? idx : (video.section_index ?? 0);
    }
    return video.section_index ?? 0;
}

function getVideosInSection(sectionIdx) {
    const list = [];
    videosData.value.forEach((video, globalIndex) => {
        if (getVideoSectionIndex(video) === sectionIdx) list.push({ globalIndex, video });
    });
    return list;
}

function addSection() {
    const title = (newSectionTitle.value || '').trim();
    if (!title) return;
    sectionsData.value.push({ id: null, title, order: sectionsData.value.length });
    newSectionTitle.value = '';
}

function removeSection(idx) {
    const section = sectionsData.value[idx];
    if (section?.id) removedSectionIds.value.push(section.id);
    sectionsData.value.splice(idx, 1);
    videosData.value.forEach(v => {
        const vSectionIdx = getVideoSectionIndex(v);
        if (vSectionIdx === idx) {
            v.course_section_id = null;
            v.section_index = sectionsData.value.length > 0 ? 0 : undefined;
        } else if (typeof v.section_index === 'number' && v.section_index > idx) {
            v.section_index--;
        } else if (v.course_section_id && sectionsData.value.some(s => s.id === v.course_section_id)) {
            const newIdx = sectionsData.value.findIndex(s => s.id === v.course_section_id);
            if (newIdx >= 0) v.section_index = newIdx;
        }
    });
}

const handleVideoUpload = async (e) => {
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
            if (currentVideo.videoFilePreview && currentVideo.videoFilePreview.startsWith('blob:')) {
                URL.revokeObjectURL(currentVideo.videoFilePreview);
            }
            currentVideo.videoFile = file;
            currentVideo.videoFilePreview = URL.createObjectURL(file);
            activeVideoPreviewForRightPanel.value = currentVideo.videoFilePreview;

            // Get and set duration
            currentVideo.duration_in_seconds = await getVideoDurationFromFile(file);
            console.log(`Duration for ${file.name}: ${currentVideo.duration_in_seconds}s`);
        }
        nextStep();

        // Ensure this new upload always triggers a draft save & loader
        if (currentStep.value >= 2) {
            autoSaveDraft();
        }
    }
};
function getVideoDurationFromFile(file) {
    return new Promise((resolve, reject) => {
        const video = document.createElement('video');
        video.preload = 'metadata';

        video.onloadedmetadata = function () {
            window.URL.revokeObjectURL(video.src);
            const duration = video.duration;
            resolve(Math.floor(duration)); // Rounded down to integer seconds
        };

        video.onerror = function () {
            reject('Failed to load video metadata.');
        };

        video.src = URL.createObjectURL(file);
    });
}

const handleThumbnailUpload = (e) => {
    const file = e.target.files[0];
    if (file && currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        const currentVideo = videosData.value[currentEditingVideoIndex.value];
        if (currentVideo.thumbnailFilePreview && currentVideo.thumbnailFilePreview.startsWith('blob:')) {
            URL.revokeObjectURL(currentVideo.thumbnailFilePreview);
        }
        currentVideo.thumbnailFile = file;
        currentVideo.thumbnailFilePreview = URL.createObjectURL(file);
        activeThumbnailPreviewForRightPanel.value = currentVideo.thumbnailFilePreview;

        if (thumbnailUploadInput.value) {
            thumbnailUploadInput.value.value = '';
        }
        
        // Trigger auto-save after thumbnail upload
        if (currentStep.value >= 3) {
            autoSaveDraft();
        }
    }
};

const removeThumbnail = () => {
    if (currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        const currentVideo = videosData.value[currentEditingVideoIndex.value];
        
        // Revoke blob URL if present
        if (currentVideo.thumbnailFilePreview && currentVideo.thumbnailFilePreview.startsWith('blob:')) {
            URL.revokeObjectURL(currentVideo.thumbnailFilePreview);
        }
        
        // Clear thumbnail data
        currentVideo.thumbnailFile = null;
        currentVideo.thumbnailFilePreview = null;
        activeThumbnailPreviewForRightPanel.value = null;

        // Clear the input
        if (thumbnailUploadInput.value) {
            thumbnailUploadInput.value.value = '';
        }
        
        // Clear any errors
        if (currentVideo.errors && currentVideo.errors.thumbnailFile) {
            delete currentVideo.errors.thumbnailFile;
        }
        
        // Trigger auto-save after removing thumbnail
        if (currentStep.value >= 3) {
            autoSaveDraft();
        }
    }
};

const nextStep = () => {
    errors.value = {}; // Clear previous step 1 errors

    if (currentStep.value === 3) {
        saveCurrentVideoDetails();
    }

    if (currentStep.value === 1) {
        currentStep.value = 2;
    } else if (currentStep.value === 2) {
        if (sectionsData.value.length === 0) {
            sectionsData.value.push({ id: null, title: 'Section 1', order: 0 });
        }
        currentStep.value = 3;
    } else if (currentStep.value === 3) {
        currentStep.value = 4;
    } else {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value >= 3 && currentEditingVideoIndex.value !== -1) {
        saveCurrentVideoDetails();
    }
    if (currentStep.value > 1) currentStep.value--;
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

const handleVideoUploadFromRightPanel = async (e) => {
    const file = e.target.files[0];
    if (file && currentEditingVideoIndex.value >= 0 && videosData.value[currentEditingVideoIndex.value]) {
        const currentVideo = videosData.value[currentEditingVideoIndex.value];
        if (currentVideo.videoFilePreview && currentVideo.videoFilePreview.startsWith('blob:')) {
            URL.revokeObjectURL(currentVideo.videoFilePreview);
        }
        currentVideo.videoFile = file;
        currentVideo.videoFilePreview = URL.createObjectURL(file);
        activeVideoPreviewForRightPanel.value = currentVideo.videoFilePreview;

        // Get and set duration
        currentVideo.duration_in_seconds = await getVideoDurationFromFile(file);
        console.log(`Duration for ${file.name} (right panel): ${currentVideo.duration_in_seconds}s`);

        if (videoUploadInputForPreview.value) {
            videoUploadInputForPreview.value.value = '';
        }
        
        // Trigger auto-save after video upload
        if (currentStep.value >= 3) {
            autoSaveDraft();
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
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Certificate title cannot be empty.',
        });
        return;
    }
    const newCert = {
        value: newCertificate.title.toLowerCase().replace(/\s+/g, '-') + '-' + Date.now(),
        text: newCertificate.title.trim(),
        description: newCertificate.description.trim()
    };


    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'New certificate created locally. Please ensure your backend saves this and refreshes the certificate list.',
    });


    form.certificates = newCert.value; // This will select the newly "added" certificate.
    showCertificatePopup.value = false;
};

const submitForm = async () => {
    saveCurrentVideoDetails();

    // Validate all required fields before publishing
    errors.value = {};
    const requiredFields = {
        course_title: 'Course Title',
        course_description: 'Description',
        additional_description: 'Additional Description',
        recomendations: 'Recomendations',
        certificates: 'Certificates',
        industry: 'Industry',
        topic: 'Topic',
        course_type: 'Course Type',
    };

    Object.entries(requiredFields).forEach(([field, name]) => {
        const value = form[field];
        let isMissing = false;
        
        isMissing = !value || (typeof value === 'string' && value.trim() === '');
        
        if (isMissing) {
            errors.value[field] = `${name} is required.`;
        }
    });

    // Validate videos
    if (videosData.value.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please add at least one video before publishing.',
        });
        currentStep.value = 2;
        return;
    }

    let hasVideoErrors = false;
    videosData.value.forEach((video, index) => {
        video.errors = {};
        if (!video.title || video.title.trim() === '') {
            video.errors.title = 'Title is required.';
            hasVideoErrors = true;
        }
        if (!video.description || video.description.trim() === '') {
            video.errors.description = 'Description is required.';
            hasVideoErrors = true;
        }
        if (!video.videoFile && !video.videoFilePreview) {
            video.errors.videoFile = 'Video file is required.';
            hasVideoErrors = true;
        }
        // Thumbnail is now required for publishing
        if (!video.thumbnailFile && !video.thumbnailFilePreview) {
            video.errors.thumbnailFile = 'Thumbnail is required for publishing.';
            hasVideoErrors = true;
        }
        
        // Validate quiz - if any quiz field is filled, quiz must be complete
        if (video.quiz) {
            const quiz = video.quiz;
            let hasQuizData = false;
            let quizIncomplete = false;
            
            // Check if any quiz field is filled
            if (quiz.title && quiz.title.trim() !== '') hasQuizData = true;
            if (quiz.description && quiz.description.trim() !== '') hasQuizData = true;
            if (quiz.questions && quiz.questions.length > 0) hasQuizData = true;
            
            // If any quiz data exists, validate it's complete
            if (hasQuizData) {
                // Title is required if quiz exists
                if (!quiz.title || quiz.title.trim() === '') {
                    video.errors.quiz = 'Quiz title is required if quiz is added.';
                    quizIncomplete = true;
                }
                
                // If questions exist, they must be complete
                if (quiz.questions && quiz.questions.length > 0) {
                    quiz.questions.forEach((question, qIndex) => {
                        if (!question.question_text || question.question_text.trim() === '') {
                            video.errors.quiz = `Question ${qIndex + 1} text is required.`;
                            quizIncomplete = true;
                        }
                        
                        if (!question.answers || question.answers.length < 2) {
                            video.errors.quiz = `Question ${qIndex + 1} must have at least 2 answers.`;
                            quizIncomplete = true;
                        } else {
                            // Check each answer has text
                            question.answers.forEach((answer, aIndex) => {
                                if (!answer.answer_text || answer.answer_text.trim() === '') {
                                    video.errors.quiz = `Question ${qIndex + 1}, Answer ${aIndex + 1} text is required.`;
                                    quizIncomplete = true;
                                }
                            });
                            
                            // Check at least one answer is correct
                            const hasCorrectAnswer = question.answers.some(a => a.is_correct === true);
                            if (!hasCorrectAnswer) {
                                video.errors.quiz = `Question ${qIndex + 1} must have at least one correct answer.`;
                                quizIncomplete = true;
                            }
                        }
                    });
                }
                
                if (quizIncomplete) {
                    hasVideoErrors = true;
                }
            }
        }
    });

    if (Object.keys(errors.value).length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: 'Please fill in all required fields before publishing:<br>' + 
                  Object.values(errors.value).join('<br>'),
        });
        currentStep.value = 1;
        return;
    }

        if (hasVideoErrors) {
        const missingItems = [];
        videosData.value.forEach((video, index) => {
            if (Object.keys(video.errors).length > 0) {
                const videoNum = index + 1;
                if (video.errors.title) missingItems.push(`Video ${videoNum}: Title is missing`);
                if (video.errors.description) missingItems.push(`Video ${videoNum}: Description is missing`);
                if (video.errors.videoFile) missingItems.push(`Video ${videoNum}: Video file is missing`);
                if (video.errors.thumbnailFile) missingItems.push(`Video ${videoNum}: Thumbnail is missing`);
                if (video.errors.quiz) missingItems.push(`Video ${videoNum}: ${video.errors.quiz}`);
            }
        });
        
        Swal.fire({
            icon: 'error',
            title: 'Incomplete Course Data',
            html: '<strong>You still haven\'t completed all required data:</strong><br><br>' + 
                  missingItems.join('<br>') + 
                  '<br><br>Please complete all fields before publishing. The course will remain as draft until all data is filled.',
            confirmButtonText: 'OK',
        });
        currentStep.value = 2;
        const firstErrorIndex = videosData.value.findIndex(v => Object.keys(v.errors).length > 0);
        if (firstErrorIndex !== -1) {
            selectVideoToEdit(firstErrorIndex);
        }
        return;
    }

    const formData = new FormData();

    // Append course details
    formData.append('title', form.course_title);
    formData.append('description', form.course_description);
    formData.append('price', form.course_price || 0);
    // Add other course fields from the 'form' object as necessary
    formData.append('additional_description', form.additional_description);
    formData.append('recomendations', form.recomendations);
    formData.append('certificates', form.certificates);
    formData.append('industry', form.industry);
    formData.append('course_type', form.course_type);
    formData.append('topic', form.topic);

    // Append videos data
    if (videosData.value && videosData.value.length > 0) {
        videosData.value.forEach((video, index) => {
            if (video.id) {
                formData.append(`videos[${index}][id]`, video.id);
            }
            formData.append(`videos[${index}][title]`, video.title || '');
            formData.append(`videos[${index}][description]`, video.description || '');
            formData.append(`videos[${index}][takeaway_notes]`, video.takeaway_notes || '');
            if (video.videoFile instanceof File) {
                formData.append(`videos[${index}][videoFile]`, video.videoFile);
            }
            const orderValue = index + 1;
            formData.append(`videos[${index}][order]`, orderValue.toString());
            // Append duration if available
            if (video.duration_in_seconds !== null && video.duration_in_seconds !== undefined) {
                formData.append(`videos[${index}][duration_in_seconds]`, video.duration_in_seconds.toString());
            }
            // Example for appending a thumbnail if it exists
            if (video.thumbnailFile instanceof File) {
                formData.append(`videos[${index}][thumbnailFile]`, video.thumbnailFile);
            }

            // Append per-video quiz if present
            if (video.quiz) {
                formData.append(`videos[${index}][quiz][title]`, video.quiz.title || '');
                formData.append(`videos[${index}][quiz][description]`, video.quiz.description || '');
                (video.quiz.questions || []).forEach((q, qIndex) => {
                    formData.append(`videos[${index}][quiz][questions][${qIndex}][question_text]`, q.question_text || '');
                    (q.answers || []).forEach((a, aIndex) => {
                        formData.append(`videos[${index}][quiz][questions][${qIndex}][answers][${aIndex}][answer_text]`, a.answer_text || '');
                        formData.append(`videos[${index}][quiz][questions][${qIndex}][answers][${aIndex}][is_correct]`, a.is_correct ? 1 : 0);
                    });
                });
            }
        });
    } else {
        // If there are no videos, we might need to send an empty array or a specific flag
        // depending on backend validation (e.g., 'videos' => 'present|array').
        // Sending an empty array indicator if backend expects 'videos' key even if empty.
        formData.append('videos', JSON.stringify([]));
    }

    if (removedVideoIds.value.length > 0) {
        removedVideoIds.value.forEach((id) => {
            formData.append('removed_video_ids[]', id);
        });
    }

    if (quizForm.title) {
        formData.append('quiz[title]', quizForm.title);
        formData.append('quiz[description]', quizForm.description);
        quizForm.questions.forEach((question, qIndex) => {
            formData.append(`quiz[questions][${qIndex}][question_text]`, question.question_text);
            question.answers.forEach((answer, aIndex) => {
                formData.append(`quiz[questions][${qIndex}][answers][${aIndex}][answer_text]`, answer.answer_text);
                formData.append(`quiz[questions][${qIndex}][answers][${aIndex}][is_correct]`, answer.is_correct ? 1 : 0);
            });
        });
    }

    // Use draft course ID if available, otherwise use the course from props
    const courseIdToUse = draftCourseId.value || (isEditingCourse.value && props.course ? props.course.id : null);
    
    const submissionRoute = courseIdToUse
        ? route('courses.updateWithVideos', { course: courseIdToUse })
        : route('courses.storeWithVideos');

    try {
        router.post(submissionRoute, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: () => {
                removedVideoIds.value = [];
                Swal.fire({
                    icon: 'success',
                    title: isEditingCourse.value ? 'Course updated!' : 'Course created!',
                    text: 'You will be redirected to My Courses.',
                    timer: 1800,
                    showConfirmButton: false,
                });
                setTimeout(() => {
                    router.visit(route('coursess'));
                }, 1200);
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
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage,
                });
            }
        });
    } catch (error) {
        console.error('An unexpected error occurred during form submission:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'An unexpected error occurred. Please try again.',
        });
    }
};

const removeVideo = (index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Revoke object URLs to prevent memory leaks
            const videoToRemove = videosData.value[index];
            if (videoToRemove.videoFilePreview && videoToRemove.videoFilePreview.startsWith('blob:')) {
                URL.revokeObjectURL(videoToRemove.videoFilePreview);
            }
            if (videoToRemove.thumbnailFilePreview && videoToRemove.thumbnailFilePreview.startsWith('blob:')) {
                URL.revokeObjectURL(videoToRemove.thumbnailFilePreview);
            }

            if (videoToRemove.id) {
                removedVideoIds.value = Array.from(new Set([...removedVideoIds.value, videoToRemove.id]));
            }

            const wasEditingTheRemovedVideo = currentEditingVideoIndex.value === index;
            const isEditingAfterTheRemovedVideo = currentEditingVideoIndex.value > index;

            videosData.value.splice(index, 1);

            if (videosData.value.length === 0) {
                currentEditingVideoIndex.value = -1;
                addNewVideoSlot();
                return;
            }

            if (wasEditingTheRemovedVideo) {
                const newIndex = Math.max(0, index - 1);
                currentEditingVideoIndex.value = newIndex;
                populateVideoDetailsForm(newIndex);
            } else if (isEditingAfterTheRemovedVideo) {
                currentEditingVideoIndex.value--;
            }
            
            // Trigger auto-save after removing video
            if (currentStep.value >= 3 && draftCourseId.value) {
                autoSaveDraft();
            }
        }
    });
};

watch(
    () => [props.course, props.courseVideos, props.courseQuiz, props.certificates, props.industries, props.courseTypes, props.topics],
    () => {
        if (props.course) {
            initializeEditingState();
            // Ensure video form is populated after initialization
            if (videosData.value.length > 0 && currentEditingVideoIndex.value >= 0) {
                setTimeout(() => {
                    populateVideoDetailsForm(currentEditingVideoIndex.value);
                }, 200);
            }
        }
    },
    { immediate: true }
);

const addQuiz = () => {
    quizForm.title = 'New Quiz';
    quizForm.description = '';
    quizForm.questions = [];
    currentStep.value = 3; // Move to the quiz step
};

const addQuestion = () => {
    quizForm.questions.push({
        question_text: '',
        answers: [
            { answer_text: '', is_correct: false },
            { answer_text: '', is_correct: false },
        ],
    });
};

const removeQuestion = (qIndex) => {
    quizForm.questions.splice(qIndex, 1);
};

const addAnswer = (qIndex) => {
    quizForm.questions[qIndex].answers.push({ answer_text: '', is_correct: false });
};

const removeAnswer = (qIndex, aIndex) => {
    quizForm.questions[qIndex].answers.splice(aIndex, 1);
};

const setCorrectAnswer = (qIndex, aIndex) => {
    quizForm.questions[qIndex].answers.forEach((answer, index) => {
        answer.is_correct = index === aIndex;
    });
};

// Per-video quiz helpers
const openVideoQuizModal = (edit = false) => {
    if (currentEditingVideoIndex.value < 0 || !videosData.value[currentEditingVideoIndex.value]) {
        Swal.fire({ icon: 'error', title: 'No video selected', text: 'Please select a video first.' });
        return;
    }
    isEditingVideoQuiz.value = !!edit;
    const existing = videosData.value[currentEditingVideoIndex.value].quiz;
    if (existing) {
        videoQuizForm.title = existing.title || '';
        videoQuizForm.description = existing.description || '';
        videoQuizForm.questions = (existing.questions || []).map(q => ({
            question_text: q.question_text || '',
            answers: (q.answers || []).map(a => ({ answer_text: a.answer_text || '', is_correct: !!a.is_correct }))
        }));
    } else {
        videoQuizForm.title = '';
        videoQuizForm.description = '';
        videoQuizForm.questions = [];
    }
    showVideoQuizModal.value = true;
};

const closeVideoQuizModal = () => {
    showVideoQuizModal.value = false;
};

const addVideoQuizQuestion = () => {
    videoQuizForm.questions.push({ question_text: '', answers: [ { answer_text: '', is_correct: false }, { answer_text: '', is_correct: false } ] });
};

const removeVideoQuizQuestion = (qIndex) => {
    videoQuizForm.questions.splice(qIndex, 1);
};

const addVideoQuizAnswer = (qIndex) => {
    videoQuizForm.questions[qIndex].answers.push({ answer_text: '', is_correct: false });
};

const removeVideoQuizAnswer = (qIndex, aIndex) => {
    videoQuizForm.questions[qIndex].answers.splice(aIndex, 1);
};

const setVideoQuizCorrectAnswer = (qIndex, aIndex) => {
    videoQuizForm.questions[qIndex].answers.forEach((a, idx) => { a.is_correct = idx === aIndex; });
};

const saveVideoQuiz = () => {
    // Basic validation
    if (!videoQuizForm.title || videoQuizForm.title.trim() === '') {
        Swal.fire({ icon: 'error', title: 'Quiz title is required' });
        return;
    }
    // Ensure every question has at least 2 answers and one correct
    for (const q of videoQuizForm.questions) {
        if (!q.question_text || q.question_text.trim() === '') {
            Swal.fire({ icon: 'error', title: 'Each question must have text' });
            return;
        }
        if (!q.answers || q.answers.length < 2) {
            Swal.fire({ icon: 'error', title: 'Each question needs at least 2 answers' });
            return;
        }
        if (!q.answers.some(a => a.is_correct)) {
            Swal.fire({ icon: 'error', title: 'Select a correct answer for each question' });
            return;
        }
    }

    const quizPayload = {
        title: videoQuizForm.title,
        description: videoQuizForm.description,
        questions: videoQuizForm.questions.map(q => ({
            question_text: q.question_text,
            answers: q.answers.map(a => ({ answer_text: a.answer_text, is_correct: !!a.is_correct }))
        }))
    };
    videosData.value[currentEditingVideoIndex.value].quiz = quizPayload;
    showVideoQuizModal.value = false;
    Swal.fire({ icon: 'success', title: 'Quiz saved for this video' });
    
    // Trigger auto-save after saving quiz
    if (currentStep.value >= 3) {
        autoSaveDraft();
    }
};

const removeVideoQuiz = () => {
    if (currentEditingVideoIndex.value < 0) return;
    delete videosData.value[currentEditingVideoIndex.value].quiz;
    Swal.fire({ icon: 'success', title: 'Quiz removed from this video' });
    
    // Trigger auto-save after removing quiz
    if (currentStep.value >= 3) {
        autoSaveDraft();
    }
};

onMounted(() => {
    if (videosData.value.length > 0 && currentEditingVideoIndex.value === -1) {
        selectVideoToEdit(0);
    }

    // Add click outside listener to close dropdowns
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.custom-dropdown')) {
            activeDropdown.value = null;
        }
    });
});

</script>

<style >
#quiz_title{
    color:black!important;
}
#quiz_description{
    color:black!important;
}
#question_text_{
    color:black!important;
}
#answer_text_{
    color:black!important;
}
.upload_left_icons{
    display: flex;
    gap: 10px;

}
.upload_header{
    display: flex;
    justify-content: space-between;
    padding: 14px;
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
        width: 340px;
    }
}
@media (max-width: 425px) {
    .upload_video_section{
        width: 390px;
    }
}
@media (max-width: 320px) {
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
    position: relative;
    width: 100%;
}

.custom-select select {
    width: 100%;
    padding: 20px;
    border: 1px solid grey;
    border-radius: 15px;
    color: #4D4D4D;
    background-color: white;
    cursor: pointer;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    padding-right: 40px; /* Space for the arrow */
    box-sizing: border-box;
}

/* Style for the select dropdown options */
.custom-select select option {
    width: calc(100% - 40px); /* Match select width minus padding */
    padding: 10px 20px;
    background-color: white;
    color: #4D4D4D;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    box-sizing: border-box;
    margin: 0 20px;
}

/* Custom dropdown arrow */
.custom-select select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 1em;
}

.custom-select select:focus {
    outline: none;
    border-color: #22c55e;
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
  /* background-image: url('/images/check_black.svg'); */
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

    background-color: #22c55e;
    padding-left: 0;
    padding-right: 0;

}
.footer_upload_video {
    background-color: #477CAA;
    color: white;
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

<style scoped>
.tabs_course_management {
    gap: 10px;
}

@media (max-width: 425px) {
    .tabs_course_management {
        flex-direction: column;
    }
}

/* Custom Dropdown Styles */
.custom-dropdown {
    position: relative;
    width: 100%;
    border: 1px solid grey;
    border-radius: 15px;
    background-color: white;
    cursor: pointer;
    user-select: none;
}

.selected-option {
    padding: 20px;
    color: #4D4D4D;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    max-width: calc(100% - 40px); /* Leave space for the arrow */
    text-align: left;
}

.selected-option span {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    display: block;
    width: 100%;
    text-align: left;
}

.dropdown-arrow {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    transition: transform 0.3s ease;
}

.custom-dropdown.active .dropdown-arrow {
    transform: translateY(-50%) rotate(180deg);
}

.dropdown-options {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background-color: white;
    border: 1px solid grey;
    border-radius: 0 0 15px 15px;
    margin-top: 5px;
    max-height: 0;
    overflow: hidden;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    opacity: 0;
    visibility: hidden;
}

.custom-dropdown.active .dropdown-options {
    max-height: 150px;
    opacity: 1;
    visibility: visible;
    overflow-y: auto;
}

.dropdown-option {
    padding: 15px 20px;
    color: #4D4D4D;
    cursor: pointer;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    transition: background-color 0.2s ease;
    text-align: left;
}

.dropdown-option:hover {
    background-color: #f5f5f5;
}

/* Add smooth scrollbar */
.dropdown-options::-webkit-scrollbar {
    width: 6px;
}

.dropdown-options::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.dropdown-options::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Add animation for options */
.dropdown-option {
    transform: translateY(-10px);
    opacity: 0;
    transition: all 0.2s ease;
}

.custom-dropdown.active .dropdown-option {
    transform: translateY(0);
    opacity: 1;
}
.dark .custom-dropdown{
        background-color: #2d2d2d;
}
.dark .selected-option{
    color: white;
}
.dark .dark_dropdown_arrow{
    filter: invert(1);
}
.dark .dropdown-option{
    color: white;
    background-color: #2d2d2d;
}
.dark .dropdown-option:hover{
    background-color: #2d2d2d;
}
.dark .add_course_dark_text{
    color: white !important;
}
.dark .add_course_dark_input_box{
    background-color: #595959 !important;
    color: white !important;
}
.dark .add_course_dark_left_videos{
    background-color: #2d2d2d !important;
    color: white !important;
}
.dark .add_course_dark_left_videos_item{
    background-color: #2d2d2d !important;
    color: white !important;
    
}
.dark .add_course_dark_left_videos_item_active{
    border-left: 2px solid white !important;
    background-color: #555555 !important;
}
.dark .add_course_dark_left_videos_item:hover{
    background-color: #555555 !important;
}
.dark .add_course_dark_icons{
    filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7495%) hue-rotate(288deg) brightness(100%) contrast(100%);
}

/* Stagger the animation for options */
.custom-dropdown.active .dropdown-option:nth-child(1) { transition-delay: 0.05s; }
.custom-dropdown.active .dropdown-option:nth-child(2) { transition-delay: 0.1s; }
.custom-dropdown.active .dropdown-option:nth-child(3) { transition-delay: 0.15s; }
.custom-dropdown.active .dropdown-option:nth-child(4) { transition-delay: 0.2s; }
.custom-dropdown.active .dropdown-option:nth-child(5) { transition-delay: 0.25s; }
.custom-dropdown.active .dropdown-option:nth-child(6) { transition-delay: 0.3s; }
.custom-dropdown.active .dropdown-option:nth-child(7) { transition-delay: 0.35s; }
.custom-dropdown.active .dropdown-option:nth-child(8) { transition-delay: 0.4s; }
.custom-dropdown.active .dropdown-option:nth-child(9) { transition-delay: 0.45s; }
.custom-dropdown.active .dropdown-option:nth-child(10) { transition-delay: 0.5s; }
</style>
