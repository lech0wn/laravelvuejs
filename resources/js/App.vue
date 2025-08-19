<template>
  <router-view />
  <div id="toaster" class="toaster"></div>
  <div id="confirm-root"></div>
</template>

<script setup>
// No script needed for basic routing shell
</script>

<style scoped>
.toaster {
  position: fixed;
  top: 16px;
  right: 16px;
  z-index: 2000;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
</style>
