<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../../layouts/SiteLayout.vue'
import EmptyState from '../../components/EmptyState.vue'
import PageHero from '../../components/PageHero.vue'
import WaypointDiamond from '../../components/WaypointDiamond.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'
import logoGold from '../../../images/logo/MARSOC_TADULAKO_logo_gold_transparent.svg'

defineOptions({ layout: SiteLayout })

defineProps({
  blocks: { type: Object, default: () => ({}) },
  members: { type: Array, default: () => [] },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)
</script>

<template>
  <div>
    <PageHero page="personnel" :block="blocks.hero" />

    <section class="page-section page-section--wide">
      <EmptyState v-if="!members.length" :message="t('empty.personnel')" />
      <div v-else class="roster">
        <Link
          v-for="member in members"
          :key="member.callsign"
          :href="withLocale(localePrefix, `/personnel/${member.callsign.toLowerCase()}`)"
          class="record"
        >
          <img :src="logoGold" alt="" class="record__watermark" aria-hidden="true" />
          <div class="record__top" :class="{ 'record__top--photo': member.portrait }">
            <!-- Only rendered with the member's consent AND an uploaded photo. -->
            <img v-if="member.portrait" :src="member.portrait" :alt="member.callsign" class="record__portrait" loading="lazy" />
            <div class="record__id">
              <div class="record__head">
                <span class="record__callsign mono-data">{{ member.callsign }}</span>
                <span class="record__status" :class="`record__status--${member.statusKey}`">
                  {{ localized(member.status, locale) }}
                </span>
              </div>
              <p class="record__role">{{ localized(member.role, locale) }}</p>
            </div>
          </div>
          <dl class="record__fields">
            <div>
              <dt>{{ t('fields.memberId') }}</dt>
              <dd class="mono-data">{{ member.memberId }}</dd>
            </div>
            <div>
              <dt>{{ t('fields.impression') }}</dt>
              <dd>{{ localized(member.impressionFocus, locale) }}</dd>
            </div>
            <div>
              <dt>{{ t('fields.primary') }}</dt>
              <dd>{{ member.primaryPlatform }}</dd>
            </div>
            <div>
              <dt>{{ t('fields.joined') }}</dt>
              <dd class="mono-data">{{ member.joinDate }}</dd>
            </div>
          </dl>
          <div class="record__foot mono-data">
            <WaypointDiamond :size="8" tone="gold" />
            {{ member.grid }}
          </div>
        </Link>
      </div>
    </section>
  </div>
</template>

<style scoped>
.roster {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: var(--space-4);
}

.record {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
  padding: var(--space-4);
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  text-decoration: none;
  color: inherit;
  overflow: hidden;
  transition: transform 0.2s ease, border-color 0.2s ease;
}

.record:hover {
  transform: translateY(-3px);
  border-color: var(--gold);
}

.record__watermark {
  position: absolute;
  top: -20px;
  right: -20px;
  width: 110px;
  height: 110px;
  opacity: 0.08;
  pointer-events: none;
}

/* Same vertical rhythm as before the wrapper existed (the card's flex gap). */
.record__id {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
  min-width: 0;
}

.record__top--photo {
  display: grid;
  grid-template-columns: 4.25rem minmax(0, 1fr);
  gap: var(--space-3);
  align-items: start;
}

.record__portrait {
  width: 4.25rem;
  aspect-ratio: 3 / 4;
  object-fit: cover;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  filter: grayscale(0.15) contrast(1.05);
  display: block;
}

.record__head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.35rem 0.5rem;
}

.record__callsign {
  font-size: 1.15rem;
  letter-spacing: 0.04em;
}

.record__status {
  font-size: 0.65rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  padding: 0.2rem 0.5rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  color: var(--ink-on-dark-muted);
}

.record__status--active {
  border-color: var(--gold);
  color: var(--gold);
}

.record__status--probationary {
  border-color: var(--red);
  color: #e78b92;
}

.record__role {
  font-family: var(--font-display);
  font-size: 1.1rem;
}

.record__fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-2) var(--space-3);
  margin: 0;
}

.record__fields dt {
  font-size: 0.65rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: var(--ink-gold-tint);
}

.record__fields dd {
  margin: 0;
  font-size: 0.85rem;
  color: var(--ink-on-dark-muted);
}

.record__foot {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.72rem;
  color: var(--ink-on-dark-muted);
  padding-top: var(--space-2);
  border-top: 1px dashed var(--ground-line);
}
</style>
