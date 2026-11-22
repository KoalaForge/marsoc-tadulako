<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../../layouts/SiteLayout.vue'
import EmptyState from '../../components/EmptyState.vue'
import MediaSlider from '../../components/MediaSlider.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'

defineOptions({ layout: SiteLayout })

const props = defineProps({
  item: { type: Object, default: null },
  relatedActivity: { type: Object, default: null },
  categoryLabels: { type: Object, default: () => ({}) },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)

function categoryLabel(cat) {
  return props.categoryLabels?.[locale.value]?.[cat] ?? props.categoryLabels?.en?.[cat] ?? cat
}
</script>

<template>
  <article v-if="item" class="media-detail page-section">
    <Link :href="withLocale(localePrefix, '/media')" class="media-detail__back mono-data">{{ t('common.backToMedia') }}</Link>

    <h1 class="media-detail__title">{{ localized(item.title, locale) }}</h1>
    <div class="media-detail__meta mono-data">
      <span>{{ categoryLabel(item.category) }}</span>
      <span v-if="item.year">{{ item.year }}</span>
    </div>

    <div v-if="item.photos?.length" class="media-detail__slider">
      <MediaSlider :photos="item.photos" :alt="localized(item.title, locale)" />
    </div>
    <EmptyState v-else :message="t('empty.photos')" />

    <p v-if="localized(item.caption, locale)" class="media-detail__caption">{{ localized(item.caption, locale) }}</p>

    <section v-if="relatedActivity" class="media-detail__related">
      <h2>{{ t('media.relatedActivityTitle') }}</h2>
      <Link :href="withLocale(localePrefix, `/activities/${relatedActivity.slug}`)">
        {{ localized(relatedActivity.title, locale) }}
      </Link>
    </section>
  </article>

  <div v-else class="page-section empty-state">
    <p class="empty-state__title">{{ t('media.notFound') }}</p>
    <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/media')">{{ t('common.backToMedia') }}</Link>
  </div>
</template>

<style scoped>
.media-detail {
  max-width: 760px;
}

.media-detail__back {
  display: block;
  margin-bottom: var(--space-4);
  color: var(--ink-gold-tint);
  text-decoration: none;
  font-size: 0.8rem;
}

.media-detail__title {
  margin-top: 0.4rem;
  font-size: clamp(1.8rem, 3.6vw, 2.6rem);
}

.media-detail__meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-top: var(--space-3);
  font-size: 0.78rem;
  color: var(--ink-on-dark-muted);
}

.media-detail__slider {
  margin-top: var(--space-5);
  max-width: 720px;
}

.media-detail__caption {
  margin-top: var(--space-4);
  color: var(--ink-on-dark-muted);
  max-width: var(--measure);
}

.media-detail__related {
  margin-top: var(--space-6);
}

.media-detail__related h2 {
  font-family: var(--font-display);
  font-size: 1.3rem;
  margin-bottom: var(--space-2);
}

.media-detail__related a {
  color: var(--white);
  text-decoration: none;
  border-bottom: 1px solid var(--ground-line);
}
</style>
