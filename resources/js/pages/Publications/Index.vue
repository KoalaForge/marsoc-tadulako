<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../../layouts/SiteLayout.vue'
import EmptyState from '../../components/EmptyState.vue'
import PageHero from '../../components/PageHero.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'

defineOptions({ layout: SiteLayout })

defineProps({
  blocks: { type: Object, default: () => ({}) },
  publications: { type: Array, default: () => [] },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)
</script>

<template>
  <div>
    <PageHero page="publications" :block="blocks.hero" />

    <section class="page-section page-section--wide">
      <EmptyState v-if="!publications.length" :message="t('empty.publications')" />
      <ul v-else class="articles">
        <li v-for="pub in publications" :key="pub.slug" class="article-row">
          <Link :href="withLocale(localePrefix, `/publications/${pub.slug}`)" class="article-row__link">
            <img v-if="pub.thumb" :src="pub.thumb" alt="" class="article-row__thumb" loading="lazy" />
            <span v-else class="article-row__thumb article-row__thumb--empty" aria-hidden="true" />
            <div class="article-row__text">
              <h2 class="article-row__title">{{ localized(pub.title, locale) }}</h2>
              <p class="article-row__excerpt">{{ localized(pub.excerpt, locale) }}</p>
              <span class="article-row__meta mono-data">
                {{ [localized(pub.category, locale), pub.author, pub.date].filter(Boolean).join(' · ') }}
              </span>
            </div>
          </Link>
        </li>
      </ul>
    </section>
  </div>
</template>

<style scoped>
.articles {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.article-row {
  border-bottom: 1px solid var(--ground-900);
}

.article-row:first-child {
  border-top: 1px solid var(--ground-900);
}

.article-row__link {
  /* Small thumbnail beside the text: a list of articles must scan quickly. */
  display: grid;
  grid-template-columns: clamp(6.5rem, 20vw, 12rem) 1fr;
  gap: var(--space-4);
  align-items: start;
  padding: var(--space-4) var(--space-2);
  text-decoration: none;
  color: inherit;
  transition: background-color 0.2s ease;
}

.article-row__link:hover {
  background: var(--ground-900);
}

.article-row__title {
  font-family: var(--font-display);
  font-size: 1.4rem;
  margin-top: 0.3rem;
}

.article-row__excerpt {
  margin-top: 0.4rem;
  color: var(--ink-on-dark-muted);
  max-width: 62ch;
}

.article-row__meta {
  display: block;
  margin-top: var(--space-2);
  font-size: 0.72rem;
  color: var(--ink-on-dark-muted);
}
.article-row__thumb {
  width: 100%;
  aspect-ratio: 16 / 10;
  object-fit: cover;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  display: block;
}

.article-row__thumb--empty {
  border-style: dashed;
}

.article-row__title {
  margin-top: 0;
}

@media (max-width: 560px) {
  .article-row__link {
    gap: var(--space-3);
  }

  .article-row__title {
    font-size: 1.15rem;
  }

  /* Excerpt drops on phones: thumbnail + title + meta is enough to choose. */
  .article-row__excerpt {
    display: none;
  }
}
</style>
