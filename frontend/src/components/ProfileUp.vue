<template>
  <div class="profile-container">
    <div class="profile-card">
      <h2>Mon Profil</h2>

      <div class="avatar-container">
        <img :src="user.avatar || defaultAvatar" alt="Avatar" class="avatar">
        <button @click="triggerFileInput" class="change-btn">📷</button>
      </div>

      <div class="input-group">
        <label>Nom</label>
        <input v-model="user.name" type="text">
      </div>

      <div class="input-group">
        <label>Email</label>
        <input v-model="user.email" type="email" >
      </div>
      <div class="input-group">
        <label>Mot de passe</label>
        <input v-model="user.password" type="text">
      </div>
      <div class="input-group">
        <label>Confirmer mot de passe</label>
        <input v-model="user.password" type="text">
      </div>
      <button @click="updateProfile" class="save-btn">Enregistrer</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const user = ref({ name: "", email: "", avatar: "" });
const defaultAvatar = "https://via.placeholder.com/100"; 

const fetchProfile = async () => {
  try {
    const response = await axios.get("/api/user");
    user.value = response.data;
  } catch (error) {
    console.error("Erreur lors du chargement du profil", error);
  }
};

const updateProfile = async () => {
  try {
    await axios.put("/api/user", { name: user.value.name });
    alert("Profil mis à jour !");
  } catch (error) {
    console.error("Erreur lors de la mise à jour", error);
  }
};

const fileInput = ref(null);
const triggerFileInput = () => fileInput.value.click();

const uploadImage = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append("avatar", file);

  try {
    const response = await axios.post("/api/user/avatar", formData, {
      headers: { "Content-Type": "multipart/form-data" }
    });
    user.value.avatar = response.data.avatar;
  } catch (error) {
    console.error("Erreur lors du téléchargement de l'image", error);
  }
};

onMounted(fetchProfile);
</script>

<style scoped>

.profile-card {
  background: rgba(17, 7, 46, 0.2);
  padding: 20px;
  border-radius: 15px;
  width: 350px;
  text-align: center;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease;
}

.profile-card:hover {
  transform: scale(1.02);
}

h2 {
  font-size: 24px;
  color: #333;
}

.avatar-container {
  position: relative;
  display: flex;
  justify-content: center;
  margin: 20px 0;
}

.avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid white;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.change-btn {
  position: absolute;
  bottom: 0;
  right: 5px;
  background: linear-gradient(135deg, #654f79, #9264e7);
  border: none;
  color: white;
  padding: 8px;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.change-btn:hover {
  transform: scale(1.1);
}

.input-group {
  text-align: left;
  margin-bottom: 15px;
}

label {
  font-size: 14px;
  font-weight: bold;
  color: #444;
  display: block;
  margin-bottom: 5px;
}

input {
  width: 90%;
  padding: 10px;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.3s ease;
}

input:focus {
  border-color: #1e3850;
  outline: none;
}



.save-btn {
  width: 100%;
  padding: 12px;
  background:#654f79;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.save-btn:hover {
  transform: scale(1.05);
}
</style>
