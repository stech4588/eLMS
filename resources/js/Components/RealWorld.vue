<template>
    <div class="main-real-world">
        <div class="real-row">
            <div class="real-col-1">
                <div class="real-dsp-1">
                    <div class="real-dsp-col">
                        <h1>What is MBM</h1>
                        <h1>University?</h1>
                    </div>
                    <div class="real-dsp-col">
                         <div class="rw-img">
                           <a :href="user ? '/dashboard' : '/'">
                             <img src="/images/MBM_Uni.png" alt="MBM Logo">
                           </a>
                         </div>
                    </div>
                </div>
                <div class="real-dsp">
                    <p>
                        <span class="silver">MBM University</span> is a <span class="gold">global community</span> by
                        <span class="silver">Andrew Tate</span> where like-minded individuals work toward
                        achieving their personal goals.
                    </p>
                </div>
                <div class="real-dsp">
                    <p>
                        Every member receives <span class="gold">advanced training and mentoring</span> from our team of
                        experienced industry
                        specialists
                    </p>
                </div>
                <div class="real-dsp">
                    <p>
                        Develop essential business skills with the help of our coaches through <span class="gold">our
                            comprehensive
                            education system.</span>
                    </p>
                </div>
                <Link href="/register">
                    <div class="real-dsp-btn">
                        <button style="cursor: pointer;">JOIN NOW</button>
                    </div>
                </Link>

            </div>
            <div class="real-col-2">
                <div class="real-image">
                    <img src="/images/placeholder.webp">
                </div>
            </div>
        </div>
    </div>
</template>
<!-- <script setup>

import { usePage } from '@inertiajs/vue3'

const user = usePage().props.auth?.user
export default {
    components: {
        Link,
    },
}
</script> -->
<script>
import { usePage, Link } from '@inertiajs/vue3'

export default {
  components: {
    Link,
  },
  computed: {
    user() {
      return usePage().props.auth?.user
    }
  }
}
</script>

<style>
.silver {
    font-weight: 900;
}

.gold {
    color: #FFB04A;
    font-weight: 900;
}

.real-dsp p {
    color: #fff;
    font-size: 16px;
}

.real-dsp-col h1 {
    color: #fff;
    margin: 0;
    margin-right: 21px;
    font-size: 42px;
    font-weight: 900;
}

@media(max-width:590px) {
    .real-dsp-col h1 {
        margin-right: 0px;
        font-size: 22px;
    }
}

.rw-img img {
    width: 170px;
}

.main-real-world {
    background-color: #08080d;
}

.real-col-1 {
    width: 50%;
    margin: 10px;
}

@media(max-width:950px) {
    .real-col-1 {
        width: 100%;
    }
}

@media(max-width:580px) {
    .real-col-1 {
        margin: 0px;
    }
}

.real-image {
    width: 100%;
    height: 100%;
}

.real-image img {
    width: 100%;
    height: 100%;
    border: 2px solid #fff;
    border-radius: 12px;
}

.real-col-2 {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40%;
}

@media(max-width:1045px) {
    .real-col-2 {
        width: 37%;
    }
}

@media(max-width:950px) {
    .real-col-2 {
        margin: 10px;
        width: 100%;
    }
}

@media(max-width:580px) {
    .real-col-2 {
        margin: 0px;
    }
}

.real-dsp-btn {
    margin-bottom: 10px;
    display: flex;
    justify-content: flex-start;
}

@media(max-width:950px) {
    .real-dsp-btn {
        justify-content: center;
    }
}

.real-dsp {
    margin-bottom: 10px;
    display: flex;
    justify-content: flex-start;
}

.real-dsp p {
    margin: 0;
    line-height: 2;
    text-align: left;
}

@media(max-width:590px) {
    .real-dsp p {
        font-size: 13px;
        text-align: center;
    }
}

.real-dsp-1 {
    display: flex;
    margin-bottom: 10px;
    flex-direction: row;
    align-items: center;
}

@media(max-width:590px) {
    .real-dsp-1 {
        flex-direction: column-reverse;
    }
}

.real-dsp-btn button {
    margin-top: 10px;
    font-weight: 800 !important;
    border-radius: 50px;
    background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
    color: black;
    border: none;
    padding: 10px 20px;
}

.real-row {
    max-width: 1200px;
    padding-top: 50px;
    padding-bottom: 50px;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    justify-content: center;
    flex-direction: row;
}

@media(max-width:950px) {
    .real-row {
        padding: 50px;
        flex-direction: column;
    }
}
</style>