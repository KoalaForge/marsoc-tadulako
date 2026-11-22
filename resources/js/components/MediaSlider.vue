<script setup>
import { ref, computed, watch } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  photos: { type: Array, default: () => [] },
  alt: { type: String, default: '' },
})

const { t } = useI18n()

const index = ref(0)
const hasMultiple = computed(() => props.photos.length > 1)

const ZOOM_STEPS = [1, 1.5, 2, 2.5, 3]
const zoom = ref(1)
const pan = ref({ x: 0, y: 0 })
const dragging = ref(false)
const dragStart = ref({ x: 0, y: 0, panX: 0, panY: 0 })

function resetZoom() {
  zoom.value = 1
  pan.value = { x: 0, y: 0 }
}

watch(index, resetZoom)

function clampPan(next, z) {
  const limit = (z - 1) * 140 // px of allowed travel per zoom step, tuned for the frame size
  return {
    x: Math.max(-limit, Math.min(limit, next.x)),
    y: Math.max(-limit, Math.min(limit, next.y)),
  }
}

function zoomIn() {
  const i = ZOOM_STEPS.indexOf(zoom.value)
  zoom.value = ZOOM_STEPS[Math.min(i + 1, ZOOM_STEPS.length - 1)]
  pan.value = clampPan(pan.value, zoom.value)
}

function zoomOut() {
  const i = ZOOM_STEPS.indexOf(zoom.value)
  zoom.value = ZOOM_STEPS[Math.max(i - 1, 0)]
  if (zoom.value === 1) pan.value = { x: 0, y: 0 }
  else pan.value = clampPan(pan.value, zoom.value)
}

function toggleZoom() {
  if (zoom.value === 1) {
    zoom.value = 2
  } else {
    resetZoom()
  }
}

function startDrag(e) {
  if (zoom.value === 1) return
  dragging.value = true
  const point = e.touches ? e.touches[0] : e
  dragStart.value = { x: point.clientX, y: point.clientY, panX: pan.value.x, panY: pan.value.y }
}

function onDrag(e) {
  if (!dragging.value) return
  const point = e.touches ? e.touches[0] : e
  const next = {
    x: dragStart.value.panX + (point.clientX - dragStart.value.x),
    y: dragStart.value.panY + (point.clientY - dragStart.value.y),
  }
  pan.value = clampPan(next, zoom.value)
}

function endDrag() {
  dragging.value = false
}

function next() {
  index.value = (index.value + 1) % props.photos.length
}

function prev() {
  index.value = (index.value - 1 + props.photos.length) % props.photos.length
}

function goTo(i) {
  index.value = i
}
</script>

<template>
  <div v-if="photos.length" class="slider" :class="{ 'slider--single': !hasMultiple }">
    <div class="slider__frame">
      <Transition name="fog-slide" mode="out-in">
        <div
          :key="index"
          class="slider__viewport"
          :class="{ 'slider__viewport--zoomed': zoom > 1 }"
          @mousedown="startDrag"
          @mousemove="onDrag"
          @mouseup="endDrag"
          @mouseleave="endDrag"
          @touchstart.passive="startDrag"
          @touchmove.passive="onDrag"
          @touchend="endDrag"
          @dblclick="toggleZoom"
        >
          <img
            :src="photos[index]"
            :alt="alt"
            class="slider__img"
            :style="{ transform: `translate(${pan.x}px, ${pan.y}px) scale(${zoom})` }"
            draggable="false"
          />
        </div>
      </Transition>

      <template v-if="hasMultiple">
        <button type="button" class="slider__nav slider__nav--prev" :aria-label="t('media.prevPhoto')" @click="prev">‹</button>
        <button type="button" class="slider__nav slider__nav--next" :aria-label="t('media.nextPhoto')" @click="next">›</button>
        <span class="slider__counter mono-data">{{ index + 1 }} / {{ photos.length }}</span>
      </template>

      <div class="slider__zoom">
        <button type="button" class="slider__zoom-btn" :aria-label="t('media.zoomOut')" :disabled="zoom === 1" @click="zoomOut">−</button>
        <span class="slider__zoom-level mono-data">{{ Math.round(zoom * 100) }}%</span>
        <button type="button" class="slider__zoom-btn" :aria-label="t('media.zoomIn')" :disabled="zoom === ZOOM_STEPS[ZOOM_STEPS.length - 1]" @click="zoomIn">+</button>
      </div>
    </div>

    <div v-if="hasMultiple" class="slider__dots">
      <button
        v-for="(photo, i) in photos"
        :key="i"
        type="button"
        class="slider__dot"
        :class="{ 'slider__dot--active': i === index }"
        :aria-label="t('media.goToPhoto', { n: i + 1 })"
        @click="goTo(i)"
      />
    </div>
  </div>
  <div v-else class="slider slider--empty empty-state">
    <p class="empty-state__title">{{ t('common.documentationPending') }}</p>
  </div>
</template>

<style scoped>
.slider {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.slider__frame {
  position: relative;
  aspect-ratio: 4 / 3;
  max-height: 70vh;
  background: var(--ground-950);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  overflow: hidden;
}

.slider__viewport {
  width: 100%;
  height: 100%;
  overflow: hidden;
  cursor: zoom-in;
}

.slider__viewport--zoomed {
  cursor: grab;
}

.slider__viewport--zoomed:active {
  cursor: grabbing;
}

.slider__img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  transition: transform 0.15s ease-out;
  user-select: none;
  pointer-events: none;
}

.slider__nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 2.5rem;
  height: 2.5rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  background: rgba(4, 13, 26, 0.72);
  color: var(--white);
  font-size: 1.4rem;
  line-height: 1;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: border-color 0.2s ease, color 0.2s ease;
}

.slider__nav:hover {
  border-color: var(--gold);
  color: var(--gold);
}

.slider__nav--prev {
  left: var(--space-2);
}

.slider__nav--next {
  right: var(--space-2);
}

.slider__counter {
  position: absolute;
  right: var(--space-2);
  bottom: var(--space-2);
  font-size: 0.68rem;
  padding: 0.25rem 0.5rem;
  border-radius: var(--radius-sm);
  background: rgba(4, 13, 26, 0.72);
  color: var(--ink-gold-tint);
}

.slider__zoom {
  position: absolute;
  left: var(--space-2);
  bottom: var(--space-2);
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.25rem 0.4rem;
  border-radius: var(--radius-sm);
  background: rgba(4, 13, 26, 0.72);
  border: 1px solid var(--ground-line);
}

.slider__zoom-btn {
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  background: transparent;
  color: var(--white);
  font-size: 1rem;
  line-height: 1;
  cursor: pointer;
  border-radius: var(--radius-sm);
}

.slider__zoom-btn:hover:not(:disabled) {
  color: var(--gold);
}

.slider__zoom-btn:disabled {
  color: var(--ink-on-dark-muted);
  cursor: not-allowed;
}

.slider__zoom-level {
  font-size: 0.65rem;
  color: var(--ink-gold-tint);
  min-width: 2.6em;
  text-align: center;
}

.slider__dots {
  display: flex;
  gap: 0.4rem;
  justify-content: center;
  flex-wrap: wrap;
}

.slider__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  border: 1px solid var(--ground-line);
  background: transparent;
  cursor: pointer;
  padding: 0;
}

.slider__dot--active {
  background: var(--gold);
  border-color: var(--gold);
}

.slider--empty {
  aspect-ratio: 4 / 3;
  justify-content: center;
}

/* Reuses the site's fog-dissolve motion language (opacity + blur) rather than a new transition. */
.fog-slide-enter-active,
.fog-slide-leave-active {
  transition: opacity 0.35s var(--ease-out-quint), filter 0.35s var(--ease-out-quint);
}

.fog-slide-enter-from,
.fog-slide-leave-to {
  opacity: 0;
  filter: blur(6px);
}

@media (prefers-reduced-motion: reduce) {
  .fog-slide-enter-active,
  .fog-slide-leave-active {
    transition: opacity 0.2s linear;
  }
  .fog-slide-enter-from,
  .fog-slide-leave-to {
    filter: none;
  }
  .slider__img {
    transition: none;
  }
}
</style>
