<template>
    <div class="main-econinner" id="access">
        <div class="econinner">
        <div class="elementorelement">
            <div class="elementorwidgetcontainer">
                <h2>What Can I Access?</h2>
            </div>
        </div>
        <!-- <div class="elementorelement elementorwidget">
            <div class="box">
                <div id="logo">
                    <div class="icon">
                        <img src="../assets/images/medal.svg" alt="logo">
                    </div>
                </div>
                <div class="discription">
                    <div class="stepHeading">
                        <h3 style="margin: 0;">
                            <span>Step-by-Step<br>Learning</span>
                        </h3>

                    </div>
                    <div class="steps">
                        <ul>
                            <li><strong>Easy-to-follow program</strong>&nbsp;for daily progress</li>
                            <li><strong>New high-value skills</strong>&nbsp;constantly added</li>
                            <li>Master skills you need to&nbsp;<strong>grow your income</strong></li>
                            <li>Learn with our&nbsp;<strong>custom-made</strong>&nbsp;educational platform</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sidePic">
                <img src="../assets/images/laptop.png" alt="step1">
            </div>
        </div>
        <div class="elementorelement elementorwidget">
            <div class="box">
                <div id="logo">
                    <span class="icon">
                        <img src="../assets/images/grouplogo.svg" alt="logo">
                    </span>

                </div>
                <div class="discription">
                    <div class="stepHeading">
                        <h3 style="margin: 0;">
                            <span>Private Network<br>& Community</span>
                        </h3>

                    </div>
                    <div class="steps">
                        <ul>
                            <li>Network with&nbsp;<strong>200,000+ students</strong></li>
                            <li>Learn from&nbsp;<strong>real-world results</strong>&nbsp;and examples</li>
                            <li><strong>Make like-minded friends</strong>&nbsp;on your journey</li>
                            <li><strong>Celebrate your wins</strong>&nbsp;with people who understand</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sidePic">
                <img src="../assets/images/mobile.png" alt="step1">
            </div>
        </div> -->
        <div class="elementorelement elementorwidget">
            <div class="box">
                <div id="logo">
                    <span class="icon">
                        <img src="/images/personal.svg" alt="logo">
                    </span>
                </div>
                <div class="discription">
                    <div class="stepHeading">
                        <h3 style="margin: 0;">
                            <span>Industry Experts<br>In Your Pocket</span>
                        </h3>

                    </div>
                    <div class="steps">
                        <ul>
                            <li><strong>Personalized advice</strong>&nbsp;from successful entrepreneurs</li>
                            <li>Receive&nbsp;<strong>mentorship throughout</strong>&nbsp;your journey</li>
                            <li>Professors will&nbsp;<strong>help develop a personalized plan</strong>&nbsp;for you</li>
                            <li>Daily&nbsp;<strong>live sessions</strong>&nbsp;with experienced coaches</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sidePic">
                <img src="/images/group.png" alt="step1">
            </div>
        </div>
    </div>
    </div>
    
</template>

<script>
export default {
    name: 'WebFeatures'
}
</script>

<style>
.econinner {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    display: flex;
}

.elementorwidgetcontainer {

    color: #30353a;
    font-size: 30px;
    font-size: 700;
    font-family: 'Montserrat';
}

.elementorelement {
    align-items: center;
    justify-content: center;
    display: flex;



}

.elementorwidget {
    border: 2px solid  #87CEEB;
    border-radius: 20px;
    width: 65%;
    /* height: 350px; */
    justify-content: space-between;
    padding: 19px 25px;
    background-color: white;
    margin-bottom: 30px;
    flex-direction: row;

}

@media(max-width:650px) {
    .elementorwidget {
        height: 680px;
        width: 80%;
        flex-direction: column;
        padding-left: 4%;
        justify-content: none;
        align-items: flex-start;
    }
}


@media (max-width: 1040px) {

    .elementorwidget {
        width: 70%;
        flex-direction: column;
        padding-left: 4% !important;
        justify-content: none;
        align-items: flex-start;
    }





    .elementorwidgetcontainer {
        text-align: center;
        font-size: 20px;
    }

}

@media (max-width: 650px) {

    .elementorwidgetcontainer {
        text-align: center;
        font-size: 20px;
    }


}

@media (max-width: 440px) {

    .elementorwidget {
        height: 640px;
        width: 80%;
    }




    .elementorwidgetcontainer {
        text-align: center;
        font-size: 20px;
    }


}

.icon {
    background-image: linear-gradient(310deg, #38B6FF, #4CCAFF);
    display: inline-block;
    border-radius: 50px;
    height: 60px;
    width: 60px;
    /* text-align: center;
        align-items: center; */
    ;
    margin-bottom: 20px;

}

.icon img {
    width: 40px;
    height: 40px;
    padding-top: 10px;
    padding-left: 10px;

}

.stepHeading {
    font-size: 30px;
    color: #08080D;
    line-height: 40px;
}

@media(max-width:510px) {
    .stepHeading {
        font-size: 20px;
    }
}

.box {
    width: 45%;
    display: flex;
    line-height: 30px;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
}

@media(max-width:1040px) {
    .box {
        width: 100%;
    }
}

.sidePic {
    width: 45%;
}

@media(max-width:1040px) {
    .sidePic {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        width: 100%;
    }
}

@media(max-width:650px) {
    .sidePic {
        justify-content: center;
        align-items: center;
    }
}
@media(max-width:510px) {

    .sidePic {
        padding-bottom: 0px;
    }
}

.sidePic img {
    width: 100%;
}

@media(max-width:1040px) {
    .sidePic img {
        width: 450px;
        height: 350px;
    }
}

@media(max-width:650px) {
    .sidePic img {
        width: 280px;
        height: 220px;
    }
}
@media(max-width:510px) {
    .sidePic img {
        width: 100%
    }
}

.econinner {
    background-position: center center;
    background-size: cover;
    background-image: url("/images/bg.png");
}

.steps ul {
    list-style: none;
    padding: 0;
}

.steps ul li {
    color: #08080D;
    position: relative;
    padding-left: 20px;
    font-size: 16px;
}

@media(max-width:510px) {
    .steps ul li {
        font-size: 12px;
    }
}

.steps ul li::before {
    content: "◆";
    position: absolute;
    left: 0;
    color:  #87CEEB;
    font-size: 12px;
}
</style>