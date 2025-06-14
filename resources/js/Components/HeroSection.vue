<template>
    <section class="relative bg-light-bg-primary dark:bg-dark-bg-primary">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-light-bg-secondary dark:bg-dark-bg-secondary opacity-50"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-light-text-primary dark:text-dark-text-primary mb-6">
                    Welcome to eLMS
                </h1>
                <p class="text-xl md:text-2xl text-light-text-secondary dark:text-dark-text-secondary mb-8 max-w-3xl mx-auto">
                    Your gateway to quality education and professional development. Discover courses that will help you achieve your goals.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/courses" 
                        class="px-8 py-3 bg-light-button-bg dark:bg-dark-button-bg text-light-button-text dark:text-dark-button-text 
                        rounded-lg hover:bg-light-button-hover dark:hover:bg-dark-button-hover transition-colors duration-200">
                        Browse Courses
                    </a>
                    <a href="/about" 
                        class="px-8 py-3 border-2 border-light-border-primary dark:border-dark-border-primary 
                        text-light-text-primary dark:text-dark-text-primary rounded-lg 
                        hover:bg-light-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors duration-200">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-light-card-bg dark:bg-dark-card-bg rounded-lg shadow-light dark:shadow-dark">
                    <div class="text-light-accent-primary dark:text-dark-accent-primary mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">
                        Expert-Led Courses
                    </h3>
                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                        Learn from industry professionals and experienced educators.
                    </p>
                </div>

                <div class="p-6 bg-light-card-bg dark:bg-dark-card-bg rounded-lg shadow-light dark:shadow-dark">
                    <div class="text-light-accent-primary dark:text-dark-accent-primary mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">
                        Secure Learning
                    </h3>
                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                        Your progress and achievements are safely stored and verified.
                    </p>
                </div>

                <div class="p-6 bg-light-card-bg dark:bg-dark-card-bg rounded-lg shadow-light dark:shadow-dark">
                    <div class="text-light-accent-primary dark:text-dark-accent-primary mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-light-text-primary dark:text-dark-text-primary mb-2">
                        Flexible Learning
                    </h3>
                    <p class="text-light-text-secondary dark:text-dark-text-secondary">
                        Study at your own pace, anytime and anywhere.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
// No additional setup needed for dark mode
</script>

<style scoped>
/* No additional styles needed as we're using Tailwind classes */
</style>