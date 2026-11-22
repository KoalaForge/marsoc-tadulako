<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'

// Renders admin-authored rich text. `html` is sanitized server-side
// (App\Support\Html) before it ever reaches a prop — never pass raw editor
// output here.
//
// Inline images are shown as small figures that text wraps around (so a
// photo-heavy article does not become a long scroll of full-width images);
// clicking one — or pressing Enter/Space on it — opens the full photo.
defineOptions({ inheritAttrs: false })

const props = defineProps({
  html: { type: String, required: true },
})

const root = ref(null)
const open = ref(null) // { src, alt } | null

function arm() {
  root.value?.querySelectorAll('img').forEach((img) => {
    img.setAttribute('tabindex', '0')
    img.setAttribute('role', 'button')
    img.setAttribute('aria-label', img.alt ? `${img.alt} — perbesar / enlarge` : 'Perbesar / enlarge')
    img.setAttribute('loading', 'lazy')
  })
}

function show(img) {
  open.value = { src: img.currentSrc || img.src, alt: img.alt }
}

function onClick(event) {
  if (event.target instanceof HTMLImageElement) show(event.target)
}

function onKey(event) {
  if (event.target instanceof HTMLImageElement && (event.key === 'Enter' || event.key === ' ')) {
    event.preventDefault()
    show(event.target)
  }
}

function close() {
  open.value = null
}

function onWindowKey(event) {
  if (event.key === 'Escape') close()
}

onMounted(() => {
  arm()
  window.addEventListener('keydown', onWindowKey)
})
onBeforeUnmount(() => window.removeEventListener('keydown', onWindowKey))
watch(() => props.html, () => nextTick(arm))
</script>

<template>
  <div ref="root" v-bind="$attrs" class="rich" @click="onClick" @keydown="onKey" v-html="html" />

  <Teleport to="body">
    <Transition name="rich-fade">
      <div v-if="open" class="rich-lightbox" role="dialog" aria-modal="true" @click.self="close">
        <button type="button" class="rich-lightbox__close mono-data" aria-label="Close" @click="close">✕</button>
        <img :src="open.src" :alt="open.alt" class="rich-lightbox__img" />
        <p v-if="open.alt" class="rich-lightbox__caption mono-data">{{ open.alt }}</p>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.rich {
  max-width: var(--measure);
  color: var(--ink-on-dark);
  font-size: 1.0625rem;
  line-height: 1.75;
  overflow-wrap: break-word;
  /* Contain floated figures so the next section never wraps around them. */
  display: flow-root;
}

/* One vertical rhythm for every block: flow spacing, not per-element margins. */
.rich :deep(> * + *) {
  margin-top: var(--space-3);
}

.rich :deep(h2),
.rich :deep(h3) {
  line-height: 1.15;
  /* More space above a heading than below it; headings never sit beside a float. */
  margin-top: var(--space-5);
  margin-bottom: calc(var(--space-1) * -0.5);
  clear: both;
}

.rich :deep(h2) {
  font-size: clamp(1.6rem, 2.6vw, 2rem);
}

.rich :deep(h3) {
  font-size: 1.35rem;
  color: var(--ink-gold-tint);
}

.rich :deep(strong) {
  font-weight: 700;
}

.rich :deep(a) {
  color: var(--ink-gold-tint);
  text-decoration: underline;
  text-underline-offset: 0.2em;
  text-decoration-thickness: 1px;
  transition: color 0.2s var(--ease-out-quint);
}

.rich :deep(a:hover) {
  color: var(--white);
}

.rich :deep(ul),
.rich :deep(ol) {
  padding-left: 1.4rem;
}

.rich :deep(li + li) {
  margin-top: 0.35rem;
}

.rich :deep(li::marker) {
  color: var(--ink-gold);
}

/* Pull quote: a hairline rule above and display type — no side stripe. */
.rich :deep(blockquote) {
  clear: both;
  margin: var(--space-4) 0 0;
  padding-top: var(--space-2);
  border-top: 1px solid var(--ink-gold);
  font-family: var(--font-display);
  font-size: clamp(1.35rem, 2.4vw, 1.75rem);
  line-height: 1.25;
  letter-spacing: 0.01em;
  color: var(--ink-gold-tint);
  text-wrap: balance;
}

.rich :deep(blockquote p) {
  margin: 0;
}

/*
 * Inline images: a compact figure the text wraps around, cropped to a fixed
 * frame so it never dictates the page's length. The full photo is one click
 * away (lightbox), so cropping costs nothing.
 */
.rich :deep(img) {
  float: right;
  clear: right;
  width: 42%;
  aspect-ratio: 4 / 3;
  object-fit: cover;
  margin: 0.35rem 0 var(--space-2) var(--space-4);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  background: var(--ground-900);
  cursor: zoom-in;
  transition: border-color 0.2s var(--ease-out-quint);
}

.rich :deep(img:hover),
.rich :deep(img:focus-visible) {
  border-color: var(--ink-gold-tint);
  outline: none;
}

.rich :deep(img:focus-visible) {
  box-shadow: 0 0 0 2px var(--ground-950), 0 0 0 4px var(--ink-gold-tint);
}

.rich :deep(::selection) {
  background: var(--ink-gold);
  color: var(--ink-on-light);
}

/* Narrow screens: no room to wrap, so a single short, full-width frame. */
@media (max-width: 640px) {
  .rich :deep(img) {
    float: none;
    width: 100%;
    aspect-ratio: 16 / 9;
    margin: var(--space-3) 0;
  }
}

/* ---------- Lightbox ---------- */
.rich-lightbox {
  position: fixed;
  inset: 0;
  z-index: 100;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--space-2);
  padding: var(--space-4);
  background: rgba(4, 13, 26, 0.92);
}

.rich-lightbox__img {
  max-width: min(94vw, 1400px);
  max-height: 82vh;
  object-fit: contain;
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
}

.rich-lightbox__caption {
  font-size: 0.78rem;
  color: var(--ink-on-dark-muted);
  text-align: center;
  max-width: 70ch;
}

.rich-lightbox__close {
  position: absolute;
  top: var(--space-3);
  right: var(--space-3);
  width: 2.5rem;
  height: 2.5rem;
  display: grid;
  place-items: center;
  background: var(--ground-900);
  color: var(--ink-on-dark);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  cursor: pointer;
}

.rich-lightbox__close:hover,
.rich-lightbox__close:focus-visible {
  border-color: var(--ink-gold-tint);
  color: var(--ink-gold-tint);
}

.rich-fade-enter-active,
.rich-fade-leave-active {
  transition: opacity 0.2s var(--ease-out-quint);
}

.rich-fade-enter-from,
.rich-fade-leave-to {
  opacity: 0;
}
</style>
