<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);

onMounted(async () => {
    const response = await axios.get('/users'); // Récupère les utilisateurs depuis Laravel
    users.value = response.data;
});
</script>

<template>
  <div>
    <h1>Liste des utilisateurs</h1>
    <ul>
      <li v-for="user in users" :key="user.id">{{ user.name }}</li>
    </ul>
  </div>
</template>
