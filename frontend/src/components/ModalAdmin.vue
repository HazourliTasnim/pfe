<template>
  <div v-if="isOpen" class="modal-overlay">
    <div class="modal-content">
      <h3>{{ title }}</h3>
      <form @submit.prevent="submitForm">
        <div class="input-group">
          <input type="text" v-model="user.nom" placeholder="Nom" required />
          <input type="text" v-model="user.prenom" placeholder="Prénom" required />
        </div>
        <input type="text" v-model="user.telephone" placeholder="telephone" required />
        <input type="email" v-model="user.email" placeholder="Email" required />
        <input v-if="isNewUser" type="password" v-model="user.password" placeholder="Mot de passe" required />
        <input v-if="isNewUser" type="password" v-model="user.password_confirmation" placeholder="Confirmer le mot de passe" required />

        <div v-if="alert.message" :class="['alert', alert.type]">
          <i :class="alert.type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle'"></i>
          {{ alert.message }}
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn primary">{{ isNewUser ? "Ajouter" : "Modifier" }}</button>
          <button type="button" class="btn secondary" @click="closeModal">Annuler</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: {
    isOpen: Boolean,
    userData: Object,
    isNewUser: Boolean,
  },
  emits: ["close", "submit"],
  data() {
    return {
      user: {},
      alert: { message: "", type: "" },
    };
  },
  computed: {
    title() {
      return this.isNewUser ? "Ajouter un administrateur" : "Modifier l'administrateur";
    },
  },
  methods: {
    translateErrorMessage(message) {
      const translations = {
        "The password field confirmation does not match.": "La confirmation du mot de passe ne correspond pas.",
        "The email has already been taken.": "Cet email est déjà utilisé.",
        "The password field must be at least 6 characters.": "Le mot de passe doit contenir au moins 6 caractères."
      };
      return translations[message] || message;
    },
    async submitForm() {
      try {
        console.log(this.user); 
        const payload = { ...this.user, role: "admin" };
        const token = localStorage.getItem("token");
        const response = await axios.post("http://localhost:8000/api/admin/register", payload, {
          headers: { Authorization: token ? `Bearer ${token}` : "",
              'Content-Type': 'application/json'
           },
           withCredentials: true
        });
        this.alert = { message: " Administrcdateur ajout\u00e9 avec succ\u00e8s !", type: "success" };
        setTimeout(() => {
          this.$emit("submit", response.data);
          this.closeModal();
        }, 2000);
      } catch (error) {
        if (error.response && error.response.data) {
          if (error.response.data.errors) {
            const errorMessages = Object.values(error.response.data.errors)
              .flat()
              .map(this.translateErrorMessage)
              .join("\n");
            this.alert = { message:   errorMessages, type: "error" };
          } else if (error.response.data.message) {
            this.alert = { message: " Erreur : " + this.translateErrorMessage(error.response.data.message), type: "error" };
          } else {
            this.alert = { message: " Une erreur s'est produite lors de l'enregistrement", type: "error" };
          }
        } else {
          this.alert = { message: " Une erreur inattendue s'est produite", type: "error" };
        }
      }
    },
    closeModal() {
      this.alert = { message: "", type: "" };
      this.$emit("close");
    },
  },
  watch: {
    isOpen(newVal) {
      if (newVal) {
        this.user = this.userData 
          ? { ...this.userData } 
          : { nom: "", prenom: "",telephone:"", email: "", password: "", status: "active" };
        this.alert = { message: "", type: "" };
      }
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content {
  background: white;
  padding: 25px;
  border-radius: 10px;
  width: 450px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}
.modal-content h3 {
  margin-bottom: 15px;
  font-size: 20px;
  text-align: center;
  color: #333;
}
.modal-content input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}
.input-group {
  display: flex;
  gap: 10px;
}
.input-group input {
  width: 50%;
}
.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
}
.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s;
}
.btn.primary {
  background-color: #28a745;
  color: white;
}
.btn.primary:hover {
  background-color: #218838;
}
.btn.secondary {
  background-color: #dc3545;
  color: white;
}
.btn.secondary:hover {
  background-color: #c82333;
}
.alert {
  margin: 10px 0;
  padding: 10px;
  border-radius: 5px;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}
.alert.success {
  background-color: #d4edda;
  color: #155724;
}
.alert.error {
  background-color: #f8d7da;
  color: #721c24;
}
</style>
