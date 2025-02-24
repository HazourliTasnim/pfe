<template>
  <div>
    <TopBar />
    <ProfileUp />
    <router-view />
  </div>
    
  </template>
  <script>
  import TopBar from '@/components/TopBar.vue';
  import ProfileUp from '../components/ProfileUp.vue';
  export default {
    components: { TopBar,ProfileUp }
  };
  </script>