<script setup>
defineProps({
  message: { type: String, required: true },
  compact: { type: Boolean, default: false },
})
</script>

<template>
  <div class="empty" :class="{ 'empty--compact': compact }" role="status">
    <span class="empty__mark" aria-hidden="true" />
    <p class="empty__message mono-data">{{ message }}</p>
  </div>
</template>

<style scoped>
.empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-6) var(--space-4);
  text-align: center;
  border: 1px dashed var(--ground-line);
  border-radius: var(--radius-md);
  color: var(--ink-on-dark-muted);
}

.empty--compact {
  padding: var(--space-4);
  align-items: flex-start;
  text-align: left;
}

.empty__mark {
  width: 10px;
  height: 10px;
  transform: rotate(45deg);
  border: 1px solid var(--ink-gold-tint);
}

.empty__message {
  font-size: 0.8125rem;
  letter-spacing: 0.04em;
  max-width: 52ch;
}
</style>
