<script setup>
import { ref, computed } from 'vue';

// Liste des wilayas (à remplacer par des données dynamiques)
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
  { id: 28, name: 'M\'Sila' },
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
  { id: 57, name: 'El M\'Ghair' },
  { id: 58, name: 'El Menia' }
]);

// Liste des entreprises/organismes (exemple)
const entreprises = ref([
{ id: 'mpt', name: 'Ministère de la Poste et des Télécommunications' },
  { id: 'anf', name: 'ANF' },
  { id: 'ap', name: 'Algérie Poste' },
  { id: 'inptic', name: 'INPTIC' },
  { id: 'inttic', name: 'INTTIC' },
  { id: 'gta', name: 'Groupe GTA' },
  { id: 'at', name: 'Algérie Télécom' },
  { id: 'atm', name: 'Mobilis' },
  { id: 'ats', name: 'ATS' },
  { id: 'saticom', name: 'SATICOM' },
  { id: 'comintal', name: 'COMINTAL' },
  { id: 'anpt', name: 'ANPT' },
  { id: 'eadn', name: 'EADN' },
  { id: 'ectic', name: 'ECI TIC' },
  { id: 'netbeopen', name: 'NetBeOpen Prime' },
  { id: 'whitebay', name: 'WhiteBay Limited' },
  { id: 'ems', name: 'EMS' }
]);

// Liste des avis de consultation
const tenders = ref([
  { id: 1, title: 'Construction de route', wilaya: 'Alger', company: 'mpt' },
  { id: 2, title: 'Fourniture de matériel informatique', wilaya: 'Oran', company: 'at' },
  { id: 3, title: 'Travaux de rénovation', wilaya: 'Blida', company: 'ap' }
]);

// Variables réactives
const searchQuery = ref('');
const selectedWilaya = ref('');
const selectedCompany = ref('');
const filteredResults = ref([]);

// Fonction pour obtenir le nom de l’entreprise
const getCompanyName = (id) => {
  const company = entreprises.value.find(e => e.id === id);
  return company ? company.name : 'Entreprise inconnue';
};

// Fonction de recherche
const searchTenders = () => {
  filteredResults.value = tenders.value.filter(tender => {
    const matchesSearch = searchQuery.value
      ? tender.title.toLowerCase().includes(searchQuery.value.toLowerCase())
      : true;
    const matchesWilaya = selectedWilaya.value ? tender.wilaya === selectedWilaya.value : true;
    const matchesCompany = selectedCompany.value ? tender.company === selectedCompany.value : true;

    return matchesSearch && matchesWilaya && matchesCompany;
  });
};
</script>

<template>
  <div class="search-container">
    <h2 class="title">Rechercher un avis de consultation</h2>

    <div class="search">
      <input v-model="searchQuery" type="text" placeholder="Rechercher un avis de consultation..." class="input" />

      <select v-model="selectedWilaya" class="search-select">
        <option value="">-- Wilayas --</option>
        <option v-for="wilaya in wilayas" :key="wilaya.id" :value="wilaya.name">
          {{ wilaya.name }}
        </option>
      </select>

      <select v-model="selectedCompany" class="search-select">
        <option value="">-- Entreprises ou Organismes --</option>
        <option v-for="entreprise in entreprises" :key="entreprise.id" :value="entreprise.id">
          {{ entreprise.name }}
        </option>
      </select>

      <button @click="searchTenders" class="search-button">Lancer la recherche</button>
    </div>

    <ul v-if="filteredResults.length" class="results-list">
      <li v-for="tender in filteredResults" :key="tender.id" class="result-item">
        {{ tender.title }} - <strong>{{ tender.wilaya }}</strong> ({{ getCompanyName(tender.company) }})
      </li>
    </ul>

    <p v-else class="no-results">Aucun avis de consultation trouvé.</p>
  </div>
</template>
<style scoped>
/* From Uiverse.io by alexruix */
.input {
  max-width: 190px;
  background-color: #acacac9d;
  color: #242424;
  padding: .15rem 2rem;
  min-height: 40px;
  border-radius: 4px;
  outline: none;
  border: none;
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

/* Existing styles */
.search-container {
 
  max-width: 1120px;
  margin: 20px auto;
  text-align: center;
  border-radius: 4px;
  background-color: #ffffff;
  padding: 2rem;
  box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}
.search {
  display:flex;
  justify-content: space-between;
  gap:10px;
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
  background-color: #434343;
}

.search-button {
  width: 100%;
  padding: 10px;
  background-color: #170e4d;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  
}

.search-button:hover {
  background-color: #0056b3;
}

.results-list {
  list-style: none;
  padding: 0;
}

.result-item {
  padding: 8px;
  color: #505050;
  margin-bottom: 5px;
  border-radius: 5px;
}

.no-results {
  color: #888;
}
</style>
