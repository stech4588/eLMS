<template>
  <section class="stat-section" ref="solutionSection" id="why-us">
    <!-- ===== ORIGINAL COMPONENT DESIGN (COMMENTED FOR BACKUP) =====
    <div class="mbm-lms-container">
      <h2 class="mbm-lms-title">Why ElevateU University Chooses Smart Technology For Smarter Learning.</h2>
      <div class="mbm-lms-grid">
        <div class="mbm-lms-stat" v-for="(stat, index) in countedStats" :key="index">
          <div class="mbm-lms-value">{{ stat.displayValue }}</div>
          <div class="mbm-lms-label" v-html="stat.label"></div>
        </div>
      </div>
    </div>
    -->

    <!-- NEW REDESIGNED UI -->
    <div class="stat-container">
      <h2 class="stat-main-title">Why ElevateU University Chooses Smart Technology For Smarter Learning.</h2>
      
      <div class="stat-grid-3x2">
        <div class="stat-item" v-for="(stat, index) in (countedStats.length ? countedStats : stats)" :key="index">
          <div class="stat-number">
             {{ countedStats.length ? stat.displayValue : stat.value }}
          </div>
          <p class="stat-label" v-html="stat.label"></p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      stats: [
        { value: '19', label: 'Industry best<br>practice awards' },
        { value: '97%', label: 'Customer<br>retention rate' },
        { value: '20+', label: 'Years of<br>experience' },
        { value: '190+', label: 'Countries use<br>our LMS' },
        { value: '200K$', label: 'Orders booked<br>per year' },
        { value: '42K', label: 'Average<br>customer size' }
      ],
      countedStats: [],
      hasAnimated: false
    };
  },
  mounted() {
    this.initObserver();
  },
  methods: {
    initObserver() {
      const observer = new IntersectionObserver(
        (entries) => {
          const entry = entries[0];
          if (entry.isIntersecting && !this.hasAnimated) {
            this.startCountUp();
            this.hasAnimated = true;
            observer.disconnect(); // Only run once
          }
        },
        { threshold: 0.4 } // 40% visible triggers the animation
      );
      observer.observe(this.$refs.solutionSection);
    },

    startCountUp() {
      const duration = 2000; // 2 seconds
      const frameRate = 60;
      const totalFrames = Math.round((duration / 1000) * frameRate);

      this.countedStats = this.stats.map(stat => {
        const numeric = parseFloat(stat.value.replace(/[^0-9.]/g, ''));
        const suffix = stat.value.replace(/[0-9.]/g, '');
        return {
          value: numeric,
          suffix: suffix,
          label: stat.label,
          displayValue: '0' + suffix
        };
      });

      let frame = 0;
      const animate = () => {
        frame++;
        this.countedStats = this.countedStats.map(stat => {
          const progress = Math.min(frame / totalFrames, 1);
          const current = Math.round(stat.value * progress);
          return {
            ...stat,
            displayValue: current.toLocaleString() + stat.suffix
          };
        });

        if (frame < totalFrames) {
          requestAnimationFrame(animate);
        }
      };

      requestAnimationFrame(animate);
    }
  }
};
</script>

<style scoped>
/* NEW STYLES */
.stat-section {
  background-color: #272d34;
  padding: 100px 20px;
  text-align: center;
  font-family: 'Inter', system-ui, sans-serif;
}

.stat-container {
  max-width: 1100px;
  margin: 0 auto;
}

.stat-main-title {
  font-size: 28px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 70px;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.3;
}

.stat-grid-3x2 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 60px 40px;
  justify-items: center;
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.stat-number {
  font-size: 52px;
  font-weight: 700;
  color: #2ecc71; /* Green color for values */
  margin-bottom: 12px;
  line-height: 1;
}

.stat-label {
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  line-height: 1.4;
  margin: 0;
}

@media (max-width: 900px) {
  .stat-grid-3x2 {
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
  }
  .stat-number {
    font-size: 40px;
  }
}

@media (max-width: 600px) {
  .stat-grid-3x2 {
    grid-template-columns: 1fr;
  }
  .stat-main-title {
    font-size: 22px;
    margin-bottom: 40px;
  }
}

/* 
ORIGINAL STYLES (COMMENTED FOR BACKUP)
.mbm-lms-solution {
  background-color: #0f2b55;
  color: #fff;
  padding: 60px 20px;
  text-align: center;
}
.mbm-lms-title {
  text-align: left;
  font-size: 34px;
}
.mbm-lms-value {
  font-size: 42px;
  color: #00a3e0;
}
... (rest of old styles)
*/
</style>

