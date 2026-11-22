<script setup>
import { computed, onMounted, ref } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import SiteLayout from '../layouts/SiteLayout.vue'
import GridField from '../components/GridField.vue'
import EmptyState from '../components/EmptyState.vue'
import WaypointDiamond from '../components/WaypointDiamond.vue'
import CoordinateReadout from '../components/CoordinateReadout.vue'
import DisclaimerStrip from '../components/DisclaimerStrip.vue'
import { withLocale } from '../lib/routes'
import { localized } from '../lib/localized'
import logoColor from '../../images/logo/MARSOC_TADULAKO_logo_color.svg'

defineOptions({ layout: SiteLayout })

const props = defineProps({
  blocks: { type: Object, default: () => ({}) },
  latestActivities: { type: Array, default: () => [] },
  featuredMedia: { type: Array, default: () => [] },
  teaserImage: { type: String, default: '' },
})

gsap.registerPlugin(ScrollTrigger)
// Sections with no content are not rendered (empty-data states), so some
// animation targets legitimately don't exist.
gsap.config({ nullTargetWarn: false })

const page = usePage()
const { t, locale } = useI18n()

const localePrefix = computed(() => page.props.locale)

// Admin-editable via /admin — Konten Halaman → Beranda. Falls back to an
// empty block shape so the page still renders before the CMS is seeded.
const identityFormula = computed(() => props.blocks.formula?.items ?? [])
const standards = computed(() => props.blocks.standards?.items ?? [])
const creedLines = computed(() => props.blocks.creed?.items ?? [])
const B = (key, field) => localized(props.blocks[key]?.[field], locale.value)
const hasWhatsApp = computed(() => Boolean(page.props.site?.whatsappBase))

const heroRoot = ref(null)
const titleMain = ref(null)

function typeInto(el, text, delay = 0) {
  if (!el) return
  el.textContent = ''
  const tl = gsap.timeline({ delay })
  text.split('').forEach((char, i) => {
    tl.call(() => { el.textContent = text.slice(0, i + 1) }, null, i * 0.045)
  })
  return tl
}

onMounted(() => {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
  const fullTitle = 'MARSOC TADULAKO'

  if (!reduced) {
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } })
    tl.to('.hero__waypoint', { opacity: 1, y: 0, duration: 0.5, ease: 'back.out(2)' }, 0.1)
      .to('.hero__logo-wrap', { opacity: 1, scale: 1, duration: 0.7, ease: 'back.out(1.6)' }, 0.3)
      .call(() => typeInto(titleMain.value, fullTitle), null, 0.6)
      .to('.hero__title-sub', { opacity: 1, y: 0, duration: 0.5 }, 1.35)
      .to('.hero__tagline', { opacity: 1, y: 0, duration: 0.7 }, 1.55)
      .to('.hero__lede', { opacity: 1, y: 0, duration: 0.7 }, 1.75)
      .to('.hero__cta', { opacity: 1, y: 0, duration: 0.6 }, 1.95)
      .to('.hero__scroll-cue', { opacity: 1, duration: 0.6 }, 2.2)
  } else {
    if (titleMain.value) titleMain.value.textContent = fullTitle
    gsap.set(
      ['.hero__waypoint', '.hero__logo-wrap', '.hero__title-sub', '.hero__tagline', '.hero__lede', '.hero__cta', '.hero__scroll-cue'],
      { opacity: 1, y: 0, scale: 1 },
    )
  }

  if (reduced) {
    gsap.set(
      [
        '.formula__item',
        '.formula__closing',
        '.standards__item',
        '.creed__line',
        '.creed__quote',
        '.activity-card',
        '.impression-teaser__copy > *',
        '.media-strip__item',
        '.final-cta > *',
      ],
      { opacity: 1, y: 0, x: 0, scale: 1 },
    )
    gsap.set('.impression-teaser__media img', { clipPath: 'inset(0 0% 0 0)' })
    return
  }

  // CHECKPOINT 2 — identity formula: each pillar steps in left-to-right, like waypoints logged in sequence.
  gsap.fromTo(
    '.formula__item',
    { opacity: 0, x: -24 },
    {
      opacity: 1,
      x: 0,
      duration: 0.6,
      ease: 'power3.out',
      stagger: 0.12,
      scrollTrigger: { trigger: '.formula', start: 'top 80%' },
    },
  )
  gsap.fromTo(
    '.formula__closing',
    { opacity: 0 },
    { opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.formula__closing', start: 'top 90%' } },
  )

  // CHECKPOINT 3 — five standards: roll-call, one row at a time.
  gsap.fromTo(
    '.standards__item',
    { opacity: 0, x: -16 },
    {
      opacity: 1,
      x: 0,
      duration: 0.5,
      ease: 'power2.out',
      stagger: 0.1,
      scrollTrigger: { trigger: '.standards__list', start: 'top 82%' },
    },
  )

  // CHECKPOINT 4 — creed: each line builds in on its own, echoing the intensity-type raise.
  gsap.fromTo(
    '.creed__line',
    { opacity: 0, y: 14 },
    {
      opacity: 1,
      y: 0,
      duration: 0.5,
      ease: 'power2.out',
      stagger: 0.18,
      scrollTrigger: { trigger: '.creed__inner', start: 'top 75%' },
    },
  )
  gsap.fromTo(
    '.creed__quote',
    { opacity: 0 },
    { opacity: 1, duration: 1, scrollTrigger: { trigger: '.creed__quote', start: 'top 95%' } },
  )

  // CHECKPOINT 5 — latest activities: cards settle in with a slight scale, like photos dropped on a table.
  gsap.fromTo(
    '.activity-card',
    { opacity: 0, scale: 0.94, y: 20 },
    {
      opacity: 1,
      scale: 1,
      y: 0,
      duration: 0.6,
      ease: 'power3.out',
      stagger: 0.1,
      scrollTrigger: { trigger: '.teaser__activities', start: 'top 82%' },
    },
  )

  // CHECKPOINT 6 — impression teaser: photo wipes open, copy follows.
  gsap.fromTo(
    '.impression-teaser__media img',
    { clipPath: 'inset(0 100% 0 0)' },
    {
      clipPath: 'inset(0 0% 0 0)',
      duration: 0.9,
      ease: 'power3.inOut',
      scrollTrigger: { trigger: '.impression-teaser', start: 'top 78%' },
    },
  )
  gsap.fromTo(
    '.impression-teaser__copy > *',
    { opacity: 0, y: 18 },
    {
      opacity: 1,
      y: 0,
      duration: 0.6,
      stagger: 0.1,
      ease: 'power3.out',
      scrollTrigger: { trigger: '.impression-teaser', start: 'top 78%' },
    },
  )

  // CHECKPOINT 7 — media strip: tiles reveal left-to-right like a contact sheet.
  gsap.fromTo(
    '.media-strip__item',
    { opacity: 0, y: 16 },
    {
      opacity: 1,
      y: 0,
      duration: 0.5,
      ease: 'power2.out',
      stagger: 0.08,
      scrollTrigger: { trigger: '.media-strip', start: 'top 85%' },
    },
  )

  // CHECKPOINT 8 — final CTA: the close, a single deliberate scale-up.
  gsap.fromTo(
    '.final-cta > *',
    { opacity: 0, scale: 0.96 },
    {
      opacity: 1,
      scale: 1,
      duration: 0.7,
      ease: 'power3.out',
      stagger: 0.08,
      scrollTrigger: { trigger: '.final-cta', start: 'top 80%' },
    },
  )
})
</script>

<template>
  <div class="home">
    <!-- CHECKPOINT 1: mission briefing / map hero -->
    <section ref="heroRoot" class="hero">
      <GridField animate density="normal" />
      <div class="hero__vignette" />

      <div class="hero__content">
        <div class="hero__logo-wrap">
          <span class="hero__compass-ring" aria-hidden="true" />
          <img :src="logoColor" alt="MARSOC TADULAKO insignia" class="hero__logo" width="120" height="120" />
        </div>
        <h1 class="hero__title">
          <span class="visually-hidden">MARSOC TADULAKO — M.R.T, Marine Raider Tadulako</span>
          <span aria-hidden="true">
            <span ref="titleMain" class="hero__title-main"></span>
            <span class="hero__title-sub">{{ t('home.heroTitleSub') }}</span>
          </span>
        </h1>
        <p class="hero__tagline">{{ t('home.heroTagline') }}</p>
        <p class="hero__lede">{{ localized(props.blocks.hero?.subheading, locale) || t('home.heroLede') }}</p>
        <div class="hero__cta">
          <a v-if="hasWhatsApp" class="btn btn--primary" :href="page.props.site?.whatsappBase" target="_blank" rel="noopener">
            {{ t('common.reportForDuty') }}
          </a>
          <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/about')">{{ t('common.readOurIdentity') }}</Link>
        </div>
      </div>

      <div class="hero__waypoint">
        <WaypointDiamond :size="10" tone="red" pulse />
        <CoordinateReadout text="00.89°S 119.87°E — HOME GRID" animate :delay="2" />
      </div>

      <div class="hero__scroll-cue mono-data">{{ t('home.scrollCue') }}</div>
    </section>

    <!-- CHECKPOINT 2: identity formula -->
    <section v-if="identityFormula.length" class="formula">
      <h2 class="section-title">{{ localized(props.blocks.formula?.heading, locale) }}</h2>
      <div class="formula__row">
        <template v-for="(item, i) in identityFormula" :key="item.key">
          <div class="formula__item">
            <WaypointDiamond :size="10" tone="gold" />
            <p class="formula__label mono-data">{{ item.label }}</p>
            <p class="formula__value">{{ localized(item.meta, locale) }}</p>
            <p class="formula__desc">{{ localized(item.body, locale) }}</p>
          </div>
          <div v-if="i < identityFormula.length - 1" class="formula__connector" aria-hidden="true" />
        </template>
      </div>
      <p v-if="B('formula', 'footnote')" class="formula__closing">{{ localized(props.blocks.formula?.footnote, locale) }}</p>
    </section>

    <!-- CHECKPOINT 3: five standards -->
    <section v-if="standards.length" class="standards">
      <h2 class="section-title">{{ localized(props.blocks.standards?.heading, locale) }}</h2>
      <p class="section-lede">{{ localized(props.blocks.standards?.subheading, locale) }}</p>
      <ol class="standards__list">
        <li v-for="s in standards" :key="s.key" class="standards__item">
          <span class="standards__n mono-data">{{ s.value }}</span>
          <WaypointDiamond :size="12" tone="red" />
          <span class="standards__name">{{ s.label }}</span>
          <span class="standards__gloss">{{ localized(s.body, locale) }}</span>
        </li>
      </ol>
    </section>

    <!-- CHECKPOINT 4: creed -->
    <section v-if="creedLines.length" class="creed">
      <GridField density="sparse" />
      <div class="creed__inner">
        <p
          v-for="line in creedLines"
          :key="line.key"
          class="creed__line"
          :class="`creed__line--${line.value}`"
        >
          {{ line.label }}
        </p>
        <p v-if="B('creed', 'footnote')" class="creed__quote">{{ localized(props.blocks.creed?.footnote, locale) }}</p>
      </div>
    </section>

    <!-- CHECKPOINT 5: latest activities -->
    <section class="teaser">
      <div class="teaser__head">
        <h2 class="section-title">{{ B('latest-activities', 'heading') || t('home.latestActivitiesTitle') }}</h2>
        <Link class="teaser__all" :href="withLocale(localePrefix, '/activities')">{{ t('home.allActivities') }}</Link>
      </div>
      <p v-if="localized(props.blocks['latest-activities']?.footnote, locale)" class="teaser__synthetic">{{ localized(props.blocks['latest-activities']?.footnote, locale) }}</p>
      <EmptyState v-if="!latestActivities.length" :message="t('empty.activities')" />
      <div v-else class="teaser__activities">
        <Link
          v-for="item in latestActivities"
          :key="item.slug"
          :href="withLocale(localePrefix, `/activities/${item.slug}`)"
          class="activity-card"
        >
          <img v-if="item.thumb" :src="item.thumb" :alt="localized(item.title, locale)" class="activity-card__thumb" loading="lazy" />
          <h3 class="activity-card__title">{{ localized(item.title, locale) }}</h3>
          <p class="activity-card__meta mono-data">
            {{ [localized(item.category, locale), item.date, localized(item.location, locale)].filter(Boolean).join(' · ') }}
          </p>
          <p class="activity-card__overview">{{ localized(item.overview, locale) }}</p>
        </Link>
      </div>
    </section>

    <!-- CHECKPOINT 6: featured impression -->
    <section class="impression-teaser" :class="{ 'impression-teaser--text-only': !teaserImage }">
      <div v-if="teaserImage" class="impression-teaser__media">
        <img :src="teaserImage" :alt="B('impression-teaser', 'heading') || t('home.impressionTitle')" loading="lazy" />
      </div>
      <div class="impression-teaser__copy">
        <h2 class="section-title">{{ B('impression-teaser', 'heading') || t('home.impressionTitle') }}</h2>
        <p class="section-lede">{{ B('impression-teaser', 'subheading') || t('home.impressionLede') }}</p>
        <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/impression')">{{ t('home.openReferenceCenter') }}</Link>
      </div>
    </section>

    <!-- CHECKPOINT 7: media archive teaser -->
    <section class="teaser">
      <div class="teaser__head">
        <h2 class="section-title">{{ B('latest-media', 'heading') || t('home.latestDocTitle') }}</h2>
        <Link class="teaser__all" :href="withLocale(localePrefix, '/media')">{{ t('home.mediaArchiveLink') }}</Link>
      </div>
      <EmptyState v-if="!featuredMedia.length" :message="t('empty.media')" />
      <div v-else class="media-strip">
        <Link
          v-for="item in featuredMedia"
          :key="item.id"
          :href="withLocale(localePrefix, `/media/${item.id}`)"
          class="media-strip__item"
        >
          <img :src="item.photos[0]" :alt="localized(item.title, locale)" />
          <span class="media-strip__caption mono-data">{{ localized(item.title, locale) }}</span>
        </Link>
      </div>
    </section>

    <!-- CHECKPOINT 8: final CTA -->
    <section class="final-cta">
      <h2 class="final-cta__title">{{ B('final-cta', 'heading') || t('home.finalCtaTitle') }}</h2>
      <p class="final-cta__lede">{{ B('final-cta', 'subheading') || t('home.finalCtaLede') }}</p>
      <div class="hero__cta">
        <a v-if="hasWhatsApp" class="btn btn--primary" :href="page.props.site?.whatsappBase" target="_blank" rel="noopener">
          {{ t('common.reportForDuty') }}
        </a>
        <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/join')">{{ t('home.membershipProcess') }}</Link>
      </div>
      <DisclaimerStrip />
    </section>
  </div>
</template>

<style scoped>
.home {
  overflow-x: clip;
}

/* ---------- Hero ---------- */
.hero {
  position: relative;
  min-height: 100svh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: var(--space-6) var(--space-4);
  background: radial-gradient(ellipse at 50% 20%, var(--ground-800), var(--ground-950) 70%);
  overflow: hidden;
}

.hero__vignette {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(4, 13, 26, 0.15) 0%, rgba(4, 13, 26, 0.3) 55%, rgba(4, 13, 26, 0.55) 100%);
  pointer-events: none;
}

.hero__waypoint {
  position: absolute;
  top: var(--space-5);
  left: var(--space-4);
  display: flex;
  align-items: center;
  gap: var(--space-2);
  color: var(--gold);
  z-index: 1;
  opacity: 0;
  transform: translateY(-18px);
}

.hero__content {
  position: relative;
  z-index: 1;
  text-align: center;
  max-width: 720px;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: var(--space-6);
}

.hero__content p,
.hero__content h1 {
  text-shadow: 0 3px 18px rgba(2, 8, 18, 0.8);
}

.hero__logo-wrap {
  position: relative;
  width: clamp(88px, 10vw, 120px);
  height: clamp(88px, 10vw, 120px);
  margin-bottom: var(--space-4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transform: scale(0.8);
}

.hero__compass-ring {
  position: absolute;
  inset: -34px;
  border-radius: 50%;
  background: repeating-conic-gradient(
    from 0deg,
    var(--grid-line-strong) 0deg 1.2deg,
    transparent 1.2deg 22.5deg,
    var(--gold) 22.5deg 23.2deg,
    transparent 23.2deg 45deg
  );
  -webkit-mask: radial-gradient(circle, transparent 66%, black 67%, black 72%, transparent 73%);
  mask: radial-gradient(circle, transparent 66%, black 67%, black 72%, transparent 73%);
  animation: compass-spin 90s linear infinite;
  pointer-events: none;
}

@keyframes compass-spin {
  to {
    transform: rotate(360deg);
  }
}

@media (prefers-reduced-motion: reduce) {
  .hero__compass-ring {
    animation: none;
  }
}

.hero__logo {
  width: 100%;
  height: auto;
  position: relative;
  filter: drop-shadow(0 12px 28px rgba(0, 0, 0, 0.45));
}

.hero__title {
  font-size: clamp(2.4rem, 6vw, 4.6rem);
  line-height: 1.02;
  letter-spacing: 0.01em;
  text-shadow: 0 4px 28px rgba(2, 8, 18, 0.85);
}

.hero__title-main {
  display: inline-block;
  min-height: 1em;
}

.hero__title-sub {
  display: block;
  font-family: var(--font-body);
  font-size: clamp(0.95rem, 1.6vw, 1.25rem);
  font-weight: 600;
  letter-spacing: 0.04em;
  color: var(--ink-gold-tint);
  margin-top: var(--space-2);
  opacity: 0;
  transform: translateY(12px);
}

.hero__tagline {
  font-family: var(--font-display);
  font-size: clamp(1.1rem, 2vw, 1.5rem);
  color: var(--white);
  margin-top: var(--space-4);
  opacity: 0;
  transform: translateY(16px);
}

.hero__lede {
  max-width: 56ch;
  margin-top: var(--space-3);
  color: var(--ink-on-dark-muted);
  opacity: 0;
  transform: translateY(16px);
}

.hero__cta {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-3);
  justify-content: center;
  margin-top: var(--space-5);
  opacity: 0;
  transform: translateY(16px);
}

.hero__scroll-cue {
  position: absolute;
  bottom: var(--space-4);
  left: 50%;
  transform: translateX(-50%);
  font-size: 0.65rem;
  letter-spacing: 0.16em;
  color: var(--ink-on-dark-muted);
  opacity: 0;
}

/* ---------- Buttons ---------- */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.85rem 1.6rem;
  text-decoration: none;
  font-family: var(--font-mono);
  font-size: 0.82rem;
  letter-spacing: 0.03em;
  border-radius: var(--radius-sm);
  transition: transform 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
}

.btn--primary {
  background: var(--red);
  color: var(--white);
}

.btn--primary:hover {
  background: var(--action-hover);
  transform: translateY(-2px);
}

.btn--ghost {
  border: 1px solid var(--ground-line);
  color: var(--ink-on-dark);
}

.btn--ghost:hover {
  border-color: var(--gold);
  color: var(--gold);
  transform: translateY(-2px);
}

/* ---------- Shared section rhythm ---------- */
section {
  padding: var(--space-7) var(--space-4);
  max-width: 1200px;
  margin: 0 auto;
}

.section-title {
  font-size: clamp(1.8rem, 3vw, 2.6rem);
}

.section-lede {
  max-width: var(--measure);
  color: var(--ink-on-dark-muted);
  margin-top: var(--space-2);
}

/* ---------- Identity formula ---------- */
.formula__row {
  display: flex;
  align-items: stretch;
  gap: var(--space-3);
  margin-top: var(--space-6);
  overflow-x: auto;
  padding-bottom: var(--space-2);
}

.formula__item {
  flex: 1 1 200px;
  min-width: 200px;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.formula__label {
  font-size: 0.72rem;
  letter-spacing: 0.1em;
  color: var(--ink-gold-tint);
  margin-top: 0.4rem;
}

.formula__value {
  font-family: var(--font-display);
  font-size: 1.6rem;
}

.formula__desc {
  color: var(--ink-on-dark-muted);
  font-size: 0.88rem;
}

.formula__connector {
  width: 1px;
  background: var(--ground-line);
  margin-top: var(--space-2);
}

.formula__closing {
  margin-top: var(--space-6);
  font-family: var(--font-display);
  font-size: 1.2rem;
  color: var(--ink-gold-tint);
  max-width: 50ch;
}

/* ---------- Standards ---------- */
.standards__list {
  list-style: none;
  margin: var(--space-6) 0 0;
  padding: 0;
  display: flex;
  flex-direction: column;
}

.standards__item {
  display: grid;
  grid-template-columns: 2.5rem auto 10rem 1fr;
  align-items: center;
  gap: var(--space-4);
  padding: var(--space-3) 0;
  border-bottom: 1px solid var(--ground-900);
}

.standards__item:first-child {
  border-top: 1px solid var(--ground-900);
}

.standards__n {
  color: var(--ground-line);
  font-size: 0.9rem;
}

.standards__name {
  font-family: var(--font-display);
  font-size: 1.4rem;
}

.standards__gloss {
  color: var(--ink-on-dark-muted);
  font-size: 0.9rem;
}

/* ---------- Creed ---------- */
.creed {
  position: relative;
  max-width: none;
  background: var(--ground-900);
  padding-top: var(--space-8);
  padding-bottom: var(--space-8);
  text-align: center;
  overflow: hidden;
}

.creed__inner {
  position: relative;
  z-index: 1;
  max-width: 820px;
  margin: 0 auto;
}

.creed__line {
  font-family: var(--font-display);
  letter-spacing: 0.02em;
  color: var(--ink-on-dark-muted);
}

.creed__line--md {
  font-size: clamp(1rem, 1.8vw, 1.3rem);
  margin-bottom: 0.3rem;
}

.creed__line--lg {
  font-size: clamp(1.6rem, 3.2vw, 2.4rem);
  color: var(--white);
  margin-top: var(--space-5);
}

.creed__line--lg:first-of-type {
  margin-top: var(--space-6);
}

.creed__line--xl {
  font-size: clamp(2.2rem, 5vw, 3.6rem);
  color: var(--gold);
  margin-top: var(--space-2);
}

.creed__quote {
  margin-top: var(--space-6);
  font-style: italic;
  color: var(--ink-gold-tint);
  font-size: 1rem;
}

/* ---------- Teasers ---------- */
.teaser__head {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  flex-wrap: wrap;
  gap: var(--space-2);
}

.teaser__all {
  text-decoration: none;
  color: var(--ink-gold-tint);
  font-family: var(--font-mono);
  font-size: 0.8rem;
}

.teaser__synthetic {
  margin-top: var(--space-2);
  font-size: 0.75rem;
  color: var(--ink-on-dark-muted);
  font-style: italic;
}

.teaser__activities {
  margin-top: var(--space-5);
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-4);
}

.activity-card {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  text-decoration: none;
  color: inherit;
  padding: var(--space-4);
  border: 1px solid var(--ground-900);
  background: var(--ground-900);
  border-radius: var(--radius-md);
  transition: border-color 0.2s ease, transform 0.2s ease;
}

.activity-card:hover {
  border-color: var(--gold);
  transform: translateY(-3px);
}

.activity-card__thumb {
  width: 100%;
  aspect-ratio: 16 / 9;
  object-fit: cover;
  border-radius: var(--radius-sm);
  margin-bottom: var(--space-2);
  display: block;
}

.activity-card__title {
  font-family: var(--font-display);
  font-size: 1.25rem;
}

.activity-card__meta {
  font-size: 0.75rem;
  color: var(--ink-on-dark-muted);
}

.activity-card__overview {
  font-size: 0.88rem;
  color: var(--ink-on-dark-muted);
}

/* ---------- Impression teaser ---------- */
.impression-teaser {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-6);
  align-items: center;
}

.impression-teaser__copy {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: var(--space-3);
}

/* No photo -> no media column at all: the copy simply takes the measure. */
.impression-teaser--text-only {
  grid-template-columns: minmax(0, var(--measure));
}

.impression-teaser__media img {
  width: 100%;
  border-radius: var(--radius-md);
  filter: grayscale(0.15) contrast(1.05);
  border: 1px solid var(--ground-line);
}

/* ---------- Media strip ---------- */
.media-strip {
  margin-top: var(--space-5);
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-3);
}

.media-strip__item {
  position: relative;
  display: block;
  aspect-ratio: 4 / 5;
  overflow: hidden;
  border-radius: var(--radius-md);
  border: 1px solid var(--ground-900);
}

.media-strip__item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s var(--ease-out-quint);
}

.media-strip__item:hover img {
  transform: scale(1.06);
}

.media-strip__caption {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  padding: var(--space-2);
  font-size: 0.65rem;
  color: var(--white);
  background: linear-gradient(0deg, rgba(4, 13, 26, 0.9), transparent);
}

/* ---------- Final CTA ---------- */
.final-cta {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-3);
}

.final-cta__title {
  font-size: clamp(1.8rem, 3.4vw, 2.6rem);
}

.final-cta__lede {
  max-width: 54ch;
  color: var(--ink-on-dark-muted);
}

@media (max-width: 860px) {
  .formula__row {
    flex-direction: column;
  }

  .formula__connector {
    display: none;
  }

  .standards__item {
    grid-template-columns: 2rem auto 1fr;
    grid-template-areas:
      'n diamond name'
      'gloss gloss gloss';
    row-gap: 0.4rem;
  }

  .standards__gloss {
    grid-area: gloss;
  }

  .teaser__activities {
    grid-template-columns: 1fr;
  }

  .impression-teaser {
    grid-template-columns: 1fr;
  }

  .media-strip {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
