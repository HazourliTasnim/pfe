<template>
  <div class="container">
    <header>
      <h2>Gestion des utilisateurs</h2>
    
    </header>

  
    <div class="add-group">
  <button class="add-user" @click="openModal('add')">
    <i class="fas fa-user-plus"></i> Ajouter un utilisateur
  </button>
  <button class="add-user admin-button" @click="openModal('addAdmin')">
  <i class="fas fa-user-shield"></i> Ajouter un Admin
</button>

<ModalAdmin 
  v-if="showModalAdmin" 
  :isOpen="showModalAdmin" 
  :userData="selectedUser" 
  :isNewUser="true" 
  @close="closeModal" 
  @submit="handleAdminSubmit" 
/>

  <div class="search-container">
      <input type="text" v-model="searchQuery" placeholder="Rechercher un utilisateur..." />
    </div>
</div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Fonction</th>
          <th>Rôle</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
  <tr v-if="paginatedUsers.length === 0">
    <td colspan="8" style="text-align: center; color: gray;">Aucun utilisateur trouvé.</td>
  </tr>
  <tr v-for="user in paginatedUsers" :key="user.id">
    <td>{{ user.id }}</td>
    <td>{{ user.nom }}</td>
    <td>{{ user.prenom }}</td>
    <td>{{ user.email }}</td>
    <td>{{ user.telephone }}</td>
    <td>{{ user.fonction ? user.fonction : 'Non définie' }}</td>
    <td :class="getRoleClass(user.role)">{{ user.role }}</td>
    <td :class="getStatusClass(user.status)">{{ user.status }}</td>
    <td>
      <button class="edit" @click="openModal('edit', user)">
        <i class="fas fa-edit"></i> Modifier
      </button>
      <button class="delete" @click="confirmDelete(user.id)">
        <i class="fas fa-trash"></i> Supprimer
      </button>
    </td>
  </tr>
</tbody>


    </table>
    <div class="pagination">
  <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">
    <i class="fa-solid fa-circle-chevron-left"></i>
  </button>
  
  <span>Page {{ currentPage }} sur {{ totalPages }}</span>
  
  <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">
    <i @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"class="fa-solid fa-circle-chevron-right"></i>
  </button>
</div>
  </div>
</template>
<script>
import ModalAdmin from "@/components/ModalAdmin.vue"; 
import "@fortawesome/fontawesome-free/css/all.css";
export default {
  components: {
    ModalAdmin, 
  },
  data() {
  return {
    users: [],
    searchQuery: "", // Ajout de searchQuery pour la recherche
    selectedUser: null,
    modalMode: "",
    showModalAdmin: false,
    admin: { nom: "", prenom: "", email: "", password: "", role: "admin", status: "actif", },
    currentPage: 1, // Page actuelle
    usersPerPage: 10, 
  };
},

  methods: {
    openModal(type, user = null) {
      if (type === "addAdmin") {
        this.selectedUser = user || { nom: "", prenom: "", email: "", password: "", role: "admin", status: "actif" };
        this.showModalAdmin = true;
      }
    },
    closeModal() {
      this.showModalAdmin = false;
    },
    handleAdminSubmit(newAdmin) {
      console.log("Nouvel admin ajouté :", newAdmin);
      this.fetchUsers(); // Rafraîchir la liste des utilisateurs
      this.closeModal();
    },
    async fetchUsers() {
  try {
    const token = localStorage.getItem("token");
    const response = await fetch("http://127.0.0.1:8000/api/users", {
      headers: {
        "Accept": "application/json",
        "Authorization": token ? `Bearer ${token}` : "",
      },
    });

    if (!response.ok) throw new Error(`Erreur ${response.status}`);

    const data = await response.json();
    console.log("Données reçues :", data);
    this.users = data;
  } catch (error) {
    console.error("Erreur de chargement des utilisateurs :", error.message);
  }
}
,
goToPage(page) {
    if (page >= 1 && page <= this.totalPages) {
      this.currentPage = page;
    }},
async confirmDelete(id) {
  if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
    try {
      const token = localStorage.getItem("token");
      const response = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
        method: "DELETE",
        headers: {
          "Accept": "application/json",
          "Authorization": token ? `Bearer ${token}` : "",
        },
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Erreur ${response.status}: ${errorText}`);
      }
    } catch (error) {
      console.error("Erreur lors de la suppression de l'utilisateur :", error);
    } finally {
      this.fetchUsers(); // Recharge les utilisateurs quoi qu'il arrive
    }
  }
}
, 
   

    async confirmDelete(id) {
      if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
        try {
          const token = localStorage.getItem("token");
          const response = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
            method: "DELETE",
            headers: {
              "Accept": "application/json",
              "Authorization": token ? `Bearer ${token}` : "",
            },
          });

          if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Erreur ${response.status}: ${errorText}`);
          }

          this.fetchUsers();
        } catch (error) {
          console.error("Erreur lors de la suppression de l'utilisateur :", error);
        }
      }
    },

    logout() {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      this.$router.push("/");
    },

    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    },

    getRoleClass(role) {
      return role === "admin"
        ? "role-admin"
        : role === "entreprise"
        ? "role-entreprise"
        : "role-user";
    },

    getStatusClass(status) {
      return status === "active"
        ? "status-active"
        : status === "pending"
        ? "status-pending"
        : "status-suspended";
    },
    
  },
  computed: {
  filteredUsers() {
    if (!this.searchQuery) {
      return this.users;
    }
    return this.users.filter(user =>
      user.nom.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
      user.prenom.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
      user.email.toLowerCase().includes(this.searchQuery.toLowerCase())
    );
  },

  paginatedUsers() {
    const start = (this.currentPage - 1) * this.usersPerPage;
    const end = start + this.usersPerPage;
    return this.filteredUsers.slice(start, end);
  },

  totalPages() {
    return Math.ceil(this.filteredUsers.length / this.usersPerPage);
  }
},


  mounted() {
    this.fetchUsers();
  },
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

button.logout {
  background: #dc3545;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s;
}

button.logout:hover {
  background: #c82333;
}
.add-group {
  display: flex;
  justify-content: flex-start;
  gap: 10px; /* Ajoute un petit espace entre les boutons */
  margin-bottom: 10px;
}
button.add-user {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s;
  margin-bottom: 10px;
  margin-left: 10px;
}
button.add-user:hover {
  background: #0056b3;
}
button .admin-button {
  background: #ff63d0; 
}

.admin-button:hover {
  background: #d94baf; 
}



.search-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px;
  
 
}

.search-container input {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 14px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 10px;
  overflow: hidden;
}

thead {
  background: #f8f9fa;
  font-weight: bold;
}

th, td {
  padding: 15px;
  border-bottom: 1px solid #ddd;
  text-align: left;
}

td {
  font-size: 14px;
  color: #333;
}

th {
  font-size: 15px;
  color: #6c757d;
}

tr:hover {
  background: #f1f1f1;
}
tbody tr {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

tbody tr:hover {
  transform: scale(1.01);
  z-index: 10;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

button.edit, button.delete {
  padding: 6px 12px;
  border-radius: 5px;
  border: none;
  font-size: 12px;
  cursor: pointer;
  
}

button.edit {
  background: #17b847;
  color: white;
  margin-right: 10px;
 
}

button.delete {
  background: #e3342f;
  color: white;
}

button.edit:hover {
  background: #147414;
}

button.delete:hover {
  background: #cc1f1a;
}

/* Styles pour les rôles */
.role-admin {

  color: #ff63d0;
  padding: 4px 8px;
  border-radius: 5px;
  font-weight: bold;
}

.role-entreprise {

  color: #36a2eb;
  padding: 4px 8px;
  border-radius: 5px;
  font-weight: bold;
}

.role-user {

  color: #4bc0c0;
  padding: 4px 8px;
  border-radius: 5px;
  font-weight: bold;
}

/* Styles pour les statuts */
.status-active {
 
  color: #28a745;
  padding: 4px 8px;
  border-radius: 5px;
  font-weight: bold;
}

.status-pending {

  color: #ffc107;
  padding: 4px 8px;
  border-radius: 5px;
  font-weight: bold;
}

.status-suspended {

  color: #dc3545;
  padding: 4px 8px;
  border-radius: 5px;
  font-weight: bold;
}
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination button {
 background: none;
  color: rgb(61, 213, 30);
  border: none;
  padding: 8px 12px;
  margin: 0 5px;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s;
}
.pagination button:hover {
  color: rgb(13, 100, 26);

}
.pagination button:disabled {
  color: rgb(213, 30, 30);
  cursor: not-allowed;
}
.fas{
  margin-right: 8px; 
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
}
</style>
