<template>
    <div class="elms-v3-post-card">
        <!-- Pinned Badge -->
        <div v-if="post.is_pinned" class="elms-v3-pinned absolute top-5 right-6 z-10 shadow-sm">
            <i class="fa-solid fa-thumbtack text-[10px] -rotate-45"></i>
            Pinned post
        </div>

        <!-- Header -->
        <div class="elms-v3-post-header">
            <div class="elms-v3-user-info">
                <div class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 shadow-sm border border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="elms-v3-meta">
                    <span class="elms-v3-username">{{ post.user.name }}</span>
                    <span class="elms-v3-time-cat">
                        {{ formatTimeAgo(post.created_at) }} in 
                        <span style="color: #009EE0; font-weight: 700;">
                            <i :class="getCategoryIcon(post.category)" class="mr-1 opacity-70"></i>
                            {{ getCategoryLabel(post.category) }}
                        </span>
                    </span>
                </div>
            </div>
            <!-- Options Menu -->
            <div v-if="post.user_id === $page.props.auth.user.id" class="relative">
                <div @click.stop="toggleMenu" class="elms-v3-post-options-btn" title="Options">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </div>
                
                <!-- Dropdown -->
                <div v-if="showMenu" v-click-outside="closeMenu" class="elms-v3-post-dropdown">
                    <div @click.stop="openEditModal" class="elms-v3-dropdown-item">
                        <i class="fa-solid fa-pen-to-square mr-2 text-blue-500"></i> Edit Post
                    </div>
                    <div @click.stop="confirmDelete" class="elms-v3-dropdown-item text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fa-solid fa-trash-can mr-2"></i> Delete Post
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Layout (Social Style) -->
        <div class="elms-v3-content-wrapper px-6">
            <h3 v-if="displayTitle" class="elms-v3-post-title text-xl font-black mb-3 text-gray-900 dark:text-white leading-tight">
                {{ displayTitle }}
            </h3>
            <p class="elms-v3-post-desc text-base text-gray-700 dark:text-gray-300 mb-4">{{ displayContent }}</p>

            <!-- Poll UI in Feed -->
            <div v-if="post.poll" class="mb-4 space-y-2 max-w-lg">
                <div v-for="option in post.poll.options" :key="option.id" @click.stop="vote(option.id)"
                    class="relative h-12 w-full bg-gray-50 dark:bg-gray-800/50 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <div class="absolute inset-y-0 left-0 bg-blue-500/10 transition-all duration-500"
                        :style="{ width: getOptionPercentage(option.votes_count) + '%' }"></div>
                    <div class="absolute inset-0 px-4 flex items-center justify-between pointer-events-none">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-3">
                            <i v-if="isOptionSelected(option.id)" class="fa-solid fa-circle-check text-blue-500"></i>
                            {{ option.option_text }}
                        </span>
                        <span class="text-xs font-black text-blue-500/60 transition-colors group-hover:text-blue-500">{{ getOptionPercentage(option.votes_count) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Media Attachments (Responsive Grid) -->
            <div v-if="post.attachments && post.attachments.length > 0" 
                 class="mb-4 grid gap-2 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                 :class="{
                     'grid-cols-1': post.attachments.length === 1,
                     'grid-cols-2': post.attachments.length >= 2
                 }">
                <template v-for="(att, idx) in post.attachments.slice(0, 4)" :key="att.id">
                    <div class="relative aspect-video group cursor-pointer overflow-hidden bg-gray-100 dark:bg-gray-900" 
                         @click.stop="openReplyModal"
                         :class="{ 'row-span-2 h-full': post.attachments.length === 3 && idx === 0 }">
                        <img :src="att.file_url" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div v-if="isVideo(att)" class="absolute inset-0 flex items-center justify-center bg-black/20">
                             <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30 text-white">
                                <i class="fa-solid fa-play text-xl ml-1"></i>
                             </div>
                        </div>
                        <!-- More overlay -->
                        <div v-if="idx === 3 && post.attachments.length > 4" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white text-2xl font-black">
                            +{{ post.attachments.length - 4 }}
                        </div>
                    </div>
                </template>
            </div>

            <!-- YouTube Previews in Feed -->
            <div v-if="post.links && post.links.some(l => isYoutubeUrl(l))" class="mb-4 space-y-3">
                <template v-for="(link, lIdx) in post.links" :key="'yt-feed-' + lIdx">
                    <div v-if="isYoutubeUrl(link)" class="elms-v3-media-thumb group shadow-lg ring-1 ring-white/10" @click.stop="redirectToLink(link)">
                        <div class="aspect-video relative">
                            <img :src="getYoutubeThumbnail(link)" alt="YouTube preview" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-all duration-300 group-hover:scale-[1.02]">
                            <!-- Overlay Link Info -->
                            <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/80 via-black/40 to-transparent translate-y-2 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="flex items-center gap-2 text-white/90 text-[10px] font-bold">
                                    <i class="fa-brands fa-youtube text-red-500 text-sm"></i>
                                    <span class="truncate">{{ link }}</span>
                                </div>
                            </div>
                            <div class="elms-v3-play-icon bg-red-600/90 group-hover:scale-110 transition-transform shadow-xl">
                                <i class="fa-solid fa-play text-white text-xs ml-0.5"></i>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Generic Links Display in Feed (Premium simple grid) -->
            <div v-if="post.links && post.links.some(l => !isYoutubeUrl(l))" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <template v-for="(link, lIdx) in post.links" :key="'gen-feed-' + lIdx">
                    <div v-if="!isYoutubeUrl(link)" @click.stop="redirectToLink(link)" class="group flex items-center gap-3 py-3 px-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800/40 hover:bg-white dark:hover:bg-gray-800 hover:shadow-md hover:border-blue-200 dark:hover:border-blue-900/50 transition-all duration-300 cursor-pointer overflow-hidden relative">
                         <!-- Subtle background glow -->
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-blue-500/5 blur-xl group-hover:bg-blue-500/10 transition-all"></div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300 rotate-12 group-hover:rotate-0">
                            <i class="fa-solid fa-link text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-extrabold text-gray-800 dark:text-gray-200 truncate tracking-tight group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors uppercase">{{ link }}</p>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 block opacity-60 group-hover:opacity-100">Click to Open</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="elms-v3-footer">
            <div class="elms-v3-action" @click="toggleLikePost(post)" :class="{ 'text-blue-500': post.is_liked }">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5" :fill="post.is_liked ? 'currentColor' : 'none'">
                    <path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Like</span>
                <span v-if="post.likes_count > 0" class="ml-1 font-bold">{{ post.likes_count }}</span>
            </div>
            <div class="elms-v3-action" @click="openReplyModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>{{ post.replies_count || 0 }} comments</span>
            </div>
        </div>

        <!-- Reply / Detailed View Modal -->
        <div v-if="replyModalOpen" class="elms-v3-modal-overlay" @click.self="closeReplyModal">
            <div class="elms-v3-reply-modal-content">
                <div class="elms-v3-reply-modal-body">
                    <!-- Original Post inside Modal -->
                    <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 shadow-md border-2 border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100 text-base flex items-center gap-2">
                                    {{ post.user.name }}
                                    <span v-if="post.user.is_admin" class="bg-blue-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">1</span>
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    {{ formatTimeAgo(post.created_at) }} • 
                                    <i :class="getCategoryIcon(post.category)" class="mr-1 opacity-70"></i>
                                    {{ getCategoryLabel(post.category) }}
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-gray-400">
                                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-gray-400">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 cursor-pointer hover:text-gray-600">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 cursor-pointer hover:text-gray-600">
                                <circle cx="12" cy="12" r="1" /><circle cx="19" cy="12" r="1" /><circle cx="5" cy="12" r="1" />
                                
                            </svg> -->
                            <div v-if="post.user_id === $page.props.auth.user.id" class="relative">
                                <div @click.stop="toggleMenu" class="elms-v3-post-options-btn ml-2" style="opacity: 1; padding: 4px;">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </div>
                                <div v-if="showMenu" v-click-outside="closeMenu" class="elms-v3-post-dropdown right-0">
                                    <div @click.stop="openEditModal" class="elms-v3-dropdown-item">
                                        <i class="fa-solid fa-pen-to-square mr-2 text-blue-500"></i> Edit Post
                                    </div>
                                    <div @click.stop="confirmDelete" class="elms-v3-dropdown-item text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i class="fa-solid fa-trash-can mr-2"></i> Delete Post
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2">
                        {{ displayTitle }}
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-purple-200">
                            <path d="M12 2C6.477 2 2 6.477 2 12c0 1.821.487 3.53 1.338 5L2.5 21.5l4.5-.838A9.955 9.955 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z" />
                        </svg>
                    </h2>

                    <div class="text-gray-800 dark:text-gray-200 text-base leading-relaxed mb-6 whitespace-pre-wrap">
                        {{ displayContent }}
                    </div>

                    <!-- Poll UI in Modal -->
                    <div v-if="post.poll" class="mb-8 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider">Poll</span>
                            <span class="text-xs text-gray-500">{{ post.poll.total_votes }} votes</span>
                        </div>
                        <div class="space-y-3">
                            <div 
                                v-for="option in post.poll.options" 
                                :key="option.id"
                                @click.stop="vote(option.id)"
                                class="relative h-12 w-full bg-white dark:bg-gray-900 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 cursor-pointer group hover:border-blue-500/50 transition-all"
                            >
                                <div 
                                    class="absolute inset-y-0 left-0 bg-blue-500 transition-all duration-500 opacity-10"
                                    :style="{ width: getOptionPercentage(option.votes_count) + '%' }"
                                ></div>
                                <div class="absolute inset-0 px-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                             :class="isOptionSelected(option.id) ? 'border-blue-500 bg-blue-500' : 'border-gray-300 dark:border-gray-600 group-hover:border-blue-300'">
                                            <i v-if="isOptionSelected(option.id)" class="fa-solid fa-check text-[10px] text-white"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ option.option_text }}</span>
                                    </div>
                                    <span class="text-xs font-black text-gray-500">{{ getOptionPercentage(option.votes_count) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- YouTube Link Preview in Modal -->
                    <div v-if="post.link_url && isYoutubeUrl(post.link_url)" class="mb-8 relative group cursor-pointer" @click="redirectToLink(post.link_url)">
                        <div class="rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-lg aspect-video bg-black relative">
                            <img :src="getYoutubeThumbnail(post.link_url)" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-2xl transform group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-play text-white text-2xl ml-1"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-2 text-xs text-blue-500 font-bold">
                            <i class="fa-brands fa-youtube text-red-600 text-lg"></i>
                            <span>Watch on YouTube</span>
                        </div>
                    </div>

                    <!-- Generic Link Preview in Modal -->
                    <div v-else-if="post.link_url" class="mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 flex items-center gap-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" @click="redirectToLink(post.link_url)">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate">{{ post.link_url }}</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">External Link</p>
                        </div>
                    </div>

                    <!-- Media Display in Modal (Stacked for high visibility) -->
                    <div v-if="post.attachments && post.attachments.length > 0" class="mb-8 space-y-4">
                        <div v-for="att in post.attachments" :key="att.id" class="rounded-2xl overflow-hidden bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center">
                            <template v-if="isVideo(att)">
                                <video :src="att.file_url" controls class="w-full max-h-[600px]"></video>
                            </template>
                            <template v-else-if="att.file_type === 'image' || att.file_path.match(/\.(jpeg|jpg|png|gif|svg)$/i)">
                                <img :src="att.file_url" class="max-w-full h-auto max-h-[800px] object-contain">
                            </template>
                            <div v-else class="p-4 w-full flex items-center gap-3 bg-white dark:bg-gray-800">
                                <i class="fa-solid fa-file-lines text-2xl text-blue-500"></i>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ att.file_name }}</p>
                                    <p class="text-xs text-gray-500">Document</p>
                                </div>
                                <a :href="att.file_url" download class="text-blue-500 hover:underline text-xs font-bold">Download</a>
                            </div>
                        </div>
                    </div>

                    <!-- Likes/Comments count display in modal body -->
                    <div class="flex items-center gap-6 mb-8 pt-6 border-t dark:border-gray-800">
                         <div class="flex items-center gap-2 text-gray-500 font-bold border rounded-lg px-4 py-1.5 cursor-pointer">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3" />
                            </svg>
                            Like
                            <span class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded text-xs ml-1">{{ post.likes_count || 1 }}</span>
                         </div>
                         <div class="flex items-center gap-2 text-gray-500 font-bold">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                            </svg>
                            {{ post.replies_count || 0 }} comments
                         </div>
                    </div>

                    <!-- Replies List -->
                    <div class="space-y-6">
                        <div v-for="reply in replies" :key="reply.id" class="flex gap-3">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-2xl">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ reply.user.name }}</span>
                                        <span class="text-[10px] text-gray-500">{{ formatTimeAgo(reply.created_at) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-snug">{{ reply.content }}</p>

                                    <!-- Reply Attachments -->
                                    <div v-if="reply.attachments && reply.attachments.length > 0" class="mt-2 space-y-2">
                                        <div v-for="att in reply.attachments" :key="att.id" class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
                                            <template v-if="att.file_type === 'image' || att.file_path.match(/\.(jpeg|jpg|png|gif|svg)$/i)">
                                                <img :src="'/' + att.file_path" class="max-w-full h-auto max-h-64 object-contain mx-auto block">
                                            </template>
                                            <template v-else-if="att.file_type === 'video' || att.file_path.match(/\.(mp4|webm|ogg|mov)$/i)">
                                                <video :src="'/' + att.file_path" controls class="max-w-full max-h-64 mx-auto block"></video>
                                            </template>
                                            <div v-else class="p-3 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                                <i class="fa-solid fa-file-lines text-blue-500"></i>
                                                <span class="truncate flex-1">{{ att.file_name }}</span>
                                                <a :href="'/' + att.file_path" download class="text-blue-500 hover:underline ml-auto">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reply Actions -->
                                <div class="mt-2 flex items-center gap-4 text-xs font-bold text-gray-500 ml-2">
                                    <div @click="toggleLikePost(reply)" class="flex items-center gap-1 cursor-pointer hover:text-blue-500 transition-colors" :class="{ 'text-blue-500': reply.is_liked }">
                                        <i class="fa-solid fa-thumbs-up" :class="reply.is_liked ? 'fa-solid' : 'fa-regular'"></i>
                                        <span>Like{{ reply.likes_count > 0 ? ' (' + reply.likes_count + ')' : '' }}</span>
                                    </div>
                                    <div @click="startReplyTo(reply)" class="flex items-center gap-1 cursor-pointer hover:text-blue-500 transition-colors">
                                        <i class="fa-solid fa-reply"></i>
                                        <span>Reply</span>
                                    </div>
                                </div>

                                <!-- Nested Replies UI -->
                                <div v-if="reply.replies_count > 0 || (reply.child_replies && reply.child_replies.length > 0)" class="mt-4 ml-4 pl-4 border-l-2 border-gray-100 dark:border-gray-700 space-y-4">
                                    <!-- Toggle/Load More Button -->
                                    <div v-if="reply.replies_count > 0 && (!reply.child_replies || reply.child_replies.length < reply.replies_count)" 
                                         @click="fetchNestedReplies(reply)" 
                                         class="text-[10px] font-bold text-blue-500 cursor-pointer hover:underline flex items-center gap-1"
                                    >
                                        <i class="fa-solid fa-chevron-down text-[8px]"></i>
                                        {{ reply.child_replies ? 'Show more replies' : 'Show ' + reply.replies_count + (reply.replies_count === 1 ? ' reply' : ' replies') }}
                                    </div>

                                    <!-- Rendered Nested Replies -->
                                    <div v-for="child in (reply.child_replies || [])" :key="child.id" class="flex gap-2">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                            <i class="fa-solid fa-user text-xs"></i>
                                        </div>
                                        <div class="flex-1 bg-white dark:bg-gray-900/50 p-2.5 rounded-xl border border-gray-50 dark:border-gray-800">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-[11px] text-gray-900 dark:text-gray-100">{{ child.user.name }}</span>
                                                <span class="text-[9px] text-gray-500">{{ formatTimeAgo(child.created_at) }}</span>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ child.content }}</p>
                                            <div class="mt-1.5 flex items-center gap-3 text-[10px] font-bold text-gray-500">
                                                <div @click="toggleLikePost(child)" class="cursor-pointer hover:text-blue-500" :class="{ 'text-blue-500': child.is_liked }">
                                                    Like{{ child.likes_count > 0 ? ' (' + child.likes_count + ')' : '' }}
                                                </div>
                                                <div @click="startReplyTo(child)" class="cursor-pointer hover:text-blue-500">Reply</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="currentPage < lastPage" @click="fetchReplies" class="text-sm text-blue-500 font-bold cursor-pointer hover:underline text-center py-2">
                             See more comments...
                        </div>
                    </div>
                </div>

                <!-- Modal Footer with Pill Input -->
                <div class="elms-v3-reply-modal-footer">
                    <div v-if="replyingTo" class="mb-2 px-4 py-1.5 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 text-[10px] text-blue-700 dark:text-blue-300 flex justify-between items-center rounded-r-lg animate-in slide-in-from-bottom-2 duration-200">
                        <span>Replying to <strong>{{ replyingTo.user.name }}</strong></span>
                        <i @click="cancelReplyTo" class="fa-solid fa-xmark cursor-pointer hover:text-blue-900 dark:hover:text-blue-100"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 shadow-sm">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        
                        <div class="elms-v3-comment-input-pill">
                            <!-- Attachment Previews -->
                            <div v-if="replyAttachments.length > 0" class="flex flex-wrap gap-2 p-2 w-full border-b border-gray-100 dark:border-gray-700">
                                <div v-for="(file, index) in replyAttachments" :key="index" class="relative w-12 h-12">
                                    <img v-if="file.preview" :src="file.preview" class="w-full h-full object-cover rounded-lg border border-gray-200">
                                    <div v-else class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg text-[10px] text-gray-500 overflow-hidden text-center">
                                        {{ file.name.split('.').pop() }}
                                    </div>
                                    <div @click="removeReplyAttachment(index)" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] cursor-pointer shadow-sm">
                                        <i class="fa-solid fa-xmark"></i>
                                    </div>
                                </div>
                            </div>

                            <input 
                                ref="commentInput"
                                v-model="newReplyContent" 
                                @keyup.enter="submitReply"
                                placeholder="Your comment" 
                            >
                            <div v-if="showReplyLinkInput" class="w-full px-4 py-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-800/20">
                                <div class="flex gap-2">
                                    <input 
                                        v-model="newReplyLink"
                                        :placeholder="replyLinkInputType === 'youtube' ? 'Paste YouTube URL...' : 'Paste Web URL...'"
                                        class="flex-1 text-xs text-blue-500 bg-transparent outline-none py-1"
                                        @keyup.enter="addReplyLink"
                                    >
                                    <button @click="addReplyLink" class="text-[10px] font-bold text-blue-500 uppercase">Add</button>
                                </div>
                            </div>
                            <div class="elms-v3-comment-tools shrink-0 px-2 lg:px-4">
                                <div @click="submitReply"
                                     class="p-2 rounded-full transition-all transform hover:scale-105 active:scale-95 mr-2"
                                     :class="newReplyContent.trim() || replyAttachments.length > 0 || addedReplyLinks.length > 0 ? 'bg-blue-500 text-white shadow-md cursor-pointer' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed'"
                                     title="Send Comment"
                                >
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M3.478 2.404L22.133 12 3.478 21.596l1.373-7.532C4.94 13.567 5.373 13 6 13h9c.552 0 1-.448 1-1s-.448-1-1-1H6c-.627 0-1.06-.567-1.149-1.064l-1.373-7.532z" /></svg>
                                </div>
                                <div class="elms-v3-comment-tool-icon" @click="triggerReplyFileInput" title="Add Attachments">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" /></svg>
                                </div>

                                <div class="elms-v3-comment-tool-icon relative" @click="toggleEmojiPicker" title="Add Emoji">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><path d="M8 14s1.5 2 4 2 4-2 4-2" /><line x1="9" y1="9" x2="9.01" y2="9" /><line x1="15" y1="9" x2="15.01" y2="9" /></svg>
                                    
                                    <!-- Emoji Picker Popup -->
                                    <div v-if="showEmojiPicker" class="absolute bottom-full right-0 mb-4 z-50">
                                        <EmojiPicker :native="true" @select="onEmojiSelect" />
                                    </div>
                                </div>
                                 <!-- <div class="elms-v3-comment-tool-icon font-black text-sm relative" @click="toggleGifPicker" title="Add GIF"> -->
                                    <!-- GIF -->
                                    <!-- GIF Picker Popup -->
                                    <!-- <div v-if="showGifPicker" class="absolute bottom-full right-0 mb-4 z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl p-4 w-72">
                                        <div class="flex gap-2 mb-3">
                                            <input v-model="gifSearch" @keyup.enter="searchGifs" placeholder="Search Giphy..." class="flex-1 text-xs border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1 bg-transparent">
                                            <button @click="searchGifs" class="bg-blue-500 text-white text-[10px] px-3 py-1 rounded-lg">Search</button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 h-48 overflow-y-auto custom-scrollbar">
                                            <div v-if="loadingGifs" class="col-span-2 text-center text-[10px] text-gray-500">Loading...</div>
                                            <img v-for="gif in gifs" :key="gif.id" :src="gif.images.fixed_height_small.url" @click="selectGif(gif)" class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80">
                                        </div>
                                    </div> -->
                                <div class="elms-v3-comment-tool-icon" @click="toggleReplyLinkInput('generic')" :class="{ 'text-blue-500': showReplyLinkInput && replyLinkInputType === 'generic' }" title="Add Generic Link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.172 13.828a4 4 0 015.656 0l4-4a4 4 0 10-5.656-5.656l-1.102 1.101" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="elms-v3-comment-tool-icon" @click="toggleReplyLinkInput('youtube')" :class="{ 'text-red-500': showReplyLinkInput && replyLinkInputType === 'youtube' }" title="Add YouTube Link">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                        <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z" />
                                        <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor"/>
                                    </svg>
                                </div>
                                <input type="file" ref="replyFileInput" class="hidden" @change="handleReplyFileChange" accept="image/*,video/*" multiple>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mini Previews for added links in reply -->
                <div v-if="addedReplyLinks.length > 0" class="px-6 pb-2 space-y-1">
                    <div v-for="(link, lIdx) in addedReplyLinks" :key="'reply-link-' + lIdx" class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg border dark:border-gray-700/50">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <i v-if="isYoutubeUrl(link)" class="fa-brands fa-youtube text-red-600 text-sm"></i>
                            <i v-else class="fa-solid fa-link text-blue-500 text-[10px]"></i>
                            <span class="text-[10px] text-gray-500 truncate max-w-[200px]">{{ link }}</span>
                        </div>
                        <button @click="removeReplyLink(lIdx)" class="text-gray-400 hover:text-red-500">
                             <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div v-if="showEditModal" class="elms-v3-modal-overlay" @click.self="closeEditModal">
        <div class="elms-v3-modal-content edit-modal">
            <div class="elms-v3-create-card overflow-hidden flex flex-col" style="margin-bottom: 0;">
                <div class="elms-v3-create-header flex-shrink-0 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-pen-to-square text-blue-500"></i>
                        <h3 class="text-sm font-black uppercase tracking-tight text-gray-900 dark:text-gray-100">Edit Your Post</h3>
                    </div>
                    <button @click="closeEditModal" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400 dark:text-gray-500 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <div class="overflow-y-auto custom-scrollbar flex-1 pr-2">
                <input 
                    v-model="editForm.title"
                    placeholder="Title"
                    class="elms-v3-input-title"
                    @focus="lastEditFocusedField = 'title'"
                >
                <textarea 
                    v-model="editForm.content"
                    placeholder="Write something..."
                    class="elms-v3-input-content"
                    rows="4"
                    @focus="lastEditFocusedField = 'content'"
                ></textarea>

                <!-- Edit Link Section (Modified for Multiple Links) -->
                <div v-if="showEditLinkInput" class="mt-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50/50 dark:bg-gray-800/30">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                             {{ editLinkInputType === 'youtube' ? 'Add YouTube Video' : 'Add Web Link' }}
                        </span>
                        <button @click="showEditLinkInput = false; tempEditLink = ''" class="text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-wider">Close</button>
                    </div>
                    <div class="flex gap-2">
                        <input 
                            v-model="tempEditLink"
                            :placeholder="editLinkInputType === 'youtube' ? 'Paste YouTube URL...' : 'Paste Web URL...'"
                            class="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all"
                            @keyup.enter="addEditLink"
                        >
                        <button @click="addEditLink" class="bg-blue-500 text-white px-4 rounded-xl font-bold text-xs uppercase transition-all hover:bg-blue-600 active:scale-95">Add</button>
                    </div>
                    <p v-if="tempEditLink && !isValidUrl(tempEditLink)" class="mt-2 text-[10px] text-red-500 font-bold">Please enter a valid working URL.</p>

                    <!-- Previews of added links in edit -->
                    <div class="mt-4 space-y-3">
                        <template v-for="(link, lIdx) in editForm.links" :key="'edit-link-' + lIdx">
                            <!-- YouTube Preview -->
                            <div v-if="isYoutubeUrl(link)" class="relative group">
                                <div class="rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-md aspect-video bg-black relative">
                                    <img :src="getYoutubeThumbnail(link)" class="w-full h-full object-cover opacity-80">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fa-solid fa-play text-white text-lg ml-0.5"></i>
                                        </div>
                                    </div>
                                    <button @click="removeEditLink(lIdx)" class="absolute top-2 right-2 bg-black/50 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-500">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Generic Link UI in edit (Simplified) -->
                            <div v-else class="flex items-center justify-between p-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-xl shadow-sm">
                                <div class="flex items-center gap-3 overflow-hidden">
                                     <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center text-blue-500">
                                        <i class="fa-solid fa-link text-xs"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate max-w-[200px]">{{ link }}</span>
                                </div>
                                <button @click="removeEditLink(lIdx)" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Edit Poll Section -->
                <div v-if="showEditPoll" class="mt-4 p-4 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50/50 dark:bg-gray-800/30">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Poll</span>
                        <button @click="showEditPoll = false" class="text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-wider">Remove</button>
                    </div>
                    
                    <div class="space-y-3">
                        <div v-for="(option, index) in editPollOptions" :key="index" class="relative group">
                            <input 
                                v-model="editPollOptions[index]"
                                :placeholder="'Option ' + (index + 1)"
                                class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all pr-10"
                            >
                            <button 
                                v-if="editPollOptions.length > 2"
                                @click="removeEditPollOption(index)" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button 
                        v-if="editPollOptions.length < 5"
                        @click="addEditPollOption" 
                        class="mt-4 border border-gray-200 dark:border-gray-700 rounded-xl px-6 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 transition-all uppercase"
                    >
                        <i class="fa-solid fa-plus mr-2"></i> Add option
                    </button>
                </div>

                <!-- Existing Attachments -->
                <div v-if="existingAttachments && existingAttachments.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[150px] overflow-y-auto p-1">
                    <div v-for="att in existingAttachments" :key="att.id" class="relative group">
                        <img v-if="!isVideo(att)" :src="att.file_url" class="rounded-lg w-full h-24 object-cover border dark:border-gray-700">
                        <div v-else class="flex flex-col items-center justify-center h-24 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-2">
                             <i class="fa-solid fa-video text-xl text-gray-400"></i>
                             <span class="text-[10px] truncate w-full text-center mt-1 dark:text-gray-400">Video</span>
                        </div>
                        <button @click="removeExistingAttachment(att.id)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md">×</button>
                    </div>
                </div>

                <!-- New Attachment Previews -->
                <div v-if="editAttachments.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4 max-h-[150px] overflow-y-auto p-1">
                    <div v-for="(file, index) in editAttachments" :key="index" class="relative group">
                        <img v-if="file.type.startsWith('image/')" :src="file.preview" class="rounded-lg w-full h-24 object-cover border dark:border-gray-700">
                        <div v-else class="flex flex-col items-center justify-center h-24 bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[10px] truncate w-full text-center mt-1 dark:text-gray-400">{{ file.name }}</span>
                        </div>
                        <button @click="removeEditAttachment(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md">×</button>
                    </div>
                </div>
            </div> <!-- End of overflow-y-auto custom-scrollbar -->
            
            <div class="p-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex-shrink-0">
                <div class="elms-v3-toolbar items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="elms-v3-tool-btn" @click="triggerEditFileInput" title="Add Attachments">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" /></svg>
                        </div>
                        <div class="elms-v3-tool-btn relative" @click="toggleEditEmojiPicker" title="Add Emoji">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><circle cx="12" cy="12" r="10" /><path d="M8 14s1.5 2 4 2 4-2 4-2" /><line x1="9" y1="9" x2="9.01" y2="9" /><line x1="15" y1="9" x2="15.01" y2="9" /></svg>
                            <div v-if="showEditEmojiPicker" class="absolute bottom-full left-0 mb-4 z-50">
                                <EmojiPicker :native="true" @select="onEditEmojiSelect" />
                            </div>
                        </div>
                        <div class="elms-v3-tool-btn font-black text-sm relative" @click="toggleEditPoll" :class="{ 'text-blue-500 bg-blue-50 dark:bg-blue-900/20': showEditPoll }" title="Add Poll">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                <path d="M18 20V10M12 20V4M6 20v-6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="elms-v3-tool-btn relative" @click="toggleEditLinkInput('generic')" :class="{ 'text-blue-500 bg-blue-50 dark:bg-blue-900/20': showEditLinkInput && editLinkInputType === 'generic' }" title="Add Generic Link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.172 13.828a4 4 0 015.656 0l4-4a4 4 0 10-5.656-5.656l-1.102 1.101" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="elms-v3-tool-btn relative" @click="toggleEditLinkInput('youtube')" :class="{ 'text-red-500 bg-red-50 dark:bg-red-900/20': showEditLinkInput && editLinkInputType === 'youtube' }" title="Add YouTube Link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z" />
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="currentColor"/>
                            </svg>
                        </div>
                        <!-- <div class="elms-v3-tool-btn font-black text-sm relative opacity-50 cursor-not-allowed" title="GIF (Coming Soon)">
                            GIF
                        </div> -->
                        <input type="file" ref="editFileInput" @change="handleEditFileChange" class="hidden" accept="image/*,video/*,application/pdf,.doc,.docx,.zip,.txt" multiple>

                        <div class="elms-v3-category-select-wrapper ml-2">
                            <select v-model="editForm.category" class="elms-v3-category-select">
                                <option value="general">General discussion</option>
                                <option v-for="catId in ['new_member', 'wins', 'bonus', 'questions', 'announcements']" :key="catId" :value="catId">
                                    {{ getCategoryLabel(catId) }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span @click="closeEditModal" class="elms-v3-cancel-btn uppercase text-xs font-bold tracking-wider cursor-pointer hover:text-gray-900">CANCEL</span>
                        <button 
                            @click="updatePost"
                            class="elms-v3-post-btn active uppercase"
                            :disabled="editing"
                        >
                            {{ editing ? 'SAVING...' : 'POST' }}
                        </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script setup>
import { ref, defineProps, computed, onMounted, onUnmounted, defineEmits } from 'vue';
import axios from 'axios';
import { formatDistanceToNow } from 'date-fns';
import { showToast } from '@/toast.js';
import Swal from 'sweetalert2';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';
import { GiphyFetch } from '@giphy/js-fetch-api';

const gf = new GiphyFetch('dc6zaTOxFJmzC'); // Standard Giphy Public Beta Key

const emit = defineEmits(['deleted', 'restored']);

const props = defineProps({
    post: Object,
});

const showMenu = ref(false);
const showEditModal = ref(false);
const editing = ref(false);
const editForm = ref({
    title: '',
    content: '',
    category: '',
    links: []
});

const showEditPoll = ref(false);
const editPollOptions = ref(['', '']);

const toggleEditPoll = () => {
    showEditPoll.value = !showEditPoll.value;
};

const addEditPollOption = () => {
    if (editPollOptions.value.length < 5) {
        editPollOptions.value.push('');
    }
};

const removeEditPollOption = (index) => {
    if (editPollOptions.value.length > 2) {
        editPollOptions.value.splice(index, 1);
    }
};

const editAttachments = ref([]);
const editFileInput = ref(null);
const showEditEmojiPicker = ref(false);
const lastEditFocusedField = ref('content');
const existingAttachments = ref([]);

const removeExistingAttachment = (attId) => {
    existingAttachments.value = existingAttachments.value.filter(a => a.id !== attId);
};

const toggleEditEmojiPicker = () => {
    showEditEmojiPicker.value = !showEditEmojiPicker.value;
};

const onEditEmojiSelect = (emoji) => {
    if (lastEditFocusedField.value === 'title') {
        editForm.value.title += emoji.i;
    } else {
        editForm.value.content += emoji.i;
    }
    showEditEmojiPicker.value = false;
};

const triggerEditFileInput = () => {
    if (editFileInput.value) editFileInput.value.click();
};

const handleEditFileChange = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        file.preview = URL.createObjectURL(file);
        editAttachments.value.push(file);
    });
};

const removeEditAttachment = (index) => {
    if (editAttachments.value[index].preview) {
        URL.revokeObjectURL(editAttachments.value[index].preview);
    }
    editAttachments.value.splice(index, 1);
};

const toggleMenu = () => showMenu.value = !showMenu.value;
const closeMenu = () => showMenu.value = false;

const openEditModal = () => {
    editForm.value = {
        title: props.post.title || '',
        content: props.post.content || '',
        category: props.post.category || 'general',
        links: props.post.links ? [...props.post.links] : []
    };
    showEditLinkInput.value = editForm.value.links.length > 0;
    tempEditLink.value = '';
    editLinkInputType.value = 'generic';
    if (props.post.poll) {
        showEditPoll.value = true;
        editPollOptions.value = props.post.poll.options.map(o => o.option_text);
    } else {
        showEditPoll.value = false;
        editPollOptions.value = ['', ''];
    }
    editAttachments.value = [];
    existingAttachments.value = props.post.attachments ? [...props.post.attachments] : [];
    showEditModal.value = true;
    closeMenu();
    closeReplyModal();
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const updatePost = async () => {
    if (!editForm.value.title.trim() || (!editForm.value.content.trim() && editAttachments.value.length === 0)) {
        showToast('Title and content (or attachment) are required', 'error');
        return;
    }

    editing.value = true;
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Spook Laravel into viewing this as PUT
    formData.append('title', editForm.value.title);
    formData.append('content', editForm.value.content);
    formData.append('category', editForm.value.category);
    
    // Existing attachments to keep
    if (existingAttachments.value.length > 0) {
        existingAttachments.value.forEach(att => {
            formData.append('existing_attachments[]', att.id);
        });
    } else {
        formData.append('existing_attachments', ''); // Clear all
    }
    
    editAttachments.value.forEach(file => {
        formData.append('attachments[]', file);
    });
    
    if (editForm.value.links && editForm.value.links.length > 0) {
        editForm.value.links.forEach((link, index) => {
            formData.append(`links[${index}]`, link);
        });
    } else {
        formData.append('links', ''); // Send empty to clear
    }

    if (showEditPoll.value) {
        const validOptions = editPollOptions.value.filter(o => o.trim());
        if (validOptions.length >= 2) {
            validOptions.forEach(opt => {
                formData.append('poll_options[]', opt);
            });
            formData.append('poll_question', editForm.value.title);
        }
    } else {
        formData.append('remove_poll', '1');
    }

    try {
        // Use POST with _method spoofing for file uploads on update
        const response = await axios.post(`/api/community-posts/${props.post.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        Object.assign(props.post, response.data);
        showToast('Post updated successfully');
        closeEditModal();
    } catch (error) {
        console.error('Error updating post:', error);
        showToast('Error updating post', 'error');
    } finally {
        editing.value = false;
    }
};

// Custom directive for clicking outside
const vClickOutside = {
    mounted(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
    },
};

const newReplyContent = ref('');
const replyAttachments = ref([]);
const replyFileInput = ref(null);
const showEmojiPicker = ref(false);
const showGifPicker = ref(false);
const gifSearch = ref('');
const gifs = ref([]);
const loadingGifs = ref(false);
const lastFocusedField = ref('content');

const addedReplyLinks = ref([]);
const replyLinkInputType = ref('generic');

const toggleReplyLinkInput = (type) => {
    if (showReplyLinkInput.value && replyLinkInputType.value === type) {
        showReplyLinkInput.value = false;
        newReplyLink.value = '';
    } else {
        showReplyLinkInput.value = true;
        replyLinkInputType.value = type;
    }
};

const addReplyLink = () => {
    if (!newReplyLink.value.trim()) return;
    if (!isValidUrl(newReplyLink.value)) {
        showToast('Please enter a valid working URL.', 'error');
        return;
    }
    if (addedReplyLinks.value.includes(newReplyLink.value.trim())) {
        showToast('Link already added.', 'warning');
        return;
    }
    addedReplyLinks.value.push(newReplyLink.value.trim());
    newReplyLink.value = '';
};

const removeReplyLink = (index) => {
    addedReplyLinks.value.splice(index, 1);
};

const isValidUrl = (string) => {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
};

const showEditLinkInput = ref(false);
const editLinkInputType = ref('generic');
const tempEditLink = ref('');

const toggleEditLinkInput = (type) => {
    if (showEditLinkInput.value && editLinkInputType.value === type) {
        showEditLinkInput.value = false;
        tempEditLink.value = '';
    } else {
        showEditLinkInput.value = true;
        editLinkInputType.value = type;
    }
};

const addEditLink = () => {
    if (!tempEditLink.value.trim()) return;
    if (!isValidUrl(tempEditLink.value)) {
        showToast('Please enter a valid working URL.', 'error');
        return;
    }
    if (!editForm.value.links) editForm.value.links = [];
    if (editForm.value.links.includes(tempEditLink.value.trim())) {
        showToast('Link already added.', 'warning');
        return;
    }
    editForm.value.links.push(tempEditLink.value.trim());
    tempEditLink.value = '';
};

const removeEditLink = (index) => {
    editForm.value.links.splice(index, 1);
};

const toggleEmojiPicker = () => {
    showEmojiPicker.value = !showEmojiPicker.value;
    showGifPicker.value = false;
};

/*
const toggleGifPicker = () => {
    showGifPicker.value = !showGifPicker.value;
    showEmojiPicker.value = false;
    if (showGifPicker.value && gifs.value.length === 0) {
        fetchTrendingGifs();
    }
};
*/

const onEmojiSelect = (emoji) => {
    newReplyContent.value += emoji.i;
    showEmojiPicker.value = false;
};

/*
const fetchTrendingGifs = async () => {
    loadingGifs.value = true;
    try {
        const { data } = await gf.trending({ limit: 10 });
        gifs.value = data;
    } catch (err) {
        console.error(err);
    } finally {
        loadingGifs.value = false;
    }
};

const searchGifs = async () => {
    if (!gifSearch.value.trim()) {
        fetchTrendingGifs();
        return;
    }
    loadingGifs.value = true;
    try {
        const { data } = await gf.search(gifSearch.value, { limit: 10 });
        gifs.value = data;
    } catch (err) {
        console.error(err);
    } finally {
        loadingGifs.value = false;
    }
};

const selectGif = (gif) => {
    newReplyContent.value += ` ${gif.images.fixed_height.url} `;
    showGifPicker.value = false;
};
*/

const triggerReplyFileInput = () => {
    if (replyFileInput.value) replyFileInput.value.click();
};

const handleReplyFileChange = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            file.preview = URL.createObjectURL(file);
        }
        replyAttachments.value.push(file);
    });
};

const removeReplyAttachment = (index) => {
    const file = replyAttachments.value[index];
    if (file.preview) URL.revokeObjectURL(file.preview);
    replyAttachments.value.splice(index, 1);
};

const replyingTo = ref(null);

const commentInput = ref(null);

const startReplyTo = (comment) => {
    replyingTo.value = comment;
    newReplyContent.value = `@${comment.user.name} `;
    if (commentInput.value) commentInput.value.focus();
};

const cancelReplyTo = () => {
    replyingTo.value = null;
    newReplyContent.value = '';
};

const toggleLikePost = async (target) => {
    try {
        const response = await axios.post(`/api/community-posts/${target.id}/toggle-like`);
        target.is_liked = response.data.liked;
        target.likes_count = response.data.likes_count;
    } catch (error) {
        console.error('Error toggling like:', error);
    }
};

const fetchNestedReplies = async (parentComment) => {
    try {
        const response = await axios.get(`/api/community-posts/${parentComment.id}`);
        const replies = (response.data.replies?.data || response.data.data || []).reverse();
        
        if (!parentComment.child_replies) parentComment.child_replies = [];
        
        // Merge and avoid duplicates
        const existingIds = parentComment.child_replies.map(r => r.id);
        const newReplies = replies.filter(r => !existingIds.includes(r.id));
        
        parentComment.child_replies = [...parentComment.child_replies, ...newReplies];
    } catch (error) {
        console.error('Error fetching nested replies:', error);
    }
};

const vote = async (optionId) => {
    if (!props.post.poll) return;

    try {
        const response = await axios.post(`/api/community-polls/${props.post.poll.id}/vote`, {
            option_id: optionId
        });
        props.post.poll = response.data.poll;
        showToast('Vote recorded!');
    } catch (error) {
        console.error('Error voting:', error);
        showToast(error.response?.data?.message || 'Error voting', 'error');
    }
};

const getOptionPercentage = (votesCount) => {
    if (!props.post.poll || !props.post.poll.total_votes) return 0;
    return Math.round((votesCount / props.post.poll.total_votes) * 100);
};

const isOptionSelected = (optionId) => {
    return props.post.poll?.user_voted_option_ids?.includes(optionId) || false;
};

const isYoutubeUrl = (url) => {
    if (!url) return false;
    return url.match(/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/);
};

const getYoutubeEmbedId = (url) => {
    if (!url) return '';
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : '';
};

const getYoutubeThumbnail = (url) => {
    const videoId = getYoutubeEmbedId(url);
    return videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : '';
};

const redirectToLink = (url) => {
    if (!url) return;
    window.open(url, '_blank', 'noopener,noreferrer');
};

const confirmDelete = () => {
    Swal.fire({
        title: 'Delete Post?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#1a2c38',
        confirmButtonText: 'Yes, delete it!',
        background: document.documentElement.classList.contains('dark') ? '#1A2C38' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
    }).then(async (result) => {
        if (result.isConfirmed) {
            handleDelete();
        }
    });
};

const handleDelete = async () => {
    try {
        await axios.delete(`/api/community-posts/${props.post.id}`);
        
        // Notify parent to remove from local list
        emit('deleted', props.post.id);

        // Show "Undo" Toast
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            text: 'Post moved to trash',
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: true,
            confirmButtonText: 'Undo',
            confirmButtonColor: '#009EE0',
            timer: 5000,
            timerProgressBar: true,
            background: isDark ? '#1A2C38' : '#fff',
            color: isDark ? '#fff' : '#1C355E',
            customClass: {
                popup: 'elms-v3-toast-undo',
                confirmButton: 'elms-v3-undo-btn'
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await axios.post(`/api/community-posts/${props.post.id}/restore`);
                    showToast('Post restored', 'success');
                    emit('restored', props.post); // Tell parent to put it back
                } catch (err) {
                    showToast('Failed to restore post', 'error');
                }
            }
        });
    } catch (error) {
        showToast('Error deleting post', 'error');
    }
};

const getCategoryLabel = (catId) => {
    const categories = {
        'general': 'General discussion',
        'new_member': 'New Member!',
        'wins': 'Wins / Results!',
        'bonus': 'Bonus Content',
        'questions': 'Ask Questions',
        'announcements': 'Announcements'
    };
    return categories[catId] || 'General discussion';
};

const getCategoryIcon = (catId) => {
    const icons = {
        'general': 'fa-solid fa-comments',
        'new_member': 'fa-solid fa-user-plus',
        'wins': 'fa-solid fa-trophy',
        'bonus': 'fa-solid fa-gift',
        'questions': 'fa-solid fa-circle-question',
        'announcements': 'fa-solid fa-bullhorn'
    };
    return icons[catId] || 'fa-solid fa-comments';
};

const replyModalOpen = ref(false);
const replies = ref([]);
const currentPage = ref(1);
const lastPage = ref(1);
const loadedReplies = ref(false);

const openReplyModal = () => {
    replyModalOpen.value = true;
    document.body.style.overflow = 'hidden';
    if (!loadedReplies.value) {
        fetchReplies();
    }
};

const closeReplyModal = () => {
    replyModalOpen.value = false;
    document.body.style.overflow = '';
};

const imageAttachments = computed(() => props.post.attachments?.filter(a => a.file_type === 'image') || []);
const fileAttachments = computed(() => props.post.attachments?.filter(a => a.file_type !== 'image') || []);

const displayedImages = computed(() => imageAttachments.value.slice(0, 4));
const hiddenImagesCount = computed(() => imageAttachments.value.length - 4);

const displayTitle = computed(() => {
    if (props.post.title) return props.post.title;
    // Extract first line as title if it exists and is reasonable
    const parts = props.post.content.split('\n');
    if (parts[0].length > 0 && parts[0].length < 100) return parts[0];
    return 'Community Insight';
});

const displayContent = computed(() => {
    const parts = props.post.content.split('\n');
    if (parts.length > 1 && parts[0].length < 100) {
        return parts.slice(1).join('\n').trim();
    }
    return props.post.content;
});

const isVideo = (attachment) => {
    if (!attachment) return false;
    return attachment.file_type === 'video' || 
           attachment.file_path.match(/\.(mp4|webm|ogg|mov)$/i);
};

const handleLike = () => {
    // Placeholder for like functionality
    console.log('Liked post:', props.post.id);
};

const gridClasses = computed(() => {
    return ''; // No longer used in V3
});

const imageContainerClasses = (index) => {
    return ''; // No longer used in V3
};

const formatTimeAgo = (date) => {
    return formatDistanceToNow(new Date(date), { addSuffix: true });
};

const fetchReplies = async () => {
    if (currentPage.value > lastPage.value && loadedReplies.value) return;

    try {
        const response = await axios.get(`/api/community-posts/${props.post.id}?page=${currentPage.value}`);
        // Handle the wrapped response format {post, replies}
        const repliesData = response.data.replies || response.data;
        replies.value = [...replies.value, ...repliesData.data];
        lastPage.value = repliesData.last_page;
        currentPage.value++;
        loadedReplies.value = true;
        
        // Update main post like status if provided
        if (response.data.post) {
            props.post.is_liked = response.data.post.is_liked;
            props.post.likes_count = response.data.post.likes_count;
        }
    } catch (error) {
        console.error('Error fetching replies:', error);
    }
};


const submitReply = async () => {
    if (!newReplyContent.value.trim() && replyAttachments.value.length === 0 && addedReplyLinks.value.length === 0) return;

    const formData = new FormData();
    formData.append('content', newReplyContent.value);
    
    // Threading logic: 
    // Always use the main comment as parent to avoid infinite deep nesting in UI
    let pid = props.post.id;
    let targetParent = null;

    if (replyingTo.value) {
        if (replyingTo.value.parent_id === props.post.id) {
            // Replying to a top-level comment
            pid = replyingTo.value.id;
            targetParent = replyingTo.value;
        } else {
            // Replying to a nested comment
            pid = replyingTo.value.parent_id;
            // Find the top-level parent to insert into
            targetParent = replies.value.find(r => r.id === pid);
        }
    }
    
    formData.append('parent_id', pid);
    
    replyAttachments.value.forEach(file => {
        formData.append('attachments[]', file);
    });

    if (addedReplyLinks.value.length > 0) {
        addedReplyLinks.value.forEach((link, index) => {
            formData.append(`links[${index}]`, link);
        });
    }

    try {
        const response = await axios.post('/api/community-posts', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        const newReply = response.data;
        if (newReply.is_liked === undefined) newReply.is_liked = false;
        if (newReply.likes_count === undefined) newReply.likes_count = 0;
        
        if (pid === props.post.id) {
            replies.value.push(newReply);
        } else if (targetParent) {
            if (!targetParent.child_replies) targetParent.child_replies = [];
            targetParent.child_replies.push(newReply);
            targetParent.replies_count = (targetParent.replies_count || 0) + 1;
        }

        newReplyContent.value = '';
        replyAttachments.value = [];
        replyingTo.value = null;
        showReplyLinkInput.value = false;
        newReplyLink.value = '';
        addedReplyLinks.value = [];
        props.post.replies_count++;
        showToast('Reply added successfully!');
    } catch (error) {
        console.error('Error submitting reply:', error);
        const message = error.response?.data?.message || 'Error submitting reply';
        showToast(message, 'error');
    }
};
</script>
