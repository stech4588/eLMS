<template>
  <div
    class="fusion-layout-column fusion_builder_column fusion-builder-column-6 fusion-flex-column fusion-flex-align-self-center light"
    :style="columnStyle"
  >
    <div class="fusion-column-wrapper fusion-column-has-shadow fusion-flex-justify-content-center fusion-content-layout-column">
      <canvas
        id="sequenceCanvas"
        width="870"
        height="780"
        class="animate-display"
        ref="canvas"
      ></canvas>

      <div
        class="fusion-image-element"
        style="text-align: center; --awb-max-width: 95px;"
      >
        <span
          class="fusion-imageframe imageframe-none imageframe-5 hover-type-none loader-gif"
          v-show="isLoading"
        >
          <img
            width="150"
            height="150"
            title="loading"
            src="https://www.seertechsolutions.com/wp-content/uploads/2025/02/loading-1.gif"
            alt="loading"
            class="img-responsive wp-image-4233"
          />
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";

const canvas = ref(null);
const isLoading = ref(true);

const columnStyle = `
  --awb-bg-size: cover;
  --awb-width-large: 45%;
  --awb-margin-top-large: 0px;
  --awb-spacing-right-large: 4.2667%;
  --awb-margin-bottom-large: 20px;
  --awb-spacing-left-large: 4.2667%;
  --awb-width-medium: 100%;
  --awb-order-medium: 0;
  --awb-spacing-right-medium: 1.92%;
  --awb-spacing-left-medium: 1.92%;
  --awb-width-small: 100%;
  --awb-order-small: 0;
  --awb-spacing-right-small: 1.92%;
  --awb-spacing-left-small: 1.92%;
`;

onMounted(() => {
  const ctx = canvas.value.getContext("2d");

  // Sample canvas animation (replace with your own)
  let x = 0;
  function draw() {
    ctx.clearRect(0, 0, canvas.value.width, canvas.value.height);
    ctx.fillStyle = "orange";
    ctx.beginPath();
    ctx.arc(x, 100, 50, 0, 2 * Math.PI);
    ctx.fill();
    x += 1;
    if (x > canvas.value.width) x = 0;
    requestAnimationFrame(draw);
  }

  draw();

  // Hide loader after animation starts (simulate delay)
  setTimeout(() => {
    isLoading.value = false;
  }, 1500);
});
</script>

<style scoped>
.animate-display {
  background: #ffffff;
}

.fusion-imageframe img {
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}
</style>
