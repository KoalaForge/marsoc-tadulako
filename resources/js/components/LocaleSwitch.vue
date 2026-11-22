<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

const page = usePage()
const { t } = useI18n()

const locale = computed(() => page.props.locale)
const localeUrls = computed(() => page.props.localeUrls)
</script>

<template>
  <div class="locale-switch" role="group" :aria-label="t('langSwitch.ariaLabel')">
    <a
      :href="localeUrls.en"
      class="locale-switch__btn"
      :class="{ 'locale-switch__btn--active': locale === 'en' }"
      :aria-pressed="locale === 'en'"
    >
      {{ t('langSwitch.en') }}
    </a>
    <span class="locale-switch__sep" aria-hidden="true">/</span>
    <a
      :href="localeUrls.id"
      class="locale-switch__btn"
      :class="{ 'locale-switch__btn--active': locale === 'id' }"
      :aria-pressed="locale === 'id'"
    >
      {{ t('langSwitch.id') }}
    </a>
  </div>
</template>

<style scoped>
.locale-switch {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  font-family: var(--font-mono);
  font-size: 0.72rem;
  letter-spacing: 0.04em;
}

.locale-switch__btn {
  background: none;
  border: none;
  padding: 0.2rem 0.3rem;
  color: var(--ink-on-dark-muted);
  cursor: pointer;
  text-decoration: none;
}

.locale-switch__btn--active {
  color: var(--gold);
}

.locale-switch__btn:hover {
  color: var(--white);
}

.locale-switch__sep {
  color: var(--ground-line);
}
</style>
