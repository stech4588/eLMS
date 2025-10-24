<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="$emit('close')">
        <div class="p-6 bg-white rounded-lg shadow-xl dark:bg-gray-800" style="width: 500px; max-width: 90%;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Takeaway Notes for: {{ video.title }}</h3>
                <button @click="$emit('close')" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white text-2xl">&times;</button>
            </div>
            <div class="mb-6 prose dark:prose-invert max-w-none max-h-60 overflow-y-auto dark:text-white">
                <p>{{ video.takeaway_notes }}</p>
            </div>
            <div class="flex justify-end space-x-4">
                <button @click="$emit('close')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Close
                </button>
                <button @click="downloadNotes" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                    Download Notes
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    show: Boolean,
    video: Object,
});

defineEmits(['close']);

const downloadNotes = () => {
  if (!props.video || !props.video.takeaway_notes) {
    return;
  }

  const notes = props.video.takeaway_notes;
  const title = props.video.title || 'video';
  const filename = `${title}-notes.txt`;

  const element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(notes));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
};
</script>
