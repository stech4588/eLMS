<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    <div class="login-container">
      <div class="login-header">
        <h2>Empower Your Learning Journey</h2>
        <p>Welcome Back, Please Login to your account.</p>
      </div>

      <div class="login-divider"></div>

      <div v-if="status" class="status-message">
        {{ status }}
      </div>

      <form @submit.prevent="submit">
        <div class="floating-group">
          <TextInput
            id="email"
            type="email"
            v-model="form.email"
            required
            autocomplete="username"
            placeholder=" "
            class="floating-input"
            @focus="onFocus"
            @blur="onBlur"
          />
          <label
            for="email"
            class="floating-label"
            :class="{ active: form.email || isFocused }"
          >
            Email
          </label>
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="floating-group">
          <TextInput
            :type="showPassword ? 'text' : 'password'"
            id="password"
            v-model="form.password"
            required
            autocomplete="current-password"
            placeholder=" "
            class="floating-input"
            @focus="onFocuspass"
            @blur="onBlurpass"
          />
          <label
            for="password"
            class="floating-label"
            :class="{ active: form.password || isFocusedpass }"
          >
            Password
          </label>
          <img
            src="/images/view_icon.svg"
            alt="Toggle visibility"
            class="view-icon"
            @click="togglePassword"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="forgot-password-wrapper">
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="forgot-password-link"
          >
            Forgot Password? click here to reset your password!
          </Link>
        </div>

        <div class="login-button-wrapper">
          <PrimaryButton
            class="login-btn"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Log in
          </PrimaryButton>

          
            <Link :href="route('joinnow')" class="signup-link signup-btn-wrapper">
              Join Now
            </Link>
         

          
            <Link :href="route('Instructor')" class="signup-link-instructor signup-btn-wrapper">
              Sign Up with Instructor
            </Link>
          
        </div>

        <div class="or-divider">
          <div class="line"></div>
          <span>OR</span>
          <div class="line"></div>
        </div>

        <div class="google-sign">
          <img src="/images/google_icon.svg" alt="Google" />
          <button @click="redirectToGoogle">Sign in with Google</button>
        </div>
      </form>
    </div>
  </GuestLayout>
</template>

<style scoped>
.login-container {
  max-width: 550px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Arial', sans-serif;
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.login-header h2 {
  font-weight: 600;
  font-size: 30px;
  color: black;
  margin-top: 0.5rem;
}

.login-header p {
  font-size: 12px;
  text-align: justify;
  color: #777;
  margin-top: 1.5rem;
}

.login-divider {
  height: 1px;
  background-color: #eee;
  margin: 1.5rem 0;
}

.status-message {
  margin-bottom: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: green;
}

.floating-group {
  position: relative;
  margin-bottom: 1.5rem;
}

.floating-input {
  width: 100%;
  padding: 0.75rem;
  border-radius: 4px;
  border: 1px solid #ddd;
  color: black;
  font-size: 1rem;
  background-color: white;
  border-left: 5px solid #7E7E7E;
}

.floating-label {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  font-weight: 500;
  color: #666;
  background: white;
  padding: 0 5px;
  transition: all 0.3s ease;
  pointer-events: none;
}

.floating-label.active {
  top: 5px;
  font-size: 14px;
  color: #2b2899;
}

.view-icon {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}

.forgot-password-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 0.5rem;
}

.forgot-password-link {
  font-size: 0.875rem;
  color: #666;
  text-decoration: none;
}

.login-button-wrapper {
  display: flex;
  gap: 36px;
  margin-top: 60px;
}
@media (max-width: 550px) {
  .login-button-wrapper {
    flex-direction: column;
    gap: 10px;
  }
}

.login-btn {
  padding: 14px 25px;
  background-color: #1898e5;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  cursor: pointer;
  justify-content: center;
}

.signup-btn-wrapper {
  text-align: center;
  padding: 12px 14px;
  border: 1px solid #7E7E7E;
  justify-content: center;
  border-radius: 6px;
  display: flex;
  align-items: center;
}

.signup-link {
  text-decoration: none;
  font-size: 12px;
  color: #7E7E7E;
}

.signup-link-instructor {
  text-decoration: none;
  font-size: 12px;
  color: #7E7E7E;
}
.or-divider {
  display: flex;
  align-items: center;
  margin: 2.5rem 0 1.5rem;
}

.or-divider .line {
  flex-grow: 1;
  height: 1px;
  background-color: #999999;
}

.or-divider span {
  padding: 0 1rem;
  color: #999999;
}

.google-sign {
  display: flex;
}

.google-sign img {
  padding: 12px;
  box-shadow: -4px 4px 8px #7E7E7E;
  border: 1px solid #7E7E7E;
  margin-top: -1.35px;
}

.google-sign button {
  border: none;
  background-color: #1898e5;
  padding: 9px 40px;
  margin-left: -3px;
  font-size: 16px;
  border: 1px solid #7E7E7E;
  color: white;
}

@media (max-width: 375px) {
  .google-sign button {
    padding: 9px 20px;
    width: 100% !important;
  }
}

@media (max-width: 340px) {
  .google-sign button {
    font-size: 12px;
  }
  .google-sign img {
    width: 48px;
    margin-top: 1px;
  }
}
</style>
