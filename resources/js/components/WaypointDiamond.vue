<script setup>
defineProps({
  size: { type: Number, default: 14 },
  tone: { type: String, default: 'red' }, // 'red' | 'gold' | 'white'
  pulse: { type: Boolean, default: false },
  filled: { type: Boolean, default: true },
})
</script>

<template>
  <span
    class="waypoint"
    :class="[`waypoint--${tone}`, { 'waypoint--pulse': pulse, 'waypoint--outline': !filled }]"
    :style="{ width: `${size}px`, height: `${size}px` }"
    aria-hidden="true"
  />
</template>

<style scoped>
.waypoint {
  display: inline-block;
  flex-shrink: 0;
  transform: rotate(45deg);
  position: relative;
}

.waypoint--red {
  background: var(--red);
}

.waypoint--gold {
  background: var(--gold);
}

.waypoint--white {
  background: var(--white);
}

.waypoint--outline {
  background: transparent;
  border: 2px solid currentColor;
}

.waypoint--pulse::after {
  content: '';
  position: absolute;
  inset: -6px;
  border: 1px solid currentColor;
  opacity: 0;
  animation: waypoint-pulse 2.4s var(--ease-out-quint) infinite;
}

@keyframes waypoint-pulse {
  0% {
    opacity: 0.55;
    transform: scale(0.6);
  }
  80%,
  100% {
    opacity: 0;
    transform: scale(1.9);
  }
}

@media (prefers-reduced-motion: reduce) {
  .waypoint--pulse::after {
    animation: none;
    opacity: 0;
  }
}
</style>
