<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import SiteLayout from '../layouts/SiteLayout.vue'
import EmptyState from '../components/EmptyState.vue'
import PageHero from '../components/PageHero.vue'
import MediaSlider from '../components/MediaSlider.vue'
import { localized } from '../lib/localized'

defineOptions({ layout: SiteLayout })

const props = defineProps({
  blocks: { type: Object, default: () => ({}) },
  eras: { type: Array, default: () => [] },
  weaponCategories: { type: Array, default: () => [] },
})

const { t, locale } = useI18n()

const L = (field) => localized(field, locale.value)
const items = (key) => props.blocks[key]?.items ?? []

const preview = ref(null) // { images, name } | null

function openPreview(item) {
  if (!item.images?.length) return
  preview.value = item
}

function closePreview() {
  preview.value = null
}
</script>

<template>
  <div>
    <PageHero page="impression" :block="blocks.hero" />

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">01</span>
        <h2 class="manual-section__title">{{ L(blocks.history?.heading) }}</h2>
      </div>
      <p v-if="L(blocks.history?.body)" class="section-lede">{{ L(blocks.history?.body) }}</p>
      <EmptyState v-if="!L(blocks.history?.body)" compact :message="t('empty.list')" />

    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">02</span>
        <h2 class="manual-section__title">{{ L(blocks.eras?.heading) }}</h2>
      </div>
      <p class="section-lede">{{ L(blocks.eras?.subheading) }}</p>
      <EmptyState v-if="!eras.length" compact :message="t('empty.eras')" />
      <div v-else class="era-grid">
        <article v-for="era in eras" :key="era.key" class="era-card">
          <button type="button" class="era-card__photo" :class="{ 'era-card__photo--empty': !era.images.length }" @click="openPreview(era)">
            <img v-if="era.images.length" :src="era.images[0]" :alt="era.name" />
            <span v-else class="mono-data">{{ t('impression.eraNoImage') }}</span>
            <span v-if="era.images.length > 1" class="photo-count mono-data">+{{ era.images.length - 1 }}</span>
          </button>
          <h3>{{ era.name }}</h3>
          <dl>
            <div><dt>{{ t('impression.eraUniformLabel') }}</dt><dd>{{ localized(era.uniform, locale) }}</dd></div>
            <div><dt>{{ t('impression.eraGearLabel') }}</dt><dd>{{ localized(era.gear, locale) }}</dd></div>
            <div><dt>{{ t('impression.eraHelmetLabel') }}</dt><dd>{{ localized(era.helmet, locale) }}</dd></div>
          </dl>
        </article>
      </div>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">03</span>
        <h2 class="manual-section__title">{{ L(blocks.weapons?.heading) }}</h2>
      </div>
      <p class="section-lede">{{ L(blocks.weapons?.subheading) }}</p>
      <EmptyState v-if="!weaponCategories.length" compact :message="t('empty.weapons')" />
      <div v-else class="weapon-categories">
        <div v-for="cat in weaponCategories" :key="cat.categoryKey" class="weapon-category">
          <h3 class="weapon-category__label mono-data">{{ cat.category }}</h3>
          <div class="weapon-grid">
            <button
              v-for="item in cat.items"
              :key="item.key"
              type="button"
              class="weapon-card"
              :class="{ 'weapon-card--empty': !item.images.length }"
              @click="openPreview(item)"
            >
              <span class="weapon-card__photo">
                <img v-if="item.images.length" :src="item.images[0]" :alt="item.name" />
                <span v-else class="mono-data">{{ t('impression.noReferenceImage') }}</span>
                <span v-if="item.images.length > 1" class="photo-count mono-data">+{{ item.images.length - 1 }}</span>
              </span>
              <span class="weapon-card__name">{{ item.name }}</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">04</span>
        <h2 class="manual-section__title">{{ L(blocks.headgear?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('headgear').length" compact :message="t('empty.list')" />
      <dl v-else class="headgear-list">
        <div v-for="h in items('headgear')" :key="h.key">
          <dt>{{ h.label }}</dt>
          <dd>{{ L(h.body) }}</dd>
        </div>
      </dl>
    </section>

    <section class="manual-section">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">05</span>
        <h2 class="manual-section__title">{{ L(blocks['eye-protection']?.heading) }}</h2>
      </div>
      <EmptyState v-if="!items('eye-protection').length" compact :message="t('empty.list')" />
      <dl v-else class="headgear-list">
        <div v-for="e in items('eye-protection')" :key="e.key">
          <dt>{{ e.label }}</dt>
          <dd>{{ L(e.body) }}</dd>
        </div>
      </dl>
    </section>

    <section class="manual-section manual-section--library">
      <div class="manual-section__head">
        <span class="manual-section__n mono-data">06</span>
        <h2 class="manual-section__title">{{ L(blocks.library?.heading) }}</h2>
      </div>
      <p class="section-lede">{{ L(blocks.library?.subheading) }}</p>
      <EmptyState v-if="!items('library').length" compact :message="t('empty.list')" />
      <ul v-else class="source-list">
        <li v-for="s in items('library')" :key="s.key">{{ s.label }}</li>
      </ul>
      <p class="source-note">{{ L(blocks.library?.footnote) }}</p>
    </section>

    <Teleport to="body">
      <Transition name="fog-slide">
        <div v-if="preview" class="lightbox" @click.self="closePreview">
          <button type="button" class="lightbox__close mono-data" @click="closePreview">✕</button>
          <div class="lightbox__frame">
            <MediaSlider :photos="preview.images" :alt="preview.name" />
          </div>
          <p class="lightbox__name">{{ preview.name }}</p>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.era-grid,
.weapon-grid {
  margin-top: var(--space-4);
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-4);
}

.era-card {
  padding: var(--space-4);
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
}

.era-card__photo {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  aspect-ratio: 16 / 9;
  margin-bottom: var(--space-3);
  background: var(--ground-950);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-sm);
  padding: 0;
  cursor: pointer;
  overflow: hidden;
}

.photo-count {
  position: absolute;
  right: 0.4rem;
  bottom: 0.4rem;
  font-size: 0.6rem;
  padding: 0.15rem 0.4rem;
  border-radius: var(--radius-sm);
  background: rgba(4, 13, 26, 0.78);
  border: 1px solid var(--ground-line);
  color: var(--ink-gold-tint);
}

.era-card__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: grayscale(0.1) contrast(1.05);
}

.era-card__photo--empty {
  cursor: default;
}

.era-card__photo span.mono-data {
  font-size: 0.62rem;
  letter-spacing: 0.06em;
  color: var(--ink-on-dark-muted);
}

.era-card h3 {
  font-family: var(--font-display);
  font-size: 1.2rem;
  margin-bottom: var(--space-3);
}

.era-card dl {
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.era-card dt {
  font-size: 0.65rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--ink-gold-tint);
}

.era-card dd {
  margin: 0;
  font-size: 0.85rem;
  color: var(--ink-on-dark-muted);
}

.weapon-categories {
  display: flex;
  flex-direction: column;
  gap: var(--space-5);
}

.weapon-category__label {
  margin-top: var(--space-4);
  font-size: 0.78rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--ink-gold-tint);
  padding-bottom: var(--space-2);
  border-bottom: 1px dashed var(--ground-line);
}

.weapon-card {
  display: flex;
  flex-direction: column;
  background: var(--ground-900);
  border: 1px solid var(--ground-line);
  border-radius: var(--radius-md);
  overflow: hidden;
  padding: 0;
  text-align: left;
  cursor: pointer;
  transition: border-color 0.2s ease, transform 0.2s ease;
}

.weapon-card:not(.weapon-card--empty):hover {
  border-color: var(--gold);
  transform: translateY(-3px);
}

.weapon-card--empty {
  cursor: default;
}

.weapon-card__photo {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  aspect-ratio: 4 / 3;
  background: var(--ground-950);
}

.weapon-card__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: grayscale(0.1) contrast(1.05);
}

.weapon-card__photo span.mono-data {
  font-size: 0.6rem;
  letter-spacing: 0.06em;
  color: var(--ink-on-dark-muted);
  padding: 0 var(--space-2);
  text-align: center;
}

.weapon-card__name {
  padding: var(--space-3);
  font-size: 0.85rem;
  color: var(--ink-on-dark);
}

.headgear-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--space-4);
  margin: var(--space-2) 0 0;
}

.headgear-list dt {
  font-family: var(--font-display);
  font-size: 1.05rem;
  margin-bottom: 0.3rem;
}

.headgear-list dd {
  margin: 0;
  color: var(--ink-on-dark-muted);
  font-size: 0.9rem;
}

.source-list {
  margin: var(--space-3) 0 0;
  padding-left: 1.1rem;
  color: var(--ink-on-dark-muted);
  font-size: 0.88rem;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.source-note {
  margin-top: var(--space-4);
  font-size: 0.85rem;
  color: var(--ink-gold-tint);
}

.manual-section--library {
  padding-bottom: var(--space-7);
}

.lightbox {
  position: fixed;
  inset: 0;
  z-index: 100;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--space-3);
  padding: var(--space-5);
  background: rgba(4, 13, 26, 0.92);
}

.lightbox__frame {
  width: min(900px, 92vw);
}

.lightbox__close {
  position: absolute;
  top: var(--space-4);
  right: var(--space-4);
  width: 2.5rem;
  height: 2.5rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--ground-line);
  background: transparent;
  color: var(--white);
  cursor: pointer;
}

.lightbox__close:hover {
  border-color: var(--gold);
  color: var(--gold);
}

.lightbox__name {
  color: var(--ink-on-dark-muted);
  font-size: 0.9rem;
}

.fog-slide-enter-active,
.fog-slide-leave-active {
  transition: opacity 0.3s var(--ease-out-quint);
}

.fog-slide-enter-from,
.fog-slide-leave-to {
  opacity: 0;
}

@media (max-width: 860px) {
  .era-grid,
  .weapon-grid,
  .headgear-list {
    grid-template-columns: 1fr;
  }
}
</style>
