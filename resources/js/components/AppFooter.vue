<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { withLocale } from '../lib/routes'
import DisclaimerStrip from './DisclaimerStrip.vue'
import logo from '../../images/logo/MARSOC_TADULAKO_logo_gold_on_black.svg'

const page = usePage()
const { t } = useI18n()
const locale = computed(() => page.props.locale)
const site = computed(() => page.props.site ?? {})
</script>

<template>
  <footer class="footer">
    <div class="footer__top">
      <div class="footer__brand">
        <img :src="logo" alt="MARSOC TADULAKO — M.R.T" class="footer__logo" width="72" height="72" />
        <div>
          <p class="footer__name">MARSOC TADULAKO</p>
          <p class="footer__tagline mono-data">Tadulako Roots. Raider Spirit.</p>
        </div>
      </div>

      <nav class="footer__cols">
        <div class="footer__col">
          <span class="footer__col-title">{{ t('footer.communityTitle') }}</span>
          <Link :href="withLocale(locale, '/about')">{{ t('nav.about') }}</Link>
          <Link :href="withLocale(locale, '/personnel')">{{ t('nav.personnel') }}</Link>
          <Link :href="withLocale(locale, '/join')">{{ t('footer.joinUs') }}</Link>
        </div>
        <div class="footer__col">
          <span class="footer__col-title">{{ t('footer.referenceTitle') }}</span>
          <Link :href="withLocale(locale, '/impression')">{{ t('footer.impressionCenter') }}</Link>
          <Link :href="withLocale(locale, '/activities')">{{ t('nav.activities') }}</Link>
          <Link :href="withLocale(locale, '/publications')">{{ t('nav.publications') }}</Link>
          <Link :href="withLocale(locale, '/media')">{{ t('nav.media') }}</Link>
        </div>
        <div class="footer__col">
          <span class="footer__col-title">{{ t('footer.contactTitle') }}</span>
          <a v-if="site.whatsappBase" :href="site.whatsappBase" target="_blank" rel="noopener">{{ t('footer.whatsapp') }}</a>
        </div>
      </nav>
    </div>

    <div class="footer__bottom">
      <DisclaimerStrip compact />
      <p class="footer__copyright mono-data">{{ t('footer.copyright', { year: site.copyrightYears }) }}</p>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: var(--ground-950);
  border-top: 1px solid var(--ground-line);
  padding: var(--space-7) var(--space-4) var(--space-5);
}

.footer__top {
  max-width: 1360px;
  margin: 0 auto;
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-6);
  justify-content: space-between;
  padding-bottom: var(--space-6);
}

.footer__brand {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  max-width: 320px;
}

.footer__name {
  font-family: var(--font-display);
  font-size: 1.1rem;
  letter-spacing: 0.04em;
  margin: 0 0 0.25rem;
}

.footer__tagline {
  font-size: 0.7rem;
  color: var(--ink-gold-tint);
}

.footer__cols {
  display: flex;
  flex-wrap: wrap;
  gap: var(--space-6);
}

.footer__col {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  min-width: 140px;
}

.footer__col-title {
  font-family: var(--font-mono);
  font-size: 0.68rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--ink-gold-tint);
  margin-bottom: 0.2rem;
}

.footer__col a {
  text-decoration: none;
  color: var(--ink-on-dark-muted);
  font-size: 0.85rem;
  transition: color 0.2s ease;
}

.footer__col a:hover {
  color: var(--white);
}

.footer__bottom {
  max-width: 1360px;
  margin: 0 auto;
  padding-top: var(--space-5);
  border-top: 1px solid var(--ground-900);
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: var(--space-3);
}

.footer__copyright {
  font-size: 0.7rem;
  color: var(--ink-on-dark-muted);
  white-space: nowrap;
}
</style>
