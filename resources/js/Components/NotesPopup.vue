<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="$emit('close')">
        <div class="p-6 bg-white rounded-lg shadow-xl dark:bg-gray-800" style="width: 500px; max-width: 90%;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Takeaway Notes for: {{ video.title }}</h3>
                <button @click="$emit('close')" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white text-2xl">&times;</button>
            </div>
            <div class="mb-6 prose dark:prose-invert max-w-none max-h-60 overflow-y-auto dark:text-white">
                <div v-if="notesText" v-html="renderedHtml"></div>
                <p v-else class="text-gray-500">No notes available for this video.</p>
            </div>
            <div class="flex justify-end space-x-4">
                <button @click="$emit('close')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Close
                </button>
                <button @click="organizeNotes" :disabled="isOrganizing || !video?.id" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md shadow-sm hover:bg-purple-700 disabled:opacity-50">
                    {{ isOrganizing ? 'Organizing...' : 'Organize with AI' }}
                </button>
                <button @click="downloadNotes" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                    Download Notes
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, watch, computed } from 'vue';
const props = defineProps({
    show: Boolean,
    video: Object,
});

const emit = defineEmits(['close', 'updated']);

const notesText = ref(props.video?.takeaway_notes || '');
watch(() => props.video, (newVal) => {
    notesText.value = newVal?.takeaway_notes || '';
}, { immediate: true });

const isOrganizing = ref(false);

function escapeHtml(text) {
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function formatInline(text) {
  // Bold: **text**
  return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
}

function markdownToHtml(markdown) {
  if (!markdown) return '';
  const lines = markdown.split(/\r?\n/);
  let html = '';
  const listStack = []; // track nested lists depth (0 or 1 level supported)

  function closeLists(toDepth = 0) {
    while (listStack.length > toDepth) {
      html += '</ul>';
      listStack.pop();
    }
  }

  for (const rawLine of lines) {
    const line = rawLine.replace(/\s+$/,'');
    if (!line.trim()) {
      closeLists(0);
      continue;
    }

    let m;
    if ((m = line.match(/^###\s+(.*)$/))) {
      closeLists(0);
      const content = formatInline(escapeHtml(m[1]));
      html += `<h3>${content}</h3>`;
      continue;
    }
    if ((m = line.match(/^##\s+(.*)$/))) {
      closeLists(0);
      const content = formatInline(escapeHtml(m[1]));
      html += `<h2>${content}</h2>`;
      continue;
    }

    // Sub-bullet (2 leading spaces before *)
    if ((m = line.match(/^\s{2}\*\s+(.*)$/))) {
      if (listStack.length < 1) {
        html += '<ul>';
        listStack.push(1);
      }
      if (listStack.length < 2) {
        html += '<ul>';
        listStack.push(2);
      }
      const content = formatInline(escapeHtml(m[1]));
      html += `<li>${content}</li>`;
      continue;
    }

    // Top-level bullet
    if ((m = line.match(/^\*\s+(.*)$/))) {
      if (listStack.length < 1) {
        html += '<ul>';
        listStack.push(1);
      }
      // If we were in nested level, close to 1
      if (listStack.length > 1) {
        closeLists(1);
      }
      const content = formatInline(escapeHtml(m[1]));
      html += `<li>${content}</li>`;
      continue;
    }

    // Paragraph fallback
    closeLists(0);
    const content = formatInline(escapeHtml(line));
    html += `<p>${content}</p>`;
  }

  closeLists(0);
  return html;
}

const renderedHtml = computed(() => markdownToHtml(notesText.value || ''));

const organizeNotes = async () => {
  if (!props.video || !props.video.id) return;
  isOrganizing.value = true;
  try {
    const { data } = await axios.post(`/videos/${props.video.id}/organize-notes`, {
      notes: notesText.value || props.video.takeaway_notes || ''
    });
    if (data && data.takeaway_notes) {
      notesText.value = data.takeaway_notes;
      emit('updated', data.takeaway_notes);
    }
  } catch (e) {
    console.error(e);
    alert('Could not organize notes right now.');
  } finally {
    isOrganizing.value = false;
  }
};

const downloadNotes = () => {
  if (!notesText.value) {
    return;
  }

  const notes = notesText.value;
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
