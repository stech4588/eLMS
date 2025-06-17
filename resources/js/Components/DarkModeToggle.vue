<template>
  <button
    @click="toggleDarkMode"
    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-dark-text-secondary hover:bg-gray-100 dark:hover:bg-dark-bg-tertiary w-full"
  >
    <i class="fas" :class="isDark ? 'fa-sun text-yellow-500' : 'fa-moon text-gray-700'"></i>
    <span class="ml-2">{{ isDark ? 'Light Mode' : 'Dark Mode' }}</span>
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isDark = ref(false)

onMounted(() => {
  // Check for saved theme preference or system preference
  if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true
    document.documentElement.classList.add('dark')
  } else {
    isDark.value = false
    document.documentElement.classList.remove('dark')
  }
})

const toggleDarkMode = () => {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.theme = 'dark'
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.theme = 'light'
  }
}
</script>

<style scoped>
/* Add smooth transition for theme switching */
:root {
  @apply transition-colors duration-200;
}
</style> 