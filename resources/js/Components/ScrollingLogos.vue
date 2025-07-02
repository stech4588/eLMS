<template>
  <div class="scrolling-logos-wrapper">
    <div class="scrolling-logos-track">
      <div v-for="(img, idx) in images" :key="'logo-'+idx" class="logo-item">
        <img :src="img.src" :alt="img.alt" />
      </div>
      <!-- Duplicate for seamless infinite scroll -->
      <div v-for="(img, idx) in images" :key="'logo-dup-'+idx" class="logo-item">
        <img :src="img.src" :alt="img.alt" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ScrollingLogos',
  data() {
    return {
      images: [
        { src: '/images/slide3.svg', alt: 'Logo 1' },
        { src: '/images/slide2.svg', alt: 'Logo 2' },
        { src: '/images/slide3.svg', alt: 'Logo 3' },
        { src: '/images/slide2.svg', alt: 'Logo 4' },
        { src: '/images/slide5.svg', alt: 'Logo 5' },
        { src: '/images/slide6.svg', alt: 'Logo 6' },
        { src: '/images/slide3.svg', alt: 'Logo 1' },
        { src: '/images/slide2.svg', alt: 'Logo 2' },
        { src: '/images/slide3.svg', alt: 'Logo 3' },
        { src: '/images/slide2.svg', alt: 'Logo 4' },
        { src: '/images/slide5.svg', alt: 'Logo 5' },
        { src: '/images/slide6.svg', alt: 'Logo 6' }
      ],
    };
  },
};
</script>

<style scoped>
.scrolling-logos-wrapper {
  width: 100%;
  overflow: hidden;
  background: #eaf4fa;
  padding: 32px 0;
}
.scrolling-logos-track {
  display: flex;
  width: max-content;
  animation: scroll-logos 30s linear infinite;
}
.logo-item {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 32px;
  min-width: 120px;
  min-height: 60px;
}
.logo-item img {
  max-height: 48px;
  max-width: 120px;
  width: auto;
  height: auto;
  object-fit: contain;
  filter: grayscale(0.2) contrast(1.1);
  transition: filter 0.2s;
}
.logo-item img:hover {
  filter: grayscale(0) contrast(1.2) drop-shadow(0 2px 8px #0001);
}
@keyframes scroll-logos {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
@media (max-width: 900px) {
  .logo-item {
    margin: 0 18px;
    min-width: 80px;
    min-height: 40px;
  }
  .logo-item img {
    max-height: 32px;
    max-width: 80px;
  }
}
@media (max-width: 600px) {
  .scrolling-logos-wrapper {
    padding: 18px 0;
  }
  .logo-item {
    margin: 0 10px;
    min-width: 60px;
    min-height: 30px;
  }
  .logo-item img {
    max-height: 24px;
    max-width: 60px;
  }
}
</style> 