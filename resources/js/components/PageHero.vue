<script setup>
import { useI18n } from 'vue-i18n'
import GridField from './GridField.vue'
import { localized } from '../lib/localized'

// CMS-driven hero (page_blocks "{page}.hero"). Falls back to the lang
// chrome keys when the block is missing so a deleted block never blanks
// the page header.
const props = defineProps({
  block: { type: Object, default: null },
  page: { type: String, required: true },
})

const { t, locale } = useI18n()

const pick = (field, key) => localized(props.block?.[field], locale.value) || t(`${props.page}.${key}`)
</script>

<template>
  <section class="page-hero">
    <GridField animate density="sparse" />
    <div class="page-hero__inner">
      <h1 class="page-hero__title">{{ pick('heading', 'pageTitle') }}</h1>
      <p class="page-hero__kicker mono-data">{{ pick('eyebrow', 'pageKicker') }}</p>
      <p class="page-hero__lede">{{ pick('subheading', 'pageLede') }}</p>
    </div>
  </section>
</template>
