<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import RichContent from '../../components/RichContent.vue'
import SiteLayout from '../../layouts/SiteLayout.vue'
import WaypointDiamond from '../../components/WaypointDiamond.vue'
import MediaSlider from '../../components/MediaSlider.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'

defineOptions({ layout: SiteLayout })

defineProps({
  activity: { type: Object, default: null },
  relatedMembers: { type: Array, default: () => [] },
  relatedPhoto: { type: Object, default: null },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)
</script>

<template>
  <article v-if="activity" class="activity page-section">
    <Link :href="withLocale(localePrefix, '/activities')" class="activity__back mono-data">{{ t('common.backToFieldLog') }}</Link>

    <figure v-if="activity.banner" class="activity__banner">
      <img :src="activity.banner" :alt="localized(activity.title, locale)" />
    </figure>
    <h1 class="activity__title">{{ localized(activity.title, locale) }}</h1>
    <div class="activity__meta mono-data">
      <span v-if="localized(activity.category, locale)">{{ localized(activity.category, locale) }}</span>
      <span v-if="activity.date">{{ activity.date }}</span>
      <span v-if="activity.location">{{ activity.location }}</span>
      <span v-if="activity.grid" class="activity__grid"><WaypointDiamond :size="7" tone="gold" />{{ activity.grid }}</span>
      <span v-if="activity.participants != null">{{ t('activities.participants', { n: activity.participants }) }}</span>
    </div>

    <div v-if="relatedPhoto" class="activity__photo">
      <MediaSlider :photos="relatedPhoto.photos || []" :alt="localized(relatedPhoto.title, locale)" />
      <p class="activity__photo-caption">{{ localized(relatedPhoto.caption, locale) }}</p>
      <Link :href="withLocale(localePrefix, `/media/${relatedPhoto.id}`)" class="activity__photo-link mono-data">
        {{ t('media.viewInArchive') }} →
      </Link>
    </div>

    <section v-if="localized(activity.overview, locale)" class="activity__block">
      <h2>{{ t('activities.overviewTitle') }}</h2>
      <p>{{ localized(activity.overview, locale) }}</p>
    </section>

    <!-- Sanitized server-side (App\Support\Html), never raw editor output. -->
    <section v-if="localized(activity.content, locale)" class="activity__block">
      <RichContent :html="localized(activity.content, locale)" />
    </section>

    <section v-if="relatedMembers.length" class="activity__block">
      <h2>{{ t('activities.relatedMembersTitle') }}</h2>
      <ul class="activity__members">
        <li v-for="m in relatedMembers" :key="m.callsign">
          <Link :href="withLocale(localePrefix, `/personnel/${m.callsign.toLowerCase()}`)">{{ m.callsign }}</Link>
          <span class="mono-data"> — {{ localized(m.role, locale) }}</span>
        </li>
      </ul>
    </section>
  </article>

  <div v-else class="page-section empty-state">
    <p class="empty-state__title">{{ t('common.eventNotFound') }}</p>
    <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/activities')">{{ t('common.backToFieldLog') }}</Link>
  </div>
</template>

<style scoped>
.activity {
  max-width: 760px;
}

.activity__back {
  display: block;
  margin-bottom: var(--space-4);
  color: var(--ink-gold-tint);
  text-decoration: none;
  font-size: 0.8rem;
}

.activity__title {
  margin-top: 0.4rem;
  font-size: clamp(1.8rem, 3.6vw, 2.6rem);
}

.activity__meta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  margin-top: var(--space-3);
  font-size: 0.78rem;
  color: var(--ink-on-dark-muted);
}

.activity__grid {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}

.activity__photo {
  margin-top: var(--space-5);
  max-width: 480px;
}

.activity__photo-caption {
  margin-top: var(--space-2);
  font-size: 0.85rem;
  color: var(--ink-on-dark-muted);
}

.activity__photo-link {
  display: inline-block;
  margin-top: var(--space-2);
  color: var(--ink-gold-tint);
  text-decoration: none;
  font-size: 0.78rem;
}

.activity__photo-link:hover {
  color: var(--gold);
}

.activity__banner {
  margin: 0 0 var(--space-4);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  overflow: hidden;
  aspect-ratio: 11 / 5;
  background: var(--ground-900);
}

.activity__banner img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.activity__block {
  margin-top: var(--space-5);
}

.activity__block h2 {
  font-family: var(--font-display);
  font-size: 1.3rem;
  margin-bottom: var(--space-2);
}

.activity__block p {
  color: var(--ink-on-dark-muted);
}

.activity__members {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.activity__members a {
  color: var(--white);
  text-decoration: none;
  border-bottom: 1px solid var(--ground-line);
}
</style>
