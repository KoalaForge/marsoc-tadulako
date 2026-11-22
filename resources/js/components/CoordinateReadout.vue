<script setup>
import { ref, onMounted } from 'vue'
import gsap from 'gsap'

const props = defineProps({
  text: { type: String, required: true },
  animate: { type: Boolean, default: false },
  delay: { type: Number, default: 0 },
})

const el = ref(null)

onMounted(() => {
  if (!props.animate || !el.value) return
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return
  const full = props.text
  el.value.textContent = ''
  const chars = full.split('')
  const tl = gsap.timeline({ delay: props.delay })
  chars.forEach((char, i) => {
    tl.call(
      () => {
        el.value.textContent = full.slice(0, i + 1)
      },
      null,
      i * 0.035,
    )
  })
})
</script>

<template>
  <span ref="el" class="coord mono-data">{{ animate ? '' : text }}</span>
</template>

<style scoped>
.coord {
  font-size: 0.8rem;
  color: var(--ink-gold-tint);
  white-space: pre;
}
</style>
