<template>
  <div class="tenders-container">
    <h2 class="title">Les Appels d'Offres</h2>

    <div v-if="paginatedTenders.length" class="tenders-list">
      <div v-for="tender in paginatedTenders" :key="tender.id" class="tender-item">
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
          <button class="details-btn" @click="viewDetails(tender.id)">Voir Détails</button>
        </div>
      </div>
    </div>

    <p v-else class="no-tenders">Aucun appel d'offre disponible.</p>

    <!-- Pagination -->
    <div class="pagination">
      <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">Précédent</button>

      <span v-for="page in totalPages" :key="page">
        <button @click="goToPage(page)" :class="{ active: currentPage === page }">{{ page }}</button>
      </span>

      <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">Suivant</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const tenders = ref([]);
const currentPage = ref(1);
const itemsPerPage = 5;
const getLogoUrl = (logo) => {
    return logo ? `http://127.0.0.1:8000/${logo}` : '/src/assets/default-logo.png';
};

onMounted(async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/appel-offres");
    tenders.value = Array.isArray(response.data) ? response.data : [];
  } catch (error) {
    console.error("Erreur lors de la récupération des appels d'offres:", error);
  }
});

const router = useRouter();

// Aller aux détails d'un appel d'offre
const viewDetails = (id) => {
  if (id) router.push({ name: "TenderDetails", params: { id } });
};

// Pagination
const totalPages = computed(() => Math.ceil(tenders.value.length / itemsPerPage));

const paginatedTenders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return tenders.value.slice(start, start + itemsPerPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page;
};

</script>

<style scoped>
.tenders-container {
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

.tender-details {
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

.tender-number {
  font-size: 12px;
  font-weight: bold;
  text-transform: uppercase;
  color: #0bd365;
}

.tender-title {
  font-size: 16px;
  font-weight: 500;
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

.no-tenders {
  text-align: center;
  font-size: 16px;
  color: #777;
}
.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  gap: 5px;
}

.pagination button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 3px;
}

.pagination button.active {
  background-color: #0bd365;
  font-weight: bold;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>
