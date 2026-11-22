<script setup>
import { ref, onMounted } from 'vue'
import gsap from 'gsap'

const props = defineProps({
  animate: { type: Boolean, default: false },
  density: { type: String, default: 'normal' }, // 'normal' | 'sparse'
})

const root = ref(null)

const step = props.density === 'sparse' ? 12.5 : 8.333
const lines = []
for (let p = step; p < 100; p += step) lines.push(p)

// Hand-authored abstract contour bands — decorative topographic motif, not GIS data.
const contours = [
  'M -5 72 C 18 60, 32 78, 50 64 S 86 52, 108 66',
  'M -5 48 C 22 40, 40 54, 58 42 S 90 30, 108 44',
  'M -5 86 C 14 80, 30 90, 52 82 S 88 74, 108 84',
  'M -5 26 C 20 20, 36 30, 60 22 S 92 14, 108 24',
]

onMounted(() => {
  if (!props.animate || !root.value) return
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return

  const lineEls = root.value.querySelectorAll('.grid-field__line')
  const contourEls = root.value.querySelectorAll('.grid-field__contour')

  contourEls.forEach((pathEl) => {
    const length = pathEl.getTotalLength()
    gsap.set(pathEl, { strokeDasharray: length, strokeDashoffset: length })
  })
  lineEls.forEach((lineEl) => {
    const length = lineEl.tagName === 'line' ? lineLength(lineEl) : 0
    gsap.set(lineEl, { strokeDasharray: length, strokeDashoffset: length })
  })

  function lineLength(lineEl) {
    const x1 = Number(lineEl.getAttribute('x1'))
    const y1 = Number(lineEl.getAttribute('y1'))
    const x2 = Number(lineEl.getAttribute('x2'))
    const y2 = Number(lineEl.getAttribute('y2'))
    return Math.hypot(x2 - x1, y2 - y1)
  }

  const tl = gsap.timeline({ defaults: { ease: 'power2.out' } })
  tl.to(contourEls, { strokeDashoffset: 0, duration: 1.6, stagger: 0.12 }, 0)
  tl.to(lineEls, { strokeDashoffset: 0, duration: 1.1, stagger: 0.012 }, 0.1)
})
</script>

<template>
  <div ref="root" class="grid-field" aria-hidden="true">
    <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="grid-field__svg">
      <path
        v-for="(d, i) in contours"
        :key="`contour-${i}`"
        class="grid-field__contour"
        :d="d"
        fill="none"
      />
      <line
        v-for="(pos, i) in lines"
        :key="`v-${i}`"
        class="grid-field__line"
        :x1="pos"
        y1="0"
        :x2="pos"
        y2="100"
      />
      <line
        v-for="(pos, i) in lines"
        :key="`h-${i}`"
        class="grid-field__line"
        x1="0"
        :y1="pos"
        x2="100"
        :y2="pos"
      />
    </svg>
  </div>
</template>

<style scoped>
.grid-field {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.grid-field__svg {
  width: 100%;
  height: 100%;
}

.grid-field__line {
  stroke: var(--grid-line);
  stroke-width: 0.12;
}

.grid-field__contour {
  stroke: var(--grid-line-strong);
  stroke-width: 0.35;
}
</style>
