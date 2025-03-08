<template>
  <div class="container">
    <header>
      <h2>Gestion des Entreprises</h2>
    </header>

    <div class="add-group">
      <button class="add-entreprise" @click="openModal">
        <i class="fas fa-building"></i> Ajouter une Entreprise
      </button>

      <div class="search-container">
        <input type="text" v-model="searchQuery" placeholder="Rechercher une entreprise..." />
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Activité</th>
          <th>Categorie</th>
       
          <th>Pays</th>
          <th>Wilaya</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody v-if="paginatedEntreprises.length">
  <tr v-for="entreprise in paginatedEntreprises" :key="entreprise.id">
    <td>{{ entreprise.id }}</td>
    <td>{{ entreprise.nom_entreprise }}</td>
    <td>{{ entreprise.activite }}</td>
    <td>{{ entreprise.categorie }}</td>
    <td>{{ entreprise.pays }}</td>
    <td>{{ entreprise.wilaya }}</td>
    <td>
      <button class="edit" @click="editEntreprise(entreprise)">
        <i class="fas fa-edit"></i> Modifier
      </button>
      <button class="delete" @click="confirmDelete(entreprise.id)">
        <i class="fas fa-trash"></i> Supprimer
      </button>
      <img :src="'http://127.0.0.1:8000/' + entreprise.logo.replace('public/', 'storage/')" alt="Logo de l'entreprise">





    </td>
  </tr>
</tbody>
<tbody v-else>
  <tr>
    <td colspan="8" style="text-align: center; color: gray;">Chargement des entreprises...</td>
  </tr>
</tbody>

    </table>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      entreprises: [],
      searchQuery: "",
      currentPage: 1,
      entreprisesPerPage: 15,
      detailsVisible: {}
    };
  },
  methods: {
    async fetchEntreprises() {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get('http://127.0.0.1:8000/api/entreprises', {
      headers: { Authorization: `Bearer ${token}` }
    });

    console.log("Données reçues :", response.data); // <-- Vérifier ici
    this.entreprises = response.data;
  } catch (error) {
    console.error("Erreur de chargement :", error);
  }
},
handleImageError(entreprise) {
    console.error("Image non trouvée pour:", entreprise.nom_entreprise, "Chemin:", entreprise.logo);
  }
  },
  computed: {
    paginatedEntreprises() {
      const start = (this.currentPage - 1) * this.entreprisesPerPage;
      return this.entreprises.slice(start, start + this.entreprisesPerPage);
    }
  },
  mounted() {
    this.fetchEntreprises();
  }
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
  }
  
  header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  button.add-entreprise {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  button.add-entreprise:hover {
    background: #218838;
  }
  .logo {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 5px;
}

  .search-container {
    margin-left: auto;
  }
  
  .search-container input {
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
  }
  
  th {
    background: #f8f9fa;
  }
  
  button.edit {
    background: #ffc107;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
  }
  
  button.delete {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
  }
  
  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
  
  .pagination button {
    background: #007bff;
    color: white;
    border: none;
    padding: 5px 10px;
    margin: 0 5px;
    cursor: pointer;
  }
  
  .pagination button:disabled {
    background: #ccc;
  }
.container {
  max-width: 1200px;
  margin: auto;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

button.more-info {
  background: #007bff;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 5px;
}

.details {
  background: #f9f9f9;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-top: 10px;
}

.logo {
  max-width: 100px;
  border-radius: 5px;
}
</style>
