<template>
    <div class="tenders-container">
      <h2 class="title">Les Appels d'Offres</h2>
  
      <div v-if="tenders.length > 0" class="tenders-list">
        <div v-for="tender in tenders" :key="tender.id" class="tender-item">
          <!-- En-tête avec logo et informations -->
          <div class="tender-header">
            <img :src="tender.logoUrl" alt="Logo de {{ tender.title }}" class="tender-logo" />
            <div class="tender-info">
              <h3 class="tender-number">{{ tender.number }}</h3>
              <p class="tender-title">{{ tender.title }}</p>
              <div class="tender-meta">
                <p><strong>Lieu :</strong> {{ tender.location }}</p>
                <p>
                  <strong>État :</strong> 
                  <span :class="getStatusClass(tender.status)">{{ tender.status }}</span>
                </p>
              </div>
            </div>
          </div>
  
          <!-- Dates -->
          <div class="tender-dates">
            <p><strong>Date de publication :</strong> {{ tender.publicationDate }}</p>
            <p><strong>Date limite :</strong> {{ tender.deadline }}</p>
          </div>
  
          <!-- Contenu avec description et bouton -->
          <div class="tender-content">
            <p class="tender-description">{{ tender.description }}</p>
            <button class="details-btn" @click="viewDetails(tender.id)" tabindex="0">
              Voir Détails
            </button>
          </div>
        </div>
      </div>
  
      <p v-else class="no-tenders">Aucun appel d'offre disponible.</p>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  import logoAt from "@/assets/logo-at.png"; // Import correct du logo
  
  const router = useRouter();
  
  const tenders = ref([
    {
      id: 1,
      number: "N° 01/AT/DG/DCMI/CTMER/2025",
      title: "APPEL A MANIFESTATION D’INTERET NATIONAL",
      location: "Alger",
      status: "En Cours",
      publicationDate: "06/02/2025",
      deadline: "23/02/2025",
      description: "La présélection de déposants pour un partenariat dans le cadre du Dépôt Vente.",
      logoUrl: logoAt,
    },
  ]);
  
  const viewDetails = (id) => {
    router.push(`/details/${id}`);
  };
  
  const getStatusClass = (status) => {
    return {
      "status-open": status === "En Cours",
      "status-closed": status === "Clôturé",
    };
  };
  </script>
  
  <style scoped>
  .tenders-container {
    max-width: 900px;
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
  
  .tenders-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  
  .tender-item {
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  }
  
  .tender-item:hover {
    transform: translateY(-5px);
  }
  
  .tender-header {
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
  }
  
  .tender-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
  }
  
  .tender-info {
    flex-grow: 1;
    color: #333;
  }
  
  .tender-number {
    font-size: 14px;
    font-weight: bold;
  }
  
  .tender-title {
    font-size: 16px;
    font-weight: 500;
  }
  
  .tender-meta p {
    font-size: 14px;
    color: #777;
  }
  
  .tender-dates {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
  }
  
  .tender-content {
    margin-top: 15px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  
  .tender-description {
    font-size: 14px;
    color: #444;
    text-align: left;
  }
  
  .details-btn {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-top: 10px;
  }
  
  .details-btn:hover {
    background-color: #0056b3;
  }
  
  .status-open {
    color: green;
    font-weight: bold;
  }
  
  .status-closed {
    color: red;
    font-weight: bold;
  }
  
  .no-tenders {
    text-align: center;
    font-size: 16px;
    color: #777;
  }
  </style>
  