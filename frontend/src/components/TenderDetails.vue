<template>
  <div class="wrapper">
    <Header />
    <div class="tender-detail-container">
      <div v-if="tender"> 
        <div class="tender-item">
        
          <div class="tender-details">
          <div class="entete-logo">
            <img :src="getLogoUrl(tender.entreprise?.logo)" alt="Logo de l'entreprise" class="tender-logo" />
          </div>
          <div class="entete-infos">
            <h3 class="tender-number">{{ tender.numero_offre || 'N/A' }}</h3>
            <p class="tender-title">{{ tender.titre_offre || 'Titre non disponible' }}</p>
          </div>
          <div class="tender-location">
            <img src="/src/assets/broche-de-localisation.png" alt="Location Icon" class="location-icon" />
            <p class="location">{{ tender.wilaya || 'Non spécifié' }}</p>
         
        </div> 
      </div>
        <div class="tender-content">
          <p class="tender-description">{{ tender.description || 'Aucune description disponible.' }}</p>
          <div class="detail-dates">
  <div class="detail-row">
    <p><strong>Date de publication :</strong> {{ tender.date_publication || 'Non spécifiée' }}</p>
    <p><strong>Date limite :</strong> {{ tender.date_limite ? tender.date_limite : 'Non spécifiée' }}</p>
    <p><strong>Date de prorogation :</strong> {{ tender.date_prorogation ? tender.date_prorogation : 'Non spécifiée' }}</p>
    <p>
      <strong>État :</strong>
      <span :class="getStatusClass(tender.etat_offre)">{{ tender.etat_offre || 'Non spécifié' }}</span>
    </p>
  </div>
</div>
          <button class="details-btn" @click="viewDetails(tender)">Voir Détails</button>
       
      </div>
      </div>
    </div>

      <p v-else class="loading">Chargement...</p>
    </div>
   <Footer />
   </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import Footer from './Footer.vue';
import Header from './Header.vue';

const route = useRoute();
const tender = ref(null);
const viewDetails = (tender) => {
  if (tender.document_pdf) {
    // Ouvre le PDF dans un nouvel onglet
    window.open(`http://127.0.0.1:8000/${tender.document_pdf}`, '_blank');
  } else {
    // Redirige vers la page de détail
    router.push({ name: "TenderDetails", params: { id: tender.id } });
  }
};


const getLogoUrl = (logo) =>
  logo ? `http://127.0.0.1:8000/${logo}` : '/src/assets/default-logo.png';

const getStatusClass = (status) => {
  return {
    "status-open": status === "En Cours",
    "status-closed": status === "Clôturé",
  };
};

onMounted(async () => {
  const id = route.params.id;
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/appel-offres/${id}`);
    tender.value = response.data;
  } catch (error) {
    console.error("Erreur lors de la récupération du détail de l'appel d'offre:", error);
  }
});

</script>

<style scoped>

.wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh; 
}

footer {
  margin-top: auto; 
}
.tender-detail-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;

}


.tender-item {
  border: 1px solid #ddd;
  padding: 15px;
  background-color: #fff;
  border-radius: 5px;
  height: auto;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.tender-item:hover {
  transform: translateY(-5px);
}

.entete-infos {
  flex-grow: 1;
  text-align:left;
  margin-right: 20px;
  line-height: 0.9;
  color: #333;
}
.tender-details {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 20px;
  border-bottom: solid 1px #444;
}
.tender-item {
  border: 1px solid #ddd;
  padding: 15px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.entete-logo img {
  width: 60px;
  height: 60px;
  object-fit: contain;
  border-right: solid 1px #9a9a9a;
  margin-right: 20px;
  padding-right: 20px;
}



.tender-number {
  font-size: 12px;
  font-weight: bold;
  text-align:left;
  text-transform: uppercase;
  color: #0bd365;
}

.tender-title {
  font-size: 16px;
  font-weight: 500;
  text-align:left;
  text-transform: uppercase;
}

.location {
  font-size: 13px;
  color: #777;
}

.location-icon {
  width: 30px;
  height: auto;
}

.tender-location {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  line-height: 0.1;
  padding-right: 10px;
}

.tender-description {
  font-size: 14px;
  color: #444;
  margin-left: 20px;
}

.tender-content {
  margin-top: 30px;
  color: #333;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  text-align: left;
  direction: rtl;
}

.details-btn {
  background-color: #007bff;
  color: white;
  padding: 5px 10px;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  margin-top: 10px;
  align-self: flex-start;
}

.details-btn:hover {
  background-color: #0056b3;
}
  

.detail-dates p {
  font-size: 14px;
  color: #555;

}

.detail-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* Deux colonnes */
  gap: 10px;
  margin-top: 10px;
}

.detail-row p {
  white-space: nowrap;
}

@media (max-width: 768px) {
  .detail-row {
    grid-template-columns: 1fr; 
  }
}



.loading {
  text-align: center;
  font-size: 16px;
  color: #777;
}

.status-open {
  color: green;
  font-weight: bold;
}

.status-closed {
  color: red;
  font-weight: bold;
}
</style>