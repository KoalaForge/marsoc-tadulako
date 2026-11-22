<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { localized } from '../lib/localized'

defineProps({
  compact: { type: Boolean, default: false },
})

// Admin-editable via SiteSettings.disclaimer ({strong} marks the bolded
// community name). Falls back to the lang copy if settings are empty.
const page = usePage()
const { t, locale } = useI18n()

const parts = computed(() => {
  const text = localized(page.props.site?.disclaimer, locale.value)
  if (!text) return null
  return text.split('{strong}')
})
</script>

<template>
  <p class="disclaimer" :class="{ 'disclaimer--compact': compact }">
    <template v-if="parts">
      <template v-for="(part, i) in parts" :key="i">
        <strong v-if="i > 0">{{ t('disclaimer.strong') }}</strong>{{ part }}
      </template>
    </template>
    <i18n-t v-else keypath="disclaimer.full" tag="span" scope="global">
      <template #strong><strong>{{ t('disclaimer.strong') }}</strong></template>
    </i18n-t>
  </p>
</template>

<style scoped>
.disclaimer {
  max-width: var(--measure);
  font-size: 0.8125rem;
  line-height: 1.6;
  color: var(--ink-on-dark-muted);
}

.disclaimer strong {
  color: var(--ink-gold-tint);
  font-weight: 700;
}

.disclaimer--compact {
  font-size: 0.75rem;
  max-width: 60ch;
}
</style>
