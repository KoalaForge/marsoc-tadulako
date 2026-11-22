<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { withLocale } from '../lib/routes'
import WaypointDiamond from './WaypointDiamond.vue'
import LocaleSwitch from './LocaleSwitch.vue'
import logo from '../../images/logo/MARSOC_TADULAKO_logo_white_transparent.svg'

const page = usePage()
const { t } = useI18n()
const open = ref(false)
const scrolled = ref(false)

const locale = computed(() => page.props.locale)
const currentPath = computed(() => page.url.split('?')[0])

const links = computed(() => [
  { to: withLocale(locale.value, '/'), label: t('nav.home') },
  { to: withLocale(locale.value, '/about'), label: t('nav.about') },
  { to: withLocale(locale.value, '/personnel'), label: t('nav.personnel') },
  { to: withLocale(locale.value, '/impression'), label: t('nav.impression') },
  { to: withLocale(locale.value, '/activities'), label: t('nav.activities') },
  { to: withLocale(locale.value, '/media'), label: t('nav.media') },
  { to: withLocale(locale.value, '/publications'), label: t('nav.publications') },
])

function onScroll() {
  scrolled.value = window.scrollY > 24
}

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', onScroll))

function closeMenu() {
  open.value = false
}
</script>

<template>
  <header class="nav" :class="{ 'nav--scrolled': scrolled, 'nav--open': open }">
    <div class="nav__bar">
      <Link :href="withLocale(locale, '/')" class="nav__brand" @click="closeMenu">
        <img :src="logo" alt="" class="nav__logo" width="32" height="32" />
        <span class="nav__brand-text">
          <span class="nav__brand-call">M.R.T</span>
          <span class="nav__brand-sub mono-data">MARSOC TADULAKO</span>
        </span>
      </Link>

      <button
        class="nav__toggle"
        type="button"
        :aria-expanded="open"
        :aria-label="t('nav.toggle')"
        @click="open = !open"
      >
        <span />
        <span />
        <span />
      </button>

      <nav class="nav__links" :id="'primary-nav'">
        <div class="nav__links-inner">
          <Link
            v-for="link in links"
            :key="link.to"
            :href="link.to"
            class="nav__link"
            :class="{ 'nav__link--active': currentPath === link.to }"
            @click="closeMenu"
          >
            <WaypointDiamond v-if="currentPath === link.to" :size="7" tone="red" />
            {{ link.label }}
          </Link>
          <Link :href="withLocale(locale, '/join')" class="nav__cta" @click="closeMenu">{{ t('nav.cta') }}</Link>
          <LocaleSwitch class="nav__locale" />
        </div>
      </nav>
    </div>
  </header>
</template>

<style scoped>
.nav {
  position: sticky;
  top: 0;
  z-index: 50;
  background: transparent;
  transition: background-color 0.4s var(--ease-out-expo), border-color 0.4s var(--ease-out-expo);
  border-bottom: 1px solid transparent;
}

.nav--scrolled,
.nav--open {
  background: rgba(4, 13, 26, 0.92);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--ground-line);
}

.nav__bar {
  max-width: 1360px;
  margin: 0 auto;
  padding: var(--space-3) var(--space-4);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-4);
}

.nav__brand {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  text-decoration: none;
  color: var(--ink-on-dark);
}

.nav__logo {
  width: 32px;
  height: 32px;
}

.nav__brand-text {
  display: flex;
  flex-direction: column;
  line-height: 1.1;
}

.nav__brand-call {
  font-family: var(--font-display);
  font-size: 1.1rem;
  letter-spacing: 0.04em;
}

.nav__brand-sub {
  font-size: 0.6rem;
  letter-spacing: 0.12em;
  color: var(--ink-gold-tint);
}

.nav__toggle {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  padding: var(--space-1);
  cursor: pointer;
}

.nav__toggle span {
  width: 22px;
  height: 2px;
  background: var(--ink-on-dark);
}

.nav__links {
  display: flex;
  align-items: center;
  gap: var(--space-4);
}

.nav__links-inner {
  display: contents;
}

.nav__link {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  text-decoration: none;
  color: var(--ink-on-dark-muted);
  font-size: 0.8rem;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  padding: var(--space-1) 0;
  transition: color 0.2s ease;
}

.nav__link:hover,
.nav__link--active {
  color: var(--white);
}

.nav__cta {
  text-decoration: none;
  background: var(--red);
  color: var(--white);
  font-family: var(--font-mono);
  font-size: 0.75rem;
  letter-spacing: 0.04em;
  padding: 0.55rem 1rem;
  border-radius: var(--radius-sm);
  transition: background-color 0.2s ease, transform 0.2s ease;
}

.nav__cta:hover {
  background: var(--action-hover);
  transform: translateY(-1px);
}

.nav__locale {
  margin-left: var(--space-1);
}

@media (max-width: 920px) {
  .nav__toggle {
    display: flex;
  }

  .nav__links {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    display: grid;
    grid-template-rows: 0fr;
    align-items: stretch;
    background: var(--ground-950);
    border-bottom: 1px solid var(--ground-line);
    transition: grid-template-rows 0.3s var(--ease-out-expo);
  }

  .nav__links-inner {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-height: 0;
    overflow: hidden;
  }

  .nav--open .nav__links {
    grid-template-rows: 1fr;
  }

  .nav__link {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    border-bottom: 1px solid var(--ground-900);
  }

  .nav__cta {
    margin: var(--space-3) var(--space-4);
  }

  .nav__locale {
    margin: 0 var(--space-4) var(--space-4);
  }
}
</style>
