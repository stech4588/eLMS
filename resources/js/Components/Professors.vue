<template>
    <div class="main_prof">
        <div class="inner_main_prof">
            <div class="elementorelement_prof">
                <div class="discription_prof">
                    <div class="main_heading_prof">
                        <div class="title_prof">
                            <h2 class="title_prof_inner">
                                Meet The Professors
                            </h2>
                        </div>
                        <!-- <div class="icon_prof">
                            <img src="/images/golden-w.png">
                        </div> -->

                    </div>
                    <div class="text_prof">
                        <p>We call our instructors “Professors”, but their teachings come from&nbsp;<strong
                                class="gold">real-world
                                experience</strong> , not just theory.</p>
                        <p>All of our professors are hand-picked by Andrew Tate and reached&nbsp;<strong
                                class="gold">seven-figure
                                success</strong> using the methods they teach inside MBM University</p>
                        <p>Their mission is to &nbsp;<strong class="gold">guide, teach, and mentor</strong> you
                            throughout your business
                            journey every single day.</p>

                    </div>
                    <Link href="/register">
                        <div class="join_prof">
                            <span>JOIN NOW</span>
                        </div>
                    </Link>

                </div>
            </div>
            <div class="elementorelement_prof">
                <div class="img_prof">
                    <img src="/images/meet-the-professors.png">
                </div>
            </div>
            <div class="elementorelement_prof">
                <Link href="/register">
                    <div class="join_prof1">
                        <span>JOIN NOW</span>
                    </div>
                </Link>

            </div>
        </div>

    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
export default {
    name: 'WebProfessors',
    components: {
        Link,
    },
}
</script>


<style>
.gold {
    color: #FFB04A;
    font-weight: 900;
}

.title_prof_inner {
    font-size: 60px;
    margin-top: 30px;
}

@media (max-width: 767px) {
    .title_prof_inner {
        font-size: 30px;
        margin-top: 0px;
        margin-bottom: 0px;
    }
}

.join_prof1 {
    display: none;
}

@media (max-width:1024px) {
    .join_prof1 {
        background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
        color: #08080d;
        border-radius: 45px;
        /* padding: 11px 32px 11px 32px; */
        width: 170px;
        justify-content: center;
        height: 60px;
        align-items: center;
        display: flex;
        font-size: 20px;
        font-weight: bolder;
    }
}

@media (max-width:450px) {
    .join_prof1 {

        width: 130px;

        height: 40px;

    }
}

.inner_main_prof {
    flex-direction: row;
    align-items: center;
    justify-content: center;
    display: flex;
    width: 70%;
    padding-top: 40px;
    padding-bottom: 70px;
    margin-left: 90px;
    margin-right: 90px;

}

@media (max-width:1024px) {
    .inner_main_prof {
        flex-direction: column;
    }
}

@media (max-width:640px) {
    .inner_main_prof {
        width: 100%;
        margin-left: 20px;
        margin-right: 20px;
    }
}

.main_prof {
    background-color: #08080d;
    align-items: center;
    justify-content: center;
    display: flex;
}

.main_heading_prof {
    flex-direction: row;
    display: flex;

}

@media (max-width:767px) {
    .main_heading_prof {
        flex-direction: column-reverse;
        display: flex;
        text-align: center;
        align-items: center;

    }
}

.title_prof {
    font-size: 40px;
    font-weight: 700;
    line-height: 70px;
    letter-spacing: 1px;
    color: white;
    width: 275px;

}

@media (max-width:767px) {
    .title_prof {
        font-size: 25px;
        line-height: 40px;
    }
}


.join_prof {
    font-weight: 800 !important;
    border-radius: 50px;
    background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
    color: black;
    padding: 10px 7px;
    font-size: 1.1rem;
    border-radius: 20px;
    width: 30%;
    margin-top: 20px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    border: none;
    align-items: center;

}
.join_prof:hover {
    transform: scale(1.1) rotateX(10deg) !important;
}
@media (max-width:1024px) {
    .join_prof {
        display: none;
    }
}

.discription_prof {
    flex-direction: column;
    width: 90%;
}

@media (max-width:600px) {
    .discription_prof a {
       display: flex;
       justify-content: center;
    }
}
@media (max-width:565px) {
    .discription_prof {
        width: 100%;
    }
}

.text_prof {
    color: white;
    text-align: left;
    margin-top: 0px;
    font-size: 23px;
    line-height: 1.7;
}

@media (max-width:767px) {
    .text_prof {
        text-align: center;
    }
}

@media (max-width:767px) {
    .text_prof {
        font-size: 15px;
    }
}

.img_prof {
    width: 500px;
    height: 500px;
    margin-top: 70px;

}

@media (max-width: 1235px) {
    .img_prof {
        width: 400px;
        height: 400px;


    }
}

@media (max-width: 1120px) {
    .img_prof {
        width: 350px;
        height: 350px;
    }
}

@media (max-width: 1024px) {
    .img_prof {
        width: 100%;
    }
}

@media (max-width: 1024px) {
    .img_prof {
        width: 100%;
    }
}

/* 

@media (max-width: 920px) {
    .img_prof {
        width: 500px;
        height: 500px;
    }
}

@media (max-width: 767px) {
    .img_prof {
        width: 400px;
        height: 400px;
        margin-top: 0px;
        margin-right: 40px;
    }
}

@media (max-width:640px) {
    .img_prof {
        width: 350px;
        height: 350px;
    }
}

@media (max-width:450px) {
    .img_prof {
        width: 280px;
        height: 280px;
    }
}

@media (max-width:370px) {
    .img_prof {
        width: 240px;
        height: 240px;
    }
} */

.img_prof img {
    width: 100%;
    height: auto;
}

@media (max-width:1030px) {
    .img_prof img {
        width: 100%;
        height: auto;
    }
}

@media (max-width:1024px) {
    .img_prof img {
        height: 300px;
    }
}

@media (max-width:405px) {
    .img_prof img {
        height: auto;
    }
}

/* @media (max-width:767px) {
    .icon_prof img {
        height: 110px;
    }
} */
.icon_prof img {
    width: 264px;
}

@media(max-width:620px) {
    .icon_prof img {
        width: 100%;
    }
}

.icon_prof {
    width: 50%;
    margin-left: 12px;

}

@media (max-width:767px) {
    .icon_prof {
        margin-left: 0px;
    }
}

@media (max-width:767px) {
    .elementorelement_prof {
        justify-content: center;

        align-items: center;
        display: flex;

    }
}
</style>