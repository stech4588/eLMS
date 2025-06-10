
<template>
<div class="main_result" id="result">
    <div class="inner_result">
        <div class="result_title">
            <h2>See Real Results</h2>
        </div>
    </div>
    <div class="inner_result">
        <div class="result_content">
            <p>
                We believe in the value of our online educational platform. If you genuinely&nbsp;<strong>put in 100% effort</strong>  and don’t feel you’ve gained value, feel free to cancel at any time.
            </p>
            <p>
                That’s why we’re priced at a&nbsp;<strong>fraction of the cost</strong>  of traditional educational institutions. The difference? You don’t have to wait four years to get a degree and look for a job…
            </p>
            <p>
                Our focus is to help you&nbsp;<strong>earn while you learn.</strong>  We teach the realities of business through daily action. If you already knew how to get started, you wouldn’t be on this page.
            </p>
        </div>
    </div>
    <div class="inner_result main_img_container" style="gap: 40px;" >
        <div class="result_img_container">
            <div class="result_head_img">
                <div class="result_img">
                    <img src="/images/the-real-world-app.webp" alt="result_img">
                </div>
                <div class="result_img_text">
                    <p>
                        We’ve built an &nbsp;<strong>ever-accessible portal,</strong>  available anytime, anywhere. Use our custom application&nbsp;<strong> on any computer, smartphone, or tablet.</strong>
                    </p>

                </div>

            </div>
            <div>
                
            </div>
            <div>
                
            </div>
        </div>
        <div class="result_img_container">
            <div class="result_head_img">
                <div class="result_img">
                    <img src="/images/200k-students.png" alt="result_img">
                </div>
                <div class="result_img_text">
                    <p>
                        We’ve built an &nbsp;<strong>ever-accessible portal,</strong>  available anytime, anywhere. Use our custom application&nbsp;<strong> on any computer, smartphone, or tablet.</strong>
                    </p>

                </div>

            </div>
            <div>
                
            </div>
            <div>
                
            </div>
        </div>
        <div class="result_img_container">
            <div class="result_head_img">
                <div class="result_img">
                    <img src="/images/luxury-lifestyle.jpg" alt="result_img">
                </div>
                <div class="result_img_text">
                    <p>
                        We’ve built an &nbsp;<strong>ever-accessible portal,</strong>  available anytime, anywhere. Use our custom application&nbsp;<strong> on any computer, smartphone, or tablet.</strong>
                    </p>

                </div>

            </div>
            <div>
                
            </div>
            <div>
                
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default{
    name: 'WebResults'
}
</script>

<style>
.result_img img{
    width: 100%;
    height: 100%;
}
.result_img {
    width: 400px;
    height: 250px;
}
@media(max-width:420px){
    .result_img {
        width: 100%;
        height: 100%;
    }
}
.main_result{
    display: flex;
    flex-direction: column;
    padding-bottom: 50px;
    justify-content: center;
    background-position: center;
    align-items: center;
    background-image: url("/images/bg.png");
    /* margin-top: 50px; */
}
.inner_result{
    justify-content: center;
    align-items: center;
    display: flex;
    margin-left: 0%;
}
.result_head_img{
    justify-content: center;
    align-items: center;
    display: flex;
    width: 400px;
    /* display: table-caption; */
    flex-direction: column;
    /* margin-left: 50px; */
    /* flex: 1 1 auto; */
}
@media (max-width: 1400px) {
    .result_head_img {
        max-width: 300px;
    }
}
@media (max-width: 1150px) {
    .result_head_img {
        max-width: 280px;
    }
}
@media (max-width: 1030px) {
    .result_head_img {
        width: 600px ;
        max-width: 600px
    }
}
@media (max-width: 700px) {
    .result_head_img {
        width: 500px ;
        max-width: 500px
    }
}
@media (max-width: 580px) {
    .result_head_img {
        width: 400px ;
        max-width: 400px
    }
}
@media (max-width: 450px) {
    .result_head_img {
        width: 300px ;
        max-width: 300px
    }
}
/* @media (max-width: 1400px){
    .result_head_img{
        width: 180%;
        max-width: 300%;
    }
} */
.result_title{
    font-size: 38px ;
    font-weight: 100;
    color: #08080d;
    
}
@media (max-width:580px){
    .result_title{
    font-size: 23px ;
    font-weight: 50;
    color: #08080d;
    
}
}
@media(max-width:450px){
    .result_content p {
        font-size: 15px;
    }
}
@media(max-width:450px){
    .result_img_text p {
        font-size: 15px;
    }
}

.result_content{
width: 52%;
font-size: 23px;
font-weight: 300;
font-family: 'Neue Plak Extended';
text-align: center;
line-height: 40px;
}
@media (max-width: 1030px){
    .result_content{
        width: 80%;
    }
}
.result_img img{
    border-style: solid;
    border-width: 2px 2px 2px 2px;
    border-color: #ffffff;
    border-radius: 39px 39px 39px 39px;
    box-shadow: 0px 0px 20px 4px rgba(0, 0, 0, 0.36);
    animation: 500ms pmFadeIn;
    /* width: 100%;
    max-width: 100%; */
    
}

@media (max-width: 1400px) {
    .result_img img {
        max-width: 300px;
    }
}
@media (max-width: 1150px) {
    .result_img img {
        max-width: 280px;
    }
}
@media (max-width: 1030px) {
    .result_img img {
        max-width: 380px;
    }
}
@media (max-width: 580px) {
    .result_img img {
        max-width: 280px;
    }
}
@media (max-width: 450px) {
    .result_img img {
        max-width: 250px;
    }
}
/* @media (max-width: 1400px){
    .result_img img{
        width: 100%;
        max-width: 100%;
    }
} */

.result_img_text{
    line-height: 40px;
    font-size: 20px;
    
}
.result_img_container{
    /* display: flex; */
   
  flex-wrap: wrap;

  
}

@media (max-width: 1030px){
    .main_img_container{
    flex-direction: column;
}
   
  } 

</style>