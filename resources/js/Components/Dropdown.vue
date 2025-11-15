<script setup>
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

const triggerRef = ref(null);
const menuRef = ref(null);
const menuStyles = ref({});

const open = ref(false);

const updatePosition = () => {
    if (!triggerRef.value || !menuRef.value) return;

    const triggerRect = triggerRef.value.getBoundingClientRect();
    const menuRect = menuRef.value.getBoundingClientRect();

    let left = triggerRect.left;

    if (props.align === 'right') {
        left = triggerRect.right - menuRect.width;
    } else if (props.align === 'center') {
        left = triggerRect.left + triggerRect.width / 2 - menuRect.width / 2;
    }

    const viewportWidth = window.innerWidth;
    const maxLeft = viewportWidth - menuRect.width - 12;
    left = Math.min(Math.max(12, left), maxLeft);

    // Use fixed positioning relative to the viewport (no scroll offset).
    // Ensure the dropdown appears above fixed headers by using a high z-index.
    menuStyles.value = {
        position: 'fixed',
        top: `${triggerRect.bottom}px`,
        left: `${left}px`,
        minWidth: `${triggerRect.width}px`,
        zIndex: 2000,
    };
};

const bindListeners = () => {
    window.addEventListener('resize', updatePosition);
    window.addEventListener('scroll', updatePosition, true);
};

const unbindListeners = () => {
    window.removeEventListener('resize', updatePosition);
    window.removeEventListener('scroll', updatePosition, true);
};

watch(() => open.value, async (isOpen) => {
    if (isOpen) {
        await nextTick();
        await nextTick();
        updatePosition();
        bindListeners();
    } else {
        unbindListeners();
    }
});

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    unbindListeners();
});

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (props.align === 'right') {
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    } else {
        return 'origin-top';
    }
});
</script>

<template>
    <div class="relative">
        <div ref="triggerRef" @click="open = !open">
            <slot name="trigger" />
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div
            v-show="open"
            class="fixed inset-0"
            :style="{ zIndex: 1999 }"
            @click="open = false"
        ></div>

        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-show="open"
                    ref="menuRef"
                    class="rounded-md shadow-lg"
                    :class="[widthClass, alignmentClasses]"
                    :style="menuStyles"
                    @click="open = false"
                >
                    <div
                        class="rounded-md ring-1 dark:bg-dark-bg-secondary ring-black ring-opacity-5"
                        :class="contentClasses"
                    >
                        <slot name="content" />
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
