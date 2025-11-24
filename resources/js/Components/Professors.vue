<template>
  <section class="mbm-lms-solution" ref="solutionSection" id="why-us">
    <div class="mbm-lms-container">
      <h2 class="mbm-lms-title">Why MBM University Chooses Smart Technology For Smarter Learning.</h2>
      <div class="mbm-lms-grid">
        <div class="mbm-lms-stat" v-for="(stat, index) in countedStats" :key="index">
          <div class="mbm-lms-value">{{ stat.displayValue }}</div>
          <div class="mbm-lms-label" v-html="stat.label"></div>
        </div>
        <!-- <div class="mbm-lms-award">
          <img src="/images/icon1.svg" alt="Award Badge" />
        </div> -->
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
.mbm-lms-solution {
  background-color: #0f2b55;
  color: #fff;
  padding: 60px 20px;
  text-align: center;
  font-family: 'Inter', sans-serif;
}

.mbm-lms-container {
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
  margin: 0 auto;
}

.mbm-lms-title {
  text-align: left;
  font-size: 34px;
  font-weight: bold;
  margin-bottom: 40px;
}

.mbm-lms-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  gap: 30px;
}

.mbm-lms-stat {
  min-width: 100px;
  max-width: 140px;
}

.mbm-lms-value {
  font-size: 42px;
  font-weight: bold;
  color: #00a3e0;
  margin-bottom: 8px;
}

.mbm-lms-label {
  font-size: 13px;
  color: #fff;
  line-height: 1.4;
}

.mbm-lms-award {
  flex: 0 0 auto;
  max-width: 110px;
}

.mbm-lms-award img {
  width: 100%;
  height: auto;
}

@media (max-width: 768px) {
  .mbm-lms-title {
    font-size: 20px;
  }

  .mbm-lms-grid {
    gap: 20px;
  }

  .mbm-lms-value {
    font-size: 22px;
  }

  .mbm-lms-label {
    font-size: 12px;
  }
}
</style>
