<template>
  <div>
    <template v-if="isTooLong">
        <component :is="isExpanded ? 'p' : 'span'" v-html="displayText"></component>
        <button @click="isExpanded = !isExpanded"
                :class="{ 'ml-1': !isExpanded, 'mt-1': isExpanded }"
                class="text-blue-600 dark:text-blue-400 hover:underline">
          {{ isExpanded ? 'See less' : 'See more' }}
        </button>
    </template>
    <p v-else v-html="textWithBreaks"></p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  text: {
    type: String,
    default: '',
  },
  limit: {
    type: Number,
    default: 50,
  },
});

const isExpanded = ref(false);

const words = computed(() => {
    return props.text ? props.text.split(' ') : [];
});

const isTooLong = computed(() => {
  return words.value.length > props.limit;
});

const textWithBreaks = computed(() => {
    return props.text ? props.text.replace(/\n/g, '<br>') : '';
});

const displayText = computed(() => {
  if (isExpanded.value) {
    return textWithBreaks.value;
  }
  return words.value.slice(0, props.limit).join(' ') + '...';
});
</script>
