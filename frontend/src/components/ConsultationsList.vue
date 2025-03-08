<template>
    <div class="consultations-container">
      <h2 class="title">Les Avis de Consultation</h2>
  
      <div v-if="consultations.length > 0" class="consultations-list">
        <div v-for="consultation in consultations" :key="consultation.id" class="consultation-item">
          <!-- En-tête -->
          <div class="consultation-details">
            <div class="entete-logo">
              <img :src="consultation.logoUrl" alt="Logo de l'entreprise" class="consultation-logo" />
            </div>
            <div class="entete-infos">
              <h3 class="consultation-number">{{ consultation.number }}</h3>
              <p class="consultation-title">{{ consultation.title }}</p>
            </div>
            <div class="consultation-location">
              <img src="/src/assets/broche-de-localisation.png" alt="Location Icon" class="location-icon" />
              <p class="location">{{ consultation.location }}</p>
            </div>
          </div>
          <!-- Contenu -->
          <div class="consultation-content">
            <p class="consultation-description">{{ consultation.description }}</p>
            <button class="details-btn" @click="viewDetails(consultation.id)">Voir Détails</button>
          </div>
        </div>
      </div>
  
      <p v-else class="no-consultations">Aucun avis de consultation disponible.</p>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  
  const router = useRouter();
  
  const consultations = ref([
    {
      id: 1,
      number: "N° 10/2025",
      title: "CONSULTATION POUR L'ACHAT DE MATÉRIEL INFORMATIQUE",
      location: "Oran",
      description: "Achat de serveurs et équipements pour les infrastructures IT.",
      logoUrl: "/src/assets/logo-gta.png",
    },
    {
      id: 2,
      number: "N° 11/2025",
      title: "CONSULTATION POUR SERVICES DE NETTOYAGE",
      location: "Annaba",
      description: "Contrat de nettoyage pour les locaux administratifs.",
      logoUrl: "/src/assets/logo-ems.png",
    },
  ]);
  
  const viewDetails = (id) => {
    router.push(`/consultations/${id}`); // Redirection vers la page de détails
  };
  </script>
  
  <style scoped>
  .consultations-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
  }
  
  .consultations-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  
  .consultation-item {
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  }
  
  .consultation-item:hover {
    transform: translateY(-5px);
  }
  
  .consultation-details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 20px;
    border-bottom: solid 1px #444;
  }
  
  .entete-logo img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border-right: solid 1px #9a9a9a;
    margin-right: 20px;
    padding-right: 20px;
  }
  
  .entete-infos {
    flex-grow: 1;
    justify-items: left;
    margin-right: 20px;
    line-height: 0.9;
    color: #333;
  }
  
  .consultation-number {
    font-size: 14px;
    font-weight: bold;
    color: #555;
  }
  
  .consultation-title {
    font-size: 16px;
    font-weight: 500;
  }
  
  .location {
    font-size: 13px;
    color: #777;
  }
  
  .location-icon {
    width: 30px;
    height: auto;
  }
  
  .consultation-location {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    line-height: 0.1;
    padding-right: 10px;
  }
  
  .consultation-description {
    font-size: 14px;
    color: #444;
  }
  
  .consultation-content {
    margin-top: 30px;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    text-align: right;
    direction: rtl;
  }
  
  .details-btn {
    background-color: #28a745;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-top: 10px;
    align-self: flex-start;
  }
  
  .details-btn:hover {
    background-color: #218838;
  }
  
  .no-consultations {
    text-align: center;
    font-size: 16px;
    color: #777;
  }
  </style>

