<template>
  <teleport to="#toaster">
    <div v-for="t in toasts" :key="t.id" :class="['toast', t.type]">
      {{ t.message }}
    </div>
  </teleport>
</template>

<script setup>
import { reactive } from 'vue'

const toasts = reactive([])

function push(message, type = 'success', timeout = 2500) {
  const id = Date.now() + Math.random()
  toasts.push({ id, message, type })
  setTimeout(() => {
    const idx = toasts.findIndex(t => t.id === id)
    if (idx !== -1) toasts.splice(idx, 1)
  }, timeout)
}

// Expose globally
if (!window.$toast) window.$toast = { push }
</script>

<style scoped>
.toast {
  background: #1f2937;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  box-shadow: 0 4px 10px rgba(0,0,0,.2);
  font-size: 0.95rem;
}
.toast.success { background: #16a34a; }
.toast.error { background: #dc2626; }
.toast.info { background: #2563eb; }
</style>
