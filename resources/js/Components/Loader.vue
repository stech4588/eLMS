<template>
    <div id="loader">
      <div id="shadow1"></div>
      <div id="shadow2"></div>
      <div id="shadow3"></div>
      <div id="box1"></div>
      <div id="box2"></div>
      <div id="box3"></div>
    </div>
  </template>
  <script>
  export default {
    name: 'Loader',
  }
  </script>
  
<style>
#loader {
    /* Uncomment this to make it run! */
    /*   animation: loader 5s linear infinite;*/
    margin-left: -75px;
    position: absolute;
    top: calc(50% - 20px);
    left: calc(50% - 20px);
}

@keyframes loader {
    0% {
        left: -100px
    }

    100% {
        left: 110%;
    }
}

#box1 {
    width: 50px;
    height: 50px;
    background: #4CCAFF;
    animation: animate .5s linear infinite;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 3px;
}

#box2 {
    width: 50px;
    height: 50px;
    background: #2b2899;
    animation: animate .5s linear infinite;
    position: absolute;
    top: 0;
    left: 73px;
    border-radius: 3px;
}

#box3 {
    width: 50px;
    height: 50px;
    background: #2b2899;
    animation: animate .5s linear infinite;
    position: absolute;
    top: 0;
    left: 142px;
    border-radius: 3px;
}

@keyframes animate {
    17% {
        border-bottom-right-radius: 3px;
    }

    25% {
        transform: translateY(9px) rotate(22.5deg);
    }

    50% {
        transform: translateY(18px) scale(1, .9) rotate(45deg);
        border-bottom-right-radius: 40px;
    }

    75% {
        transform: translateY(9px) rotate(67.5deg);
    }

    100% {
        transform: translateY(0) rotate(90deg);
    }
}

#shadow1 {
    width: 50px;
    height: 5px;
    background: #000;
    opacity: 0.1;
    position: absolute;
    top: 59px;
    left: 0;
    border-radius: 50%;
    animation: shadow .5s linear infinite;
}

#shadow2 {
    width: 50px;
    height: 5px;
    background: #000;
    opacity: 0.1;
    position: absolute;
    top: 59px;
    left: 73px;
    border-radius: 50%;
    animation: shadow .5s linear infinite;
}

#shadow3 {
    width: 50px;
    height: 5px;
    background: #000;
    opacity: 0.1;
    position: absolute;
    top: 59px;
    left: 142px;
    border-radius: 50%;
    animation: shadow .5s linear infinite;
}

@keyframes shadow {
    50% {
        transform: scale(1.2, 1);
    }
}
</style>