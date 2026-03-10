<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue'

const user = usePage().props.auth?.user
const joinNowUrl = computed(() => {
    if (user) {
        if (usePage().props.auth.profile_incomplete) {
            return '/register/complete';
        }
        return '/dashboard';
    }
    return '/';
});
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-white-100 pt-6 sm:justify-center sm:pt-0 main_auth_layout_container">
        <!-- <div style="width:100%; display: flex; justify-content: center; align-items: center;"> -->
            <!-- <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link> -->
            <!-- <img src="/images/MBM_Uni.png" alt="logo" class="" style=" width: 100px; height: 100px;">
        </div> -->
         <div style="width:100%; display: flex; justify-content: center; align-items: center;">
    <a :href="joinNowUrl">
      <img src="/images/MBM_Uni.png" alt="logo" style="width: 150px;filter: grayscale(100%) brightness(0); height: 150px;">
    </a>
  </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 main_auth_layout_container"
        >
            <slot />
        </div>
    </div>
</template>
<style>
.main_auth_layout_container{
    /* background: linear-gradient(93deg, #102548 30%, #004c8d 65%, #009ada 100%) !important; */
    padding-top: 0px !important;
    padding-bottom: 0px !important;
    padding-left: 0px !important;
    padding-right: 0px !important;
    padding-top: 0px !important;
    margin-top: 0px !important;
}
</style>
