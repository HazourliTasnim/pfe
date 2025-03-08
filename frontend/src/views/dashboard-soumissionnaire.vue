<template>
    <div v-if="user">
      <h1>Bienvenue, soumissionnaire !</h1>
      <p>Explorez les appels d'offres et les avis de consultation.</p>
  
      <!-- Bouton de déconnexion -->
      <button @click="logout" class="button logout">Se déconnecter</button>
  
      <!-- Navigation -->
      <div>
        <button @click="showTenders" class="button">Appel d'offre</button>
        <button @click="showConsultations" class="button">Avis de consultation</button>
      </div>
  
      <!-- Sections conditionnelles -->
      <div v-if="showTenderSection">
      
        <TendersList />
      </div>
  
      <div v-if="showConsultationSection">
        <ConsultationSearch />
        <ConsultationsList />
      </div>
    </div>
  
    <!-- Si l'utilisateur n'est pas chargé -->
    <div v-else>
      <p>Chargement...</p>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import api from '../api';
  import TendersList from '@/components/TendersList.vue';
  import ConsultationsList from '@/components/ConsultationsList.vue';
  import ConsultationSearch from '@/components/ConsultationSearch.vue';
  
  const router = useRouter();
  const user = ref(null);
  
  // Récupérer l'utilisateur connecté au chargement de la page
  onMounted(async () => {
    try {
      const response = await api.get('/user'); // Endpoint backend qui renvoie l'utilisateur connecté
      user.value = response.data;
    } catch (error) {
      console.error("Utilisateur non authentifié", error);
      router.push('/'); // Rediriger vers login si l'utilisateur n'est pas connecté
    }
  });
  
  // Déconnexion
  const logout = async () => {
    try {
      await api.post('/logout');
      localStorage.removeItem('token');
      user.value = null;
      router.push('/');
    } catch (error) {
      console.error('Erreur lors de la déconnexion');
    }
  };
  
  // États pour afficher les sections
  const showTenderSection = ref(true);
  const showConsultationSection = ref(false);
  
  const showTenders = () => {
    showTenderSection.value = true;
    showConsultationSection.value = false;
  };
  
  const showConsultations = () => {
    showTenderSection.value = false;
    showConsultationSection.value = true;
  };
  </script>
  
  <style scoped>
  .button {
    --h-button: 48px;
    --w-button: 140px;
    --round: 0.75rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    background: linear-gradient(135deg, #2b1682, #1f1645);
    color: white;
    border-radius: var(--round);
    border: none;
    padding: 12px 18px;
    margin: 10px;
  }
  
  .logout {
    background: linear-gradient(135deg, #b71c1c, #7f0000);
  }
  
  .button:active {
    transform: scale(0.95);
  }
  </style>
  