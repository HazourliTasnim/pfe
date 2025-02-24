<template>
  <div>
    <Topbar />
    <router-view />
  </div>
    <div>
      <h2>🏠 Dashboard</h2>
      <p>Bienvenue sur le tableau de bord</p>
    </div>
  </template>
  <script>
  import Topbar from '@/components/Topbar.vue';
  
  export default {
    components: { Topbar }
  };
  </script>