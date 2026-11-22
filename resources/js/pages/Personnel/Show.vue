<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../../layouts/SiteLayout.vue'
import WaypointDiamond from '../../components/WaypointDiamond.vue'
import { withLocale } from '../../lib/routes'
import { localized } from '../../lib/localized'
import logoGold from '../../../images/logo/MARSOC_TADULAKO_logo_gold_transparent.svg'

defineOptions({ layout: SiteLayout })

defineProps({
  member: { type: Object, default: null },
  relatedActivities: { type: Array, default: () => [] },
})

const page = usePage()
const { t, locale } = useI18n()
const localePrefix = computed(() => page.props.locale)
</script>

<template>
  <div v-if="member" class="profile page-section">
    <Link :href="withLocale(localePrefix, '/personnel')" class="profile__back mono-data">{{ t('common.backToRoster') }}</Link>

    <div class="profile__card">
      <img :src="logoGold" alt="" class="profile__watermark" aria-hidden="true" />
      <div class="profile__head">
        <span class="profile__callsign mono-data">{{ member.callsign }}</span>
        <span class="profile__status" :class="`profile__status--${member.statusKey}`">
          {{ localized(member.status, locale) }}
        </span>
      </div>
      <h1 class="profile__role">{{ localized(member.role, locale) }}</h1>
      <p v-if="localized(member.bio, locale)" class="profile__bio">{{ localized(member.bio, locale) }}</p>

      <dl class="profile__fields">
        <div v-if="member.memberId"><dt>{{ t('fields.memberId') }}</dt><dd class="mono-data">{{ member.memberId }}</dd></div>
        <div v-if="member.joinDate"><dt>{{ t('fields.joined') }}</dt><dd class="mono-data">{{ member.joinDate }}</dd></div>
        <div v-if="localized(member.impressionEra, locale)"><dt>{{ t('fields.impressionEra') }}</dt><dd>{{ localized(member.impressionEra, locale) }}</dd></div>
        <div v-if="localized(member.impressionFocus, locale)"><dt>{{ t('fields.impressionFocus') }}</dt><dd>{{ localized(member.impressionFocus, locale) }}</dd></div>
        <div v-if="member.primaryPlatform"><dt>{{ t('fields.primaryPlatform') }}</dt><dd>{{ member.primaryPlatform }}</dd></div>
        <div v-if="member.secondaryPlatform"><dt>{{ t('fields.secondaryPlatform') }}</dt><dd>{{ member.secondaryPlatform }}</dd></div>
      </dl>

      <div v-if="member.grid" class="profile__grid mono-data">
        <WaypointDiamond :size="9" tone="gold" />
        {{ member.grid }}
      </div>
    </div>

    <section v-if="relatedActivities.length" class="profile__activities">
      <h2 class="section-title">{{ t('personnel.activityParticipation') }}</h2>
      <ul>
        <li v-for="a in relatedActivities" :key="a.slug">
          <Link :href="withLocale(localePrefix, `/activities/${a.slug}`)">{{ localized(a.title, locale) }}</Link>
          <span class="mono-data"> — {{ a.date }}</span>
        </li>
      </ul>
    </section>
  </div>

  <div v-else class="page-section empty-state">
    <p class="empty-state__title">{{ t('common.recordNotFound') }}</p>
    <p>{{ t('common.recordNotFoundDesc') }}</p>
    <Link class="btn btn--ghost" :href="withLocale(localePrefix, '/personnel')">{{ t('common.backToRoster') }}</Link>
  </div>
</template>

<style scoped>
.profile {
  max-width: 760px;
}

.profile__back {
  display: block;
  margin-bottom: var(--space-4);
  color: var(--ink-gold-tint);
  text-decoration: none;
  font-size: 0.8rem;
}

.profile__card {
  position: relative;
  overflow: hidden;
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  padding: var(--space-5);
}

.profile__watermark {
  position: absolute;
  top: -40px;
  right: -40px;
  width: 220px;
  height: 220px;
  opacity: 0.07;
  pointer-events: none;
}

.profile__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
  z-index: 1;
}

.profile__callsign {
  font-size: 1.4rem;
  letter-spacing: 0.04em;
}

.profile__status {
  font-size: 0.68rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  padding: 0.25rem 0.6rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--gold);
  color: var(--gold);
}

.profile__status--probationary {
  border-color: var(--red);
  color: #e78b92;
}

.profile__role {
  position: relative;
  z-index: 1;
  margin-top: var(--space-2);
  font-size: 1.8rem;
}

.profile__bio {
  position: relative;
  z-index: 1;
  margin-top: var(--space-2);
  color: var(--ink-on-dark-muted);
  max-width: 55ch;
}

.profile__fields {
  position: relative;
  z-index: 1;
  margin: var(--space-5) 0 0;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-3) var(--space-5);
}

.profile__fields dt {
  font-size: 0.65rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: var(--ink-gold-tint);
}

.profile__fields dd {
  margin: 0;
}

.profile__grid {
  position: relative;
  z-index: 1;
  margin-top: var(--space-4);
  padding-top: var(--space-3);
  border-top: 1px dashed var(--ground-line);
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8rem;
  color: var(--ink-on-dark-muted);
}

.profile__activities {
  margin-top: var(--space-6);
}

.profile__activities ul {
  list-style: none;
  padding: 0;
  margin: var(--space-3) 0 0;
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.profile__activities a {
  color: var(--white);
  text-decoration: none;
  border-bottom: 1px solid var(--ground-line);
}

@media (max-width: 600px) {
  .profile__fields {
    grid-template-columns: 1fr;
  }
}
</style>
