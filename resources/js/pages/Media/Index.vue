<script setup>
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../../layouts/SiteLayout.vue'
import EmptyState from '../../components/EmptyState.vue'
import PageHero from '../../components/PageHero.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'

defineOptions({ layout: SiteLayout })

const props = defineProps({
  blocks: { type: Object, default: () => ({}) },
  items: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  categoryLabels: { type: Object, default: () => ({}) },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)

const categories = ['All', ...props.categories]
const activeCategory = ref('All')

const filtered = computed(() =>
  activeCategory.value === 'All' ? props.items : props.items.filter((m) => m.category === activeCategory.value),
)

function categoryLabel(cat) {
  if (cat === 'All') return t('common.all')
  return props.categoryLabels?.[locale.value]?.[cat] ?? props.categoryLabels?.en?.[cat] ?? cat
}

function lockTileSize(el) {
  const rect = el.getBoundingClientRect()
  el.style.width = `${rect.width}px`
  el.style.height = `${rect.height}px`
}
</script>

<template>
  <div>
    <PageHero page="media" :block="blocks.hero" />

    <section class="page-section page-section--wide">
      <EmptyState v-if="!items.length" :message="t('empty.media')" />
      <div v-if="items.length" class="filters">
        <button
          v-for="cat in categories"
          :key="cat"
          type="button"
          class="filters__chip"
          :class="{ 'filters__chip--active': activeCategory === cat }"
          @click="activeCategory = cat"
        >
          {{ categoryLabel(cat) }}
        </button>
      </div>

      <EmptyState v-if="items.length && !filtered.length" :message="t('empty.mediaCategory')" />
      <TransitionGroup name="fog-grid" tag="div" class="gallery" @before-leave="lockTileSize">
        <Link
          v-for="item in filtered"
          :key="item.id"
          :href="withLocale(localePrefix, `/media/${item.id}`)"
          class="tile"
          :class="{ 'tile--placeholder': !item.photos?.length }"
        >
          <span v-if="item.photos?.length > 1" class="tile__count mono-data">+{{ item.photos.length - 1 }}</span>
          <img v-if="item.photos?.length" :src="item.photos[0]" :alt="localized(item.title, locale)" />
          <div v-else class="tile__placeholder">
            <span class="mono-data">{{ t('common.documentationPending') }}</span>
          </div>
          <div class="tile__body">
            <span class="tile__title">{{ localized(item.title, locale) }}</span>
            <span class="tile__meta mono-data">{{ [categoryLabel(item.category), item.year].filter(Boolean).join(' · ') }}</span>
          </div>
        </Link>
      </TransitionGroup>
    </section>
  </div>
</template>

<style scoped>
.filters {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-2);
  margin-bottom: var(--space-5);
}

.filters__chip {
  font-family: var(--font-mono);
  font-size: 0.7rem;
  letter-spacing: 0.03em;
  padding: 0.4rem 0.75rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  background: transparent;
  color: var(--ink-on-dark-muted);
  cursor: pointer;
}

.filters__chip--active {
  border-color: var(--red);
  color: var(--white);
  background: var(--red);
}

.gallery {
  position: relative;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: var(--space-4);
}

.tile {
  position: relative;
  margin: 0;
  display: flex;
  flex-direction: column;
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  overflow: hidden;
  text-decoration: none;
  color: inherit;
  transition: border-color 0.2s ease, transform 0.2s ease;
}

.tile:hover {
  border-color: var(--gold);
  transform: translateY(-3px);
}

.tile img {
  width: 100%;
  aspect-ratio: 4 / 5;
  object-fit: cover;
  filter: grayscale(0.1) contrast(1.05);
}

.tile__placeholder {
  aspect-ratio: 4 / 5;
  display: flex;
  align-items: center;
  justify-content: center;
  background: repeating-linear-gradient(135deg, var(--ground-950), var(--ground-950) 10px, var(--ground-900) 10px, var(--ground-900) 20px);
}

.tile__placeholder span {
  font-size: 0.62rem;
  letter-spacing: 0.08em;
  color: var(--ink-gold-tint);
}

.tile__count {
  position: absolute;
  z-index: 1;
  right: 0.5rem;
  top: 0.5rem;
  font-size: 0.6rem;
  padding: 0.2rem 0.4rem;
  border-radius: var(--radius-sm);
  background: rgba(4, 13, 26, 0.8);
  border: 1px solid var(--ground-line);
  color: var(--ink-gold-tint);
}

.tile__body {
  padding: var(--space-3);
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.tile__title {
  font-size: 0.88rem;
}

.tile__meta {
  font-size: 0.65rem;
  color: var(--ink-on-dark-muted);
}

.fog-grid-enter-active,
.fog-grid-leave-active {
  transition: opacity 0.4s var(--ease-out-quint), filter 0.4s var(--ease-out-quint);
}

.fog-grid-move {
  transition: transform 0.4s var(--ease-out-quint);
}

.fog-grid-enter-from,
.fog-grid-leave-to {
  opacity: 0;
  filter: blur(5px);
}

.fog-grid-leave-active {
  position: absolute;
}

@media (prefers-reduced-motion: reduce) {
  .fog-grid-enter-active,
  .fog-grid-leave-active,
  .fog-grid-move {
    transition: opacity 0.2s linear;
  }
  .fog-grid-enter-from,
  .fog-grid-leave-to {
    filter: none;
  }
}
</style>
