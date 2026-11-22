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
  activities: { type: Array, default: () => [] },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)
const activeCategory = ref('All')

const categoryOptions = computed(() => {
  const seen = new Map()
  props.activities.forEach((a) => {
    if (!seen.has(a.category.en)) seen.set(a.category.en, a.category)
  })
  return [
    { key: 'All', label: t('common.all') },
    ...[...seen.values()].map((c) => ({ key: c.en, label: localized(c, locale.value) })),
  ]
})

const filtered = computed(() =>
  activeCategory.value === 'All' ? props.activities : props.activities.filter((a) => a.category.en === activeCategory.value),
)
</script>

<template>
  <div>
    <PageHero page="activities" :block="blocks.hero" />

    <section class="page-section page-section--wide">
      <EmptyState v-if="!activities.length" :message="t('empty.activities')" />
      <div v-if="activities.length" class="filters">
        <button
          v-for="opt in categoryOptions"
          :key="opt.key"
          type="button"
          class="filters__chip"
          :class="{ 'filters__chip--active': activeCategory === opt.key }"
          @click="activeCategory = opt.key"
        >
          {{ opt.label }}
        </button>
      </div>

      <ul v-if="activities.length" class="log">
        <li v-for="item in filtered" :key="item.slug" class="log__row">
          <Link :href="withLocale(localePrefix, `/activities/${item.slug}`)" class="log__link">
            <img v-if="item.thumb" :src="item.thumb" alt="" class="log__thumb" loading="lazy" />
            <span v-else class="log__thumb log__thumb--empty" aria-hidden="true" />
            <span class="log__date mono-data">{{ item.date }}</span>
            <span class="log__title">{{ localized(item.title, locale) }}</span>
            <span class="log__category">{{ localized(item.category, locale) }}</span>
            <span class="log__location mono-data">{{ item.location }}</span>
          </Link>
        </li>
      </ul>
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
  font-size: 0.72rem;
  letter-spacing: 0.04em;
  padding: 0.4rem 0.8rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  background: transparent;
  color: var(--ink-on-dark-muted);
  cursor: pointer;
  transition: border-color 0.2s ease, color 0.2s ease;
}

.filters__chip--active {
  border-color: var(--red);
  color: var(--white);
  background: var(--red);
}

.log {
  list-style: none;
  margin: 0;
  padding: 0;
}

.log__row {
  border-bottom: 1px solid var(--ground-900);
}

.log__row:first-child {
  border-top: 1px solid var(--ground-900);
}

.log__link {
  display: grid;
  grid-template-columns: 6rem 7rem 1fr 10rem 12rem;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-3) var(--space-2);
  text-decoration: none;
  color: inherit;
  transition: background-color 0.2s ease;
}

.log__thumb {
  width: 6rem;
  aspect-ratio: 16 / 9;
  object-fit: cover;
  border-radius: var(--radius-sm);
  display: block;
}

.log__thumb--empty {
  border: 1px dashed var(--ground-line);
}

.log__link:hover {
  background: var(--ground-900);
}

.log__date {
  font-size: 0.78rem;
  color: var(--ink-gold-tint);
}

.log__title {
  font-family: var(--font-display);
  font-size: 1.1rem;
}

.log__category {
  font-size: 0.8rem;
  color: var(--ink-on-dark-muted);
}

.log__location {
  font-size: 0.75rem;
  color: var(--ink-on-dark-muted);
  text-align: right;
}

@media (max-width: 760px) {
  .log__link {
    grid-template-columns: 1fr;
    gap: 0.3rem;
  }

  .log__thumb--empty {
    display: none;
  }

  .log__thumb {
    width: 100%;
  }

  .log__location {
    text-align: left;
  }
}
</style>
