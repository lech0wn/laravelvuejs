<template>
  <teleport to="#confirm-root">
    <div v-if="state.open" class="confirm-overlay">
      <div class="confirm-modal">
        <div class="confirm-title">{{ state.title }}</div>
        <div class="confirm-message">{{ state.message }}</div>
        <div class="confirm-actions">
          <button class="btn" @click="onCancel">Cancel</button>
          <button class="btn primary" @click="onConfirm">Confirm</button>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script setup>
import { reactive } from 'vue'

const state = reactive({ open: false, title: 'Confirm', message: '', resolve: null })

function open(message, title = 'Confirm') {
  return new Promise((resolve) => {
    state.title = title
    state.message = message
    state.open = true
    state.resolve = resolve
  })
}

function onCancel() {
  state.open = false
  state.resolve && state.resolve(false)
}

function onConfirm() {
  state.open = false
  state.resolve && state.resolve(true)
}

// Expose globally
if (!window.$confirm) window.$confirm = { open }
</script>

<style scoped>
.confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}
.confirm-modal {
  background: #fff;
  color: #111827;
  width: 380px;
  border-radius: 8px;
  padding: 16px;
  box-shadow: 0 10px 25px rgba(0,0,0,.2);
}
.confirm-title {
  font-weight: 700;
  margin-bottom: 8px;
}
.confirm-message {
  margin-bottom: 16px;
}
.confirm-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}
.btn {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  background: #f3f4f6;
  border-radius: 6px;
  cursor: pointer;
}
.btn.primary {
  background: #2563eb;
  color: #fff;
  border-color: #2563eb;
}
</style>
