<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};

const isFocused = ref(false);
const isFocusedpass = ref(false);
const showPassword = ref(false);

function onFocus() {
  isFocused.value = true;
}
function onBlur() {
  isFocused.value = false;
}
function onFocuspass() {
  isFocusedpass.value = true;
}
function onBlurpass() {
  isFocusedpass.value = false;
}
function togglePassword() {
  showPassword.value = !showPassword.value;
}
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />
    <div class="login-page-theme">
      <div class="login-card">
        <div class="login-card-inner">
          <a href="/" class="login-logo-link">
            <img src="/images/MBM_Uni.png" alt="Logo" class="login-logo" />
          </a>
          <h1 class="login-heading">Sign in to your account</h1>

          <div v-if="$page.props.flash.error" class="login-error">
            {{ $page.props.flash.error }}
          </div>
          <div v-if="status" class="login-status">
            {{ status }}
          </div>

          <form @submit.prevent="submit" class="login-form">
            <div class="login-field">
              <InputLabel for="email" value="Email" class="login-label" />
              <TextInput
                id="email"
                type="email"
                v-model="form.email"
                required
                autocomplete="username"
                placeholder="Enter your email"
                class="login-input"
                @focus="onFocus"
                @blur="onBlur"
              />
              <InputError class="login-field-error" :message="form.errors.email" />
            </div>

            <div class="login-field">
              <InputLabel for="password" value="Password" class="login-label" />
              <div class="login-password-wrap">
                <TextInput
                  :type="showPassword ? 'text' : 'password'"
                  id="password"
                  v-model="form.password"
                  required
                  autocomplete="current-password"
                  placeholder="Enter your password"
                  class="login-input"
                  @focus="onFocuspass"
                  @blur="onBlurpass"
                />
                <button type="button" class="login-toggle-password" @click="togglePassword" aria-label="Toggle password visibility">
                  <img src="/images/view_icon.svg" alt="" class="login-view-icon" />
                </button>
              </div>
              <InputError class="login-field-error" :message="form.errors.password" />
            </div>

            <div class="login-remember-forgot">
              <label class="login-remember">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="login-remember-text">Remember Me</span>
              </label>
              <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="login-forgot-link"
              >
                Forgot Password?
              </Link>
            </div>

            <PrimaryButton
              type="submit"
              class="login-submit-btn"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Sign In
            </PrimaryButton>

            <div class="login-bottom-links">
              <Link :href="route('Instructor')" class="login-forgot-link">
                Sign up as Instructor
              </Link>
            </div>

            <div class="login-social-row">
              <a :href="route('apple.redirect')" class="login-social-btn" aria-label="Sign in with Apple">
                <img src="/images/apple_logo.svg" alt="Apple" class="login-social-icon login-social-icon-apple" />
              </a>
              <a :href="route('facebook.redirect')" class="login-social-btn" aria-label="Sign in with Facebook">
                <img src="/images/facebook_icon.svg" alt="Facebook" class="login-social-icon" />
              </a>
              <a :href="route('google.redirect')" class="login-social-btn" aria-label="Sign in with Google">
                <img src="/images/google_icon.svg" alt="Google" class="login-social-icon" />
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<style scoped>
.login-page-theme {
  position: fixed;
  inset: 0;
  background-color: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  padding: 1rem;
}

.login-card {
  width: 100%;
  max-width: 420px;
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
}

.login-card-inner {
  padding: 2rem 2rem 1.5rem;
}

.login-logo-link {
  display: block;
  text-align: center;
  margin-bottom: 1.5rem;
}

.login-logo {
  width: 80px;
  height: auto;
  display: inline-block;
}

.login-heading {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
  text-align: center;
  margin: 0 0 1.5rem;
}

.login-error {
  margin-bottom: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #b91c1c;
  background-color: #fef2f2;
  padding: 0.75rem 1rem;
  border-radius: 6px;
  text-align: center;
  border: 1px solid #fecaca;
}

.login-status {
  margin-bottom: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #15803d;
  text-align: center;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.login-field {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.login-label {
  font-size: 0.875rem;
  font-weight: 500;
  text-align: start;
  color: #111827;
}

.login-input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 1rem;
  color: #111827;
  background: #ffffff;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.login-input:focus {
  outline: none;
  border-color: #22c55e;
  box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
}

.login-password-wrap {
  position: relative;
}

.login-toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 4px;
  cursor: pointer;
}

.login-view-icon {
  width: 20px;
  height: 20px;
  display: block;
}

.login-field-error {
  font-size: 0.8125rem;
}

.login-remember-forgot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.login-remember {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
  color: #374151;
}

.login-remember-text {
  user-select: none;
}

.login-forgot-link {
  font-size: 0.875rem;
  color: #111827;
  text-decoration: none;
}

.login-forgot-link:hover {
  text-decoration: underline;
  color: #374151;
}

.login-submit-btn {
  justify-content:center;
  width: 100%;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  font-weight: 600;
  color: #ffffff;
  background-color: #22c55e;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.login-submit-btn:hover:not(:disabled) {
  background-color: #16a34a;
}

.login-submit-btn:disabled {
  cursor: not-allowed;
}

.login-bottom-links {
  text-align: center;
  margin-top: 0.25rem;
}

.login-social-row {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 1.25rem;
  padding-top: 1.25rem;
  border-top: 1px solid #e5e7eb;
}

.login-social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  padding: 0;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  cursor: pointer;
  transition: border-color 0.2s, box-shadow 0.2s;
  text-decoration: none;
  color: inherit;
}

.login-social-btn:hover {
  border-color: #9ca3af;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.login-social-icon {
  width: 24px;
  height: 24px;
}

.login-social-icon-apple {
  filter: invert(1);
}
</style>
