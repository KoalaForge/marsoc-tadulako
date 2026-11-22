<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../layouts/SiteLayout.vue'
import { localized } from '../lib/localized'
import EmptyState from '../components/EmptyState.vue'
import PageHero from '../components/PageHero.vue'
import WaypointDiamond from '../components/WaypointDiamond.vue'
import DisclaimerStrip from '../components/DisclaimerStrip.vue'
import logoColor from '../../images/logo/MARSOC_TADULAKO_logo_color.svg'
import logoGoldBlack from '../../images/logo/MARSOC_TADULAKO_logo_gold_on_black.svg'
import logoWhiteBlack from '../../images/logo/MARSOC_TADULAKO_logo_white_on_black.svg'

defineOptions({ layout: SiteLayout })

const { t, locale } = useI18n()

const props = defineProps({
  blocks: { type: Object, default: () => ({}) },
})

const L = (field) => localized(field, locale.value)
const items = (key) => props.blocks[key]?.items ?? []

const logoSrcs = {
  logo_color: logoColor,
  logo_gold_on_black: logoGoldBlack,
  logo_white_on_black: logoWhiteBlack,
}
const logoModes = computed(() => items('logo-modes').map((m) => ({ ...m, src: logoSrcs[m.value] })))
</script>

<template>
  <div>
    <PageHero page="about" :block="blocks.hero" />

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">01</span>
        <h2 class="manual-section__title">{{ L(blocks.story?.heading) }}</h2>
      </div>
      <p v-if="L(blocks.story?.body)" class="section-lede">{{ L(blocks.story?.body) }}</p>
      <EmptyState v-if="!L(blocks.story?.body)" compact :message="t('empty.list')" />

    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">02</span>
        <h2 class="manual-section__title">{{ L(blocks['mission-vision']?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('mission-vision').length" compact :message="t('empty.list')" />
      <div v-else class="mv-grid">
        <div v-for="item in items('mission-vision')" :key="item.key">
          <h3 class="mv-grid__label mono-data">{{ L(item.label) }}</h3>
          <p>{{ L(item.body) }}</p>
        </div>
      </div>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">03</span>
        <h2 class="manual-section__title">{{ L(blocks.values?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('values').length" compact :message="t('empty.list')" />
      <ul v-else class="values-list">
        <li v-for="value in items('values')" :key="value.key" class="values-list__item">
          <WaypointDiamond :size="10" tone="gold" />
          <div>
            <p class="values-list__name">{{ value.label }}</p>
            <p class="values-list__desc">{{ L(value.body) }}</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">04</span>
        <h2 class="manual-section__title">{{ L(blocks.identity?.heading) }}</h2>
      </div>
      <p v-if="L(blocks.identity?.body)" class="section-lede">{{ L(blocks.identity?.body) }}</p>
      <div v-if="logoModes.length" class="logo-modes">
        <div v-for="mode in logoModes" :key="mode.key" class="logo-modes__card">
          <img :src="mode.src" :alt="`${mode.label} logo`" width="96" height="96" />
          <p class="logo-modes__label mono-data">{{ mode.label }}</p>
          <p class="logo-modes__use">{{ L(mode.body) }}</p>
        </div>
      </div>
      <p v-if="L(blocks.identity?.footnote)" class="logo-modes__rule">{{ L(blocks.identity?.footnote) }}</p>
    </section>

    <section class="manual-section manual-section--disclaimer">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">05</span>
        <h2 class="manual-section__title">{{ t('about.disclaimerTitle') }}</h2>
      </div>
      <DisclaimerStrip />
    </section>
  </div>
</template>

<style scoped>
.mv-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-5);
}

.mv-grid__label {
  font-size: 0.75rem;
  letter-spacing: 0.1em;
  color: var(--ink-gold-tint);
  margin-bottom: 0.5rem;
}

.mv-grid p {
  color: var(--ink-on-dark-muted);
  max-width: 48ch;
}

.values-list {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-4) var(--space-5);
}

.values-list__item {
  display: flex;
  gap: var(--space-2);
  align-items: flex-start;
}

.values-list__item :deep(.waypoint) {
  margin-top: 0.4rem;
}

.values-list__name {
  font-family: var(--font-display);
  font-size: 1.15rem;
}

.values-list__desc {
  color: var(--ink-on-dark-muted);
  font-size: 0.9rem;
  max-width: 42ch;
}

.logo-modes {
  margin-top: var(--space-5);
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-4);
}

.logo-modes__card {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.4rem;
  padding: var(--space-4);
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
}

.logo-modes__card img {
  background: var(--ground-950);
  padding: var(--space-2);
  border-radius: var(--radius-sm);
}

.logo-modes__label {
  font-size: 0.75rem;
  letter-spacing: 0.06em;
  color: var(--ink-gold-tint);
}

.logo-modes__use {
  font-size: 0.85rem;
  color: var(--ink-on-dark-muted);
}

.logo-modes__rule {
  margin-top: var(--space-4);
  font-size: 0.85rem;
  color: var(--ink-on-dark-muted);
  max-width: var(--measure);
}

.manual-section--disclaimer {
  padding-bottom: var(--space-7);
}

@media (max-width: 760px) {
  .mv-grid,
  .values-list,
  .logo-modes {
    grid-template-columns: 1fr;
  }
}
</style>
