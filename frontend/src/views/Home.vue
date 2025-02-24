<template>
  <Header />

    <div>
    <SectionTransparence />
   </div> 
    
    <div>
      <button @click="showTenders" type="button" class="button">Appel d'offre</button>
      <button @click="showConsultations" type="button" class="button">Avis de consultation</button>
    </div>

    <!-- Affichage conditionnel -->
    <div v-if="showTenderSection">
      <TenderSearch />
      <TendersList />
    </div>

    <div v-if="showConsultationSection">
      <ConsultationSearch />
      <ConsultationsList />
    </div>

  <Footer />
</template>

<script setup>
import { ref } from 'vue';

import TendersList from '@/components/TendersList.vue';
import ConsultationsList from '@/components/ConsultationsList.vue';
import TenderSearch from '@/components/TenderSearch.vue';
import ConsultationSearch from '@/components/ConsultationSearch.vue';
import SectionTransparence from '@/components/SectionTransparence.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';


// États pour gérer l'affichage des sections
const showTenderSection = ref(true);
const showConsultationSection = ref(false);

// Fonction pour afficher la section Appel d'Offre
const showTenders = () => {
  showTenderSection.value = true;
  showConsultationSection.value = false;
};

// Fonction pour afficher la section Avis de Consultation
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

.button:active {
  transform: scale(0.95);
}
</style>
