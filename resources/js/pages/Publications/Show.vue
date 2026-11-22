<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import RichContent from '../../components/RichContent.vue'
import SiteLayout from '../../layouts/SiteLayout.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'

defineOptions({ layout: SiteLayout })

defineProps({
  publication: { type: Object, default: null },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)
</script>

<template>
  <article v-if="publication" class="article page-section">
    <Link :href="withLocale(localePrefix, '/publications')" class="article__back mono-data">{{ t('common.backToPublications') }}</Link>

    <figure v-if="publication.banner" class="article__banner">
      <img :src="publication.banner" :alt="localized(publication.title, locale)" />
    </figure>
    <h1 class="article__title">{{ localized(publication.title, locale) }}</h1>
    <p class="article__meta mono-data">
      {{ [localized(publication.category, locale), publication.author, publication.date].filter(Boolean).join(' · ') }}
    </p>

    <!-- Sanitized server-side (App\\Support\\Html). -->
    <div v-if="localized(publication.content, locale)" class="article__body">
      <RichContent :html="localized(publication.content, locale)" />
    </div>

    <footer v-if="publication.sources?.length" class="article__sources">
      <h2 class="mono-data">{{ t('common.sourcesTitle') }}</h2>
      <ul>
        <li v-for="s in publication.sources" :key="s">{{ s }}</li>
      </ul>
    </footer>
  </article>

  <div v-else class="page-section empty-state">
    <p class="empty-state__title">{{ t('common.articleNotFound') }}</p>
    <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/publications')">{{ t('common.backToPublications') }}</Link>
  </div>
</template>

<style scoped>
.article {
  /* Wider than the text measure so the banner can breathe; text itself
     is capped at the measure inside RichContent. */
  max-width: 56rem;
}

.article__banner {
  margin: 0 0 var(--space-5);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  overflow: hidden;
  aspect-ratio: 11 / 5;
  background: var(--ground-900);
}

.article__banner img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.article__back {
  display: block;
  margin-bottom: var(--space-4);
  color: var(--ink-gold-tint);
  text-decoration: none;
  font-size: 0.8rem;
}

.article__title {
  margin-top: 0.4rem;
  font-size: clamp(1.8rem, 3.6vw, 2.6rem);
}

.article__meta {
  margin-top: var(--space-2);
  font-size: 0.78rem;
  color: var(--ink-on-dark-muted);
}

.article__body {
  margin-top: var(--space-5);
}

.article__sources {
  margin-top: var(--space-6);
  padding-top: var(--space-4);
  border-top: 1px dashed var(--ground-line);
}

.article__sources h2 {
  font-size: 0.72rem;
  letter-spacing: 0.1em;
  color: var(--ink-gold-tint);
  margin-bottom: var(--space-2);
}

.article__sources ul {
  margin: 0;
  padding-left: 1.1rem;
  color: var(--ink-on-dark-muted);
  font-size: 0.85rem;
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}
</style>
