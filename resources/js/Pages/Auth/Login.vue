<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user

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

const redirectToGoogle = () => {
  window.location.href = route('google.redirect');
};

const redirectToFacebook = () => {
  window.location.href = route('facebook.redirect');
};

const redirectToApple = () => {
  window.location.href = route('apple.redirect');
};

const joinNowUrl = computed(() => {
  if (user) {
    if (usePage().props.auth.profile_incomplete) {
      return '/register/complete';
    }
    return '/dashboard';
  }
  return '/joinnow';
});
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

      <div v-if="$page.props.flash.error" class="error-message">
        {{ $page.props.flash.error }}
      </div>

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
          <span class="forgot-password-text">Forgot Password? </span>
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="forgot-password-link"
          >
             click here to reset your password!
          </Link>
        </div>

        <div class="login-button-wrapper">
          <div class="login-button-wrapper-left">
            <PrimaryButton
            class="login-btn"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Log in
          </PrimaryButton>
          </div>
          <div class="join-now-button-wrapper">
            <Link :href="joinNowUrl" class="signup-link signup-btn-wrapper">
              Join Now
            </Link>
         

          
            <Link :href="route('Instructor')" class="signup-link-instructor signup-btn-wrapper">
              Sign Up with Instructor
            </Link>
          </div>
            
         

          
           
          
        </div>

        <div class="or-divider">
          <!-- <div class="line"></div> -->
          <span>or sign in with</span>
          <!-- <div class="line"></div> -->
        </div>

        <div class="social-login-container">
          <button class="social-login-btn">
            <img src="/images/apple_logo.svg" alt="Apple" class="social-login-apple-icon" />
          </button>
          <button class="social-login-btn social-login-btn-facebook" @click="redirectToFacebook">
            <img src="/images/facebook_icon.svg" alt="Facebook" />
          </button>
          <button class="social-login-btn" @click="redirectToGoogle">
            <img src="/images/google_icon.svg" alt="Google" />
          </button>
        </div>
        <div class="forgot-password-wrapper" style="margin-top: 20px; justify-content: center; display: flex;">
          <span class="forgot-password-text">Need to find </span>
          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="forgot-password-link"
          >
              your password?
          </Link>
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
  padding-top:0px;
  /* background-color: #0D1016;
  background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
  background-size: 50px 50px; */
}

.login-header {
  text-align: center;
  /* margin-bottom: 2rem; */
}

.login-header h2 {
  font-weight: 600;
  font-size: 30px;
  color: rgb(255, 255, 255);
  margin-top: 0.5rem;
}

.login-header p {
  font-size: 12px;
  text-align: justify;
  color: #cccccc;
  margin-top: 1.5rem;
}

.login-divider {
  height: 1px;
  background-color: #eee;
  margin-bottom: 16px;
  width: 50%;;
}

.status-message {
  margin-bottom: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: green;
}

.error-message {
  margin-bottom: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: red;
  background-color: rgba(255, 0, 0, 0.1);
  padding: 1rem;
  border-radius: 6px;
  text-align: center;
  border: 1px solid red;
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
  color: #000000;
  font-size: 1rem;
  background-color: #ffffff !important;
  border-left: 5px solid #c9c9c9;

}
.floating-input:focus {
  outline: none;
  box-shadow: none;
}

.floating-label {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 16px;
  font-weight: 500;
  color: #000000;
  background: #ffffff !important;
  padding: 0 5px;
  transition: all 0.3s ease;
  pointer-events: none;
}

.floating-label.active {
  top: 5px;
  font-size: 14px;
  color: #000000;
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
  gap: 3px;
}

.forgot-password-link {
  font-size: 0.875rem;
  color: #c9c9c9;
  text-decoration: none;
  text-decoration: underline;
}
.forgot-password-link:hover{
  color: #fcfcfc;
  
}
.forgot-password-text{
  font-size: 12px;
  color: #c9c9c9;
  text-decoration: none;
  align-items: center;
    justify-content: center;
    display: flex;
}

.login-button-wrapper {
  display: flex;
  gap: 36px;
  margin-top: 10px;
  justify-content: space-between;
}
@media (max-width: 550px) {
  .login-button-wrapper {
    flex-direction: column;
    gap: 17px;
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
  color: #c9c9c9;
}

.signup-link-instructor {
  text-decoration: none;
  font-size: 12px;
  color: #c9c9c9;
}
.or-divider {
  display: flex;
  align-items: center;
  margin: 1rem 0 1.5rem;
  justify-content: center;
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

.social-login-container {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-top: 1.5rem;
}
.join-now-button-wrapper{
  display: flex;
  gap: 20px;
  align-items: end;
  justify-content: end;
}
@media (max-width: 550px) {
  .join-now-button-wrapper{
    gap: 20px;
    align-items: center;
    justify-content: center;
  }
}
@media (max-width: 550px) {
  .login-button-wrapper-left{
    align-items: center;
    justify-content: center !important;
    display: flex !important;
  }
}

.social-login-btn {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  width: 70px; /* Adjust width as needed */
  height: 50px; /* Adjust height as needed */
}

.social-login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.social-login-btn img {
  height: 24px; /* Adjust icon size as needed */
  width: 24px;  /* Adjust icon size as needed */
}
.social-login-btn-facebook img{
  height: 36px; /* Adjust icon size as needed */
  width: 36px;  /* Adjust icon size as needed */
}
.social-login-apple-icon{
  filter: invert(1);
}
</style>
