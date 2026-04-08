<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-white">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password"class="dark:text-white" />

                <div class="relative">
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        class="mt-1 block w-full pr-12 dark:bg-dark-bg-secondary dark:text-white"
                        autocomplete="current-password"
                    />
                    <button
                        type="button"
                        class="password-eye-btn"
                        @click="showCurrentPassword = !showCurrentPassword"
                        :title="showCurrentPassword ? 'Hide password' : 'Show password'"
                        aria-label="Toggle password visibility"
                    >
                        <svg v-if="!showCurrentPassword" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.6 10.6a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M9.9 5.1A10.6 10.6 0 0112 5c6.5 0 10 7 10 7a18.2 18.2 0 01-4.2 5.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M6.2 6.2C3.8 8.1 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2 dark:text-white"
                />
            </div>

            <div>
                <InputLabel for="password" value="New Password" class="dark:text-white"/>

                <div class="relative">
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showNewPassword ? 'text' : 'password'"
                        class="mt-1 block w-full pr-12 dark:bg-dark-bg-secondary dark:text-white"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        class="password-eye-btn"
                        @click="showNewPassword = !showNewPassword"
                        :title="showNewPassword ? 'Hide password' : 'Show password'"
                        aria-label="Toggle password visibility"
                    >
                        <svg v-if="!showNewPassword" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.6 10.6a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M9.9 5.1A10.6 10.6 0 0112 5c6.5 0 10 7 10 7a18.2 18.2 0 01-4.2 5.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M6.2 6.2C3.8 8.1 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password" class="dark:text-white"
                />

                <div class="relative">
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        class="mt-1 block w-full pr-12 dark:bg-dark-bg-secondary dark:text-white"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        class="password-eye-btn"
                        @click="showConfirmPassword = !showConfirmPassword"
                        :title="showConfirmPassword ? 'Hide password' : 'Show password'"
                        aria-label="Toggle password visibility"
                    >
                        <svg v-if="!showConfirmPassword" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <svg v-else viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.6 10.6a2 2 0 102.8 2.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M9.9 5.1A10.6 10.6 0 0112 5c6.5 0 10 7 10 7a18.2 18.2 0 01-4.2 5.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M6.2 6.2C3.8 8.1 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2 dark:text-white"
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" style="background-color: #148ad9;">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-white"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
.password-eye-btn{
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    background: transparent;
    border: 1px solid transparent;
    transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}
.password-eye-btn svg{
    width: 18px;
    height: 18px;
}
.password-eye-btn:hover{
    background: rgba(15, 23, 42, 0.04);
    border-color: rgba(15, 23, 42, 0.10);
    color: #0f172a;
}
.dark .password-eye-btn{
    color: rgba(229, 242, 255, 0.75);
}
.dark .password-eye-btn:hover{
    background: rgba(229, 242, 255, 0.08);
    border-color: rgba(229, 242, 255, 0.16);
    color: #ffffff;
}
</style>
