<template>
<div>

  <router-view />

</div>
</template>
<script setup>


</script>
<style scoped>
nav {
  display: flex;
  gap: 20px;
  padding: 10px;
}
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #8d64ffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
