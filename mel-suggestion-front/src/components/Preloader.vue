<template>
  <!-- Preloader component -->
  <div class="preloader" :style="cssVars">
    <span class="rot-1"></span>
    <span class="rot-2"></span>
    <span class="rot-3"></span>
    <span class="rot-4"></span>
    <span class="rot-5"></span>
    <span class="rot-6"></span>
    <span class="rot-7"></span>
    <span class="rot-8"></span>
  </div>
</template>

<script>
export default {
  // Component name
  name: 'Preloader',
  // Component props
  props: {
    // Color of the preloader circles
    color: {
      type: String,
      default: '#41b883',
    },
    // Scale factor for the preloader size
    scale: {
      type: Number,
      default: 0.3,
    },
  },
  computed: {
    // Computed CSS variables based on props
    cssVars() {
      return {
        '--color': this.color,
        '--scale': this.scale,
      }
    }
  }
}
</script>

<style scoped>
/* Scoped styles for the Preloader component */
.preloader {
  /* Preloader container styles */
  position: relative;
  width: 125px;
  height: 125px;
  margin: auto;
  transform: scale(var(--scale));
}
.preloader > span {
  /* Preloader circle styles */
  position: absolute;
  background-color: transparent;
  height: 22px;
  width: 22px;
  border-radius: 12px;
  animation-name: f_fadeG;
  animation-duration: 1.2s;
  animation-iteration-count: infinite;
  animation-direction: normal;
}
/* Individual circle position and animation */
.rot-1 {
  left: 0;
  top: 51px;
  animation-delay: 0.45s;
}
.rot-2 {
  left: 15px;
  top: 15px;
  animation-delay: 0.6s;
}
.rot-3 {
  left: 51px;
  top: 0;
  animation-delay: 0.75s;
}
.rot-4 {
  right: 15px;
  top: 15px;
  animation-delay: 0.9s;
}
.rot-5 {
  right: 0;
  top: 51px;
  animation-delay: 1.05s;
}
.rot-6 {
  right: 15px;
  bottom: 15px;
  animation-delay: 1.2s;
}
.rot-7 {
  left: 51px;
  bottom: 0;
  animation-delay: 1.35s;
}
.rot-8 {
  left: 15px;
  bottom: 15px;
  animation-delay: 1.5s;
}
/* Keyframe animation definition */
@keyframes f_fadeG {
  0% {
    background-color: var(--color);
  }
  100% {
    background-color: transparent;
  }
}
</style>