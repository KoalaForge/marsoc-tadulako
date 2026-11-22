<script setup>
import { reactive, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../layouts/SiteLayout.vue'
import { localized } from '../lib/localized'
import EmptyState from '../components/EmptyState.vue'
import PageHero from '../components/PageHero.vue'
import WaypointDiamond from '../components/WaypointDiamond.vue'

defineOptions({ layout: SiteLayout })

const page = usePage()
const { t, tm, locale } = useI18n()

const props = defineProps({
  blocks: { type: Object, default: () => ({}) },
})

const L = (field) => localized(field, locale.value)
const items = (key) => props.blocks[key]?.items ?? []

const form = reactive({
  fullName: '',
  preferredName: '',
  age: '',
  location: '',
  airsoftExperience: '',
  impressionExperience: '',
  equipmentOwnership: '',
  motivation: '',
  contact: '',
})

const isValid = computed(() => form.fullName.trim() && form.contact.trim() && form.motivation.trim())

const waMessage = computed(() => {
  const m = tm('join.waMessage')
  const lines = [
    m.heading,
    `${m.fullName}: ${form.fullName}`,
    form.preferredName ? `${m.preferredName}: ${form.preferredName}` : null,
    form.age ? `${m.age}: ${form.age}` : null,
    form.location ? `${m.location}: ${form.location}` : null,
    form.airsoftExperience ? `${m.airsoftExperience}: ${form.airsoftExperience}` : null,
    form.impressionExperience ? `${m.impressionExperience}: ${form.impressionExperience}` : null,
    form.equipmentOwnership ? `${m.equipmentOwnership}: ${form.equipmentOwnership}` : null,
    `${m.motivation}: ${form.motivation}`,
    `${m.contact}: ${form.contact}`,
  ].filter(Boolean)
  return lines.join('\n')
})

const waBase = computed(() => page.props.site?.whatsappBase ?? '')
const waLink = computed(() => `${waBase.value}?text=${encodeURIComponent(waMessage.value)}`)

function handleSubmit() {
  if (!isValid.value || !waBase.value) return
  window.open(waLink.value, '_blank', 'noopener')
}
</script>

<template>
  <div>
    <PageHero page="join" :block="blocks.hero" />

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">01</span>
        <h2 class="manual-section__title">{{ L(blocks.about?.heading) }}</h2>
      </div>
      <p v-if="L(blocks.about?.body)" class="section-lede">{{ L(blocks.about?.body) }}</p>
      <EmptyState v-if="!L(blocks.about?.body)" compact :message="t('empty.list')" />

    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">02</span>
        <h2 class="manual-section__title">{{ L(blocks.eligibility?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('eligibility').length" compact :message="t('empty.list')" />
      <ul v-else class="plain-list">
        <li v-for="item in items('eligibility')" :key="item.key">{{ L(item.body) }}</li>
      </ul>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">03</span>
        <h2 class="manual-section__title">{{ L(blocks.expectations?.heading) }}</h2>
      </div>
      <p v-if="L(blocks.expectations?.body)" class="section-lede">{{ L(blocks.expectations?.body) }}</p>
      <EmptyState v-if="!L(blocks.expectations?.body)" compact :message="t('empty.list')" />

    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">04</span>
        <h2 class="manual-section__title">{{ L(blocks.rules?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('rules').length" compact :message="t('empty.list')" />
      <ul v-else class="plain-list">
        <li v-for="rule in items('rules')" :key="rule.key">{{ L(rule.body) }}</li>
      </ul>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">05</span>
        <h2 class="manual-section__title">{{ L(blocks.steps?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('steps').length" compact :message="t('empty.list')" />
      <ol v-else class="process">
        <li v-for="step in items('steps')" :key="step.key" class="process__step">
          <span class="process__n mono-data">{{ step.value }}</span>
          <WaypointDiamond :size="8" tone="gold" />
          <div>
            <p class="process__title">{{ L(step.title) }}</p>
            <p class="process__desc">{{ L(step.body) }}</p>
          </div>
        </li>
      </ol>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">06</span>
        <h2 class="manual-section__title">{{ t('join.formTitle') }}</h2>
      </div>
      <form class="form" @submit.prevent="handleSubmit">
        <div class="form__row">
          <label>{{ t('join.form.fullName') }}<input v-model="form.fullName" type="text" required /></label>
          <label>{{ t('join.form.preferredName') }}<input v-model="form.preferredName" type="text" /></label>
        </div>
        <div class="form__row">
          <label>{{ t('join.form.age') }}<input v-model="form.age" type="number" min="15" max="99" /></label>
          <label>{{ t('join.form.location') }}<input v-model="form.location" type="text" :placeholder="t('join.form.locationPlaceholder')" /></label>
        </div>
        <label>{{ t('join.form.airsoftExperience') }}<textarea v-model="form.airsoftExperience" rows="2" /></label>
        <label>{{ t('join.form.impressionExperience') }}<textarea v-model="form.impressionExperience" rows="2" /></label>
        <label>
          {{ t('join.form.equipmentOwnership') }}
          <textarea v-model="form.equipmentOwnership" rows="2" :placeholder="t('join.form.equipmentPlaceholder')" />
        </label>
        <label>{{ t('join.form.motivation') }}<textarea v-model="form.motivation" rows="3" required /></label>
        <label>{{ t('join.form.contact') }}<input v-model="form.contact" type="text" required /></label>

        <button class="btn btn--primary form__submit" type="submit" :disabled="!isValid || !waBase">
          {{ t('join.form.submit') }}
        </button>
        <p class="form__note">{{ t('join.form.note') }}</p>
      </form>
    </section>

    <section class="manual-section manual-section--contact">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">07</span>
        <h2 class="manual-section__title">{{ t('join.contactTitle') }}</h2>
      </div>
      <a v-if="waBase" class="btn btn--ghost" :href="waBase" target="_blank" rel="noopener">
        {{ waBase.replace('https://', '') }} →
      </a>
    </section>
  </div>
</template>

<style scoped>
.plain-list {
  margin: 0;
  padding-left: 1.1rem;
  color: var(--ink-on-dark-muted);
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  max-width: var(--measure);
}

.process {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.process__step {
  display: flex;
  align-items: flex-start;
  gap: var(--space-3);
}

.process__n {
  color: var(--ground-line);
  font-size: 0.85rem;
  min-width: 1.6rem;
}

.process__title {
  font-family: var(--font-display);
  font-size: 1.1rem;
}

.process__desc {
  color: var(--ink-on-dark-muted);
  font-size: 0.88rem;
}

.form {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
  max-width: 620px;
}

.form__row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-3);
}

label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  font-size: 0.8rem;
  color: var(--ink-gold-tint);
}

input,
textarea {
  font-family: var(--font-body);
  font-size: 0.9rem;
  color: var(--ink-on-dark);
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-sm);
  padding: 0.65rem 0.8rem;
  resize: vertical;
}

input:focus,
textarea:focus {
  outline: 2px solid var(--gold);
  outline-offset: 1px;
  border-color: var(--gold);
}

.form__submit {
  align-self: flex-start;
  margin-top: var(--space-2);
}

.form__note {
  font-size: 0.75rem;
  color: var(--ink-on-dark-muted);
}

.manual-section--contact {
  padding-bottom: var(--space-7);
}

@media (max-width: 600px) {
  .form__row {
    grid-template-columns: 1fr;
  }
}
</style>
