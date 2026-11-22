<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import AppNav from '../components/AppNav.vue'
import AppFooter from '../components/AppFooter.vue'

const page = usePage()
</script>

<template>
  <div class="app-shell">
    <Head :title="page.props.seoTitle" />
    <AppNav />
    <main class="app-main">
      <Transition name="fog" mode="out-in">
        <div :key="page.url">
          <slot />
        </div>
      </Transition>
    </main>
    <AppFooter />
  </div>
</template>

<style>
.app-shell {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.app-main {
  flex: 1;
}

/* FOG-OF-WAR RAISE: route transitions dissolve through a light mist rather than a plain fade/slide */
.fog-enter-active,
.fog-leave-active {
  transition: opacity 0.45s var(--ease-out-quint), filter 0.45s var(--ease-out-quint);
}

.fog-enter-from {
  opacity: 0;
  filter: blur(6px) brightness(1.15);
}

.fog-leave-to {
  opacity: 0;
  filter: blur(4px) brightness(0.9);
}

@media (prefers-reduced-motion: reduce) {
  .fog-enter-active,
  .fog-leave-active {
    transition: opacity 0.2s linear;
  }
  .fog-enter-from,
  .fog-leave-to {
    filter: none;
  }
}
</style>
