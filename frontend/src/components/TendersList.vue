<template>
  <div class="tenders-container">
   

    <!-- Champ de recherche -->
    <div class="search-container">
      <h2 class="title">
  <img src="@/assets/search-icon.png" alt="Icon" class="title-icon"> 
  Rechercher un appel d’offre
</h2>


    <div class="search">
      <input v-model="searchQuery" type="text" placeholder="Rechercher un appel d'offre..." class="input" />

      <select v-model="selectedWilaya" class="search-select">
        <option value="">-- Wilayas --</option>
        <option v-for="wilaya in wilayas" :key="wilaya.id" :value="wilaya.name">
          {{ wilaya.name }}
        </option>
      </select>

      <select v-model="selectedCompany" class="search-select">
        <option value="null">-- Entreprises ou Organismes --</option>
        <option v-for="entreprise in entreprises" 
        :key="entreprise.id" 
        :value="Number(entreprise.id)">
  {{ entreprise.name }}
</option>

      </select>
     
    </div>
 
  </div>
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
import { ref, computed, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

// Déclaration des variables réactives
const tenders = ref([]);
const searchQuery = ref(""); 
const currentPage = ref(1);
const itemsPerPage = 5;

const selectedWilaya = ref("");
const selectedCompany = ref(null); 

// Listes d'options
const wilayas = ref([
  { id: 1, name: 'Adrar' },
  { id: 2, name: 'Chlef' },
  { id: 3, name: 'Laghouat' },
  { id: 4, name: 'Oum El Bouaghi' },
  { id: 5, name: 'Batna' },
  { id: 6, name: 'Béjaïa' },
  { id: 7, name: 'Biskra' },
  { id: 8, name: 'Béchar' },
  { id: 9, name: 'Blida' },
  { id: 10, name: 'Bouira' },
  { id: 11, name: 'Tamanrasset' },
  { id: 12, name: 'Tébessa' },
  { id: 13, name: 'Tlemcen' },
  { id: 14, name: 'Tiaret' },
  { id: 15, name: 'Tizi Ouzou' },
  { id: 16, name: 'Alger' },
  { id: 17, name: 'Djelfa' },
  { id: 18, name: 'Jijel' },
  { id: 19, name: 'Sétif' },
  { id: 20, name: 'Saïda' },
  { id: 21, name: 'Skikda' },
  { id: 22, name: 'Sidi Bel Abbès' },
  { id: 23, name: 'Annaba' },
  { id: 24, name: 'Guelma' },
  { id: 25, name: 'Constantine' },
  { id: 26, name: 'Médéa' },
  { id: 27, name: 'Mostaganem' },
  { id: 28, name: "M'Sila" },
  { id: 29, name: 'Mascara' },
  { id: 30, name: 'Ouargla' },
  { id: 31, name: 'Oran' },
  { id: 32, name: 'El Bayadh' },
  { id: 33, name: 'Illizi' },
  { id: 34, name: 'Bordj Bou Arréridj' },
  { id: 35, name: 'Boumerdès' },
  { id: 36, name: 'El Tarf' },
  { id: 37, name: 'Tindouf' },
  { id: 38, name: 'Tissemsilt' },
  { id: 39, name: 'El Oued' },
  { id: 40, name: 'Khenchela' },
  { id: 41, name: 'Souk Ahras' },
  { id: 42, name: 'Tipaza' },
  { id: 43, name: 'Mila' },
  { id: 44, name: 'Aïn Defla' },
  { id: 45, name: 'Naâma' },
  { id: 46, name: 'Aïn Témouchent' },
  { id: 47, name: 'Ghardaïa' },
  { id: 48, name: 'Relizane' },
  { id: 49, name: 'Timimoun' },
  { id: 50, name: 'Bordj Badji Mokhtar' },
  { id: 51, name: 'Ouled Djellal' },
  { id: 52, name: 'Béni Abbès' },
  { id: 53, name: 'In Salah' },
  { id: 54, name: 'In Guezzam' },
  { id: 55, name: 'Touggourt' },
  { id: 56, name: 'Djanet' },
  { id: 57, name: "El M'Ghair" },
  { id: 58, name: 'El Menia' }
]);

const entreprises = ref([
  { id: '1', name: 'Ministère de la Poste et des Télécommunications' },
  { id: '2', name: 'ANF' },
  { id: '3', name: 'Algérie Poste' },
  { id: '4', name: 'INPTIC' },
  { id: '5', name: 'INTTIC' },
  { id: '6', name: 'Groupe GTA' },
  { id: '7', name: 'Algérie Télécom' },
  { id: '8', name: 'Mobilis' },
  { id: '9', name: 'ATS' },
  { id: '10', name: 'SATICOM' },
  { id: '11', name: 'COMINTAL' },
  { id: '12', name: 'ANPT' },
  { id: '13', name: 'EADN' },
  { id: '14', name: 'ECI TIC' },
  { id: '15', name: 'NetBeOpen Prime' },
  { id: '16', name: 'WhiteBay Limited' },
  { id: '17', name: 'EMS' },
]);

// Fonction de filtrage
const searchTenders = () => {
  return tenders.value.filter(tender => {
    const matchesQuery = searchQuery.value
      ? tender.titre_offre?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        tender.numero_offre?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        tender.wilaya?.toLowerCase().includes(searchQuery.value.toLowerCase())
      : true;

    const matchesWilaya = selectedWilaya.value
      ? tender.wilaya?.toLowerCase() === selectedWilaya.value.toLowerCase()
      : true;

    const matchesCompany = selectedCompany.value
      ? Number(tender.entreprise?.id) === Number(selectedCompany.value)
      : true;

    return matchesQuery && matchesWilaya && matchesCompany;
  });
};

// Liste filtrée via une propriété calculée
const filteredTenders = computed(() => searchTenders());

// Réinitialiser la page actuelle lors du changement des filtres
watch([searchQuery, selectedWilaya, selectedCompany], () => {
  currentPage.value = 1;
});

// Fonction pour obtenir l'URL du logo
const getLogoUrl = (logo) =>
  logo ? `http://127.0.0.1:8000/${logo}` : '/src/assets/default-logo.png';

// Appel API pour récupérer les appels d'offres
onMounted(async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/appel-offres");
    tenders.value = Array.isArray(response.data) ? response.data : [];
  } catch (error) {
    console.error("Erreur lors de la récupération des appels d'offres:", error);
  }
});

const router = useRouter();

// Gestion de la pagination
const totalPages = computed(() =>
  Math.ceil(filteredTenders.value.length / itemsPerPage)
);

const paginatedTenders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredTenders.value.slice(start, start + itemsPerPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const viewDetails = (id) => {
  if (id) {
    router.push({ name: "TenderDetails", params: { id } });
  }
};
</script>

<style scoped>
.input {
 
  color: #242424;
  min-height: 40px;
  border-radius: 4px;
  outline: none;
  border: solid 1px rgb(208, 208, 208);
  line-height: 1.15;
  box-shadow: 0px 10px 20px -18px;
}

.input:focus {
  border-bottom: 2px solid #23205f;
  border-radius: 4px 4px 2px 2px;
}

.input:hover {
  outline: 1px solid lightgrey;
}

.search-container {
  max-width: 1200px;
  margin: 20px auto;
  text-align: center;
  border-radius: 4px;
  background-color: #ffffff;
  padding: 2rem;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.search {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.title {
  font-size: 1.5rem;
  margin-bottom: 20px;
  color: #242424;
}

.search-select {
  width: 70%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #fff; 
  color: #242424;
}

.tenders-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.title {
  text-align: left;
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
  text-align:left;
  margin-right: 20px;
  line-height: 0.9;
  color: #333;
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
  background-color: #0bd3a4;
  font-weight: bold;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
.title-icon{
  height: 23px;
  margin-right: 20px;
}
</style>
