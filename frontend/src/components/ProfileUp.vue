<template>
  <div class="profile-container">
    

    <div class="content-container">
      <!-- 🔹 Cartes Statistiques (Gauche) -->
      <div class="stats-container">
        <div class="stat-card">
          <i class="fas fa-file-alt stat-icon"></i>
          <h3>{{ stats.totalAppels }}</h3>
          <p>Appels d'offres</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-user-check stat-icon"></i>
          <h3>{{ stats.totalCandidatures }}</h3>
          <p>Candidatures reçues</p>
        </div>
        <div class="stat-card">
          <i class="fas fa-eye stat-icon"></i>
          <h3>{{ stats.totalVues }}</h3>
          <p>Délai moyen de traitement d’un appel d’offre  </p><!--temps entre pub et attrib--> 
        </div>
      </div>

      <div class="profile-form">
        <h3>Modifier vos Informations</h3>
        <form @submit.prevent="updateProfile">
          <div class="input-group">
            <i class="fas fa-user icon"></i>
            <input type="text" v-model="user.name" placeholder="Entrez votre nom">
          </div>
          <div class="input-group">
            <i class="fas fa-envelope icon"></i>
            <input type="email" v-model="user.email" placeholder="Entrez votre email">
          </div>
          <div class="input-group">
            <i class="fas fa-lock icon"></i>
            <input type="password" v-model="user.password" placeholder="Nouveau mot de passe">
          </div>
          <button type="submit" class="save-button">Enregistrer</button>
        </form>
      </div>
    </div>

    <div class="chart-container">
      <h3>Statistiques des Appels d'Offres</h3>
      <canvas ref="offerChart"></canvas>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from "vue";
import { Chart, registerables } from "chart.js";
import axios from "axios";

Chart.register(...registerables);

export default {
  setup() {
    const user = reactive({
      name: "Utilisateur",
      email: "user@example.com",
      password: ""
    });

    const stats = reactive({
      totalAppels: 0,
      totalCandidatures: 0,
      totalVues: 0,
      chartData: []
    });

    const offerChart = ref(null);

    const fetchStats = async () => {
      try {
        const response = await axios.get("/api/admin/stats");
        Object.assign(stats, response.data);
        generateChart();
      } catch (error) {
        console.error("Erreur de chargement des stats", error);
      }
    };

    const generateChart = () => {
      if (!offerChart.value) return;
      new Chart(offerChart.value, {
        type: "bar",
        data: {
          labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin"],
          datasets: [
            {
              label: "Appels d'offres",
              data: stats.chartData.length ? stats.chartData : [5, 10, 15, 20, 25, 30],
              backgroundColor: "rgba(54, 162, 235, 0.5)",
              borderColor: "rgba(54, 162, 235, 1)",
              borderWidth: 2
            }
          ]
        }
      });
    };

    const updateProfile = async () => {
      try {
        await axios.put("/api/admin/profile", user);
        alert(" Profil mis à jour avec succès !");
      } catch (error) {
        console.error("Erreur :", error);
      }
    };

    onMounted(fetchStats);

    return { user, stats, offerChart, updateProfile };
  }
};
</script>

<style scoped>
.profile-container {
  max-width: 1000px;
  margin: 80px auto 50px auto;
}

.welcome-section {
  text-align: center;
  margin-bottom: 30px;
  background: #f8f9fa;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

.content-container {
  display: flex;
  justify-content: space-between;
  gap: 30px;
}
.stats-container {
  display: flex;
  flex-direction: column;
  gap: 15px;
  flex: 1;
}

.stat-card {
  background: linear-gradient(135deg, #1e3c72, #2a5298);
  color: white;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease-in-out;
  cursor: pointer;
  min-width: 220px;
}

.stat-card:hover {
  transform: scale(1.05);
  background: linear-gradient(135deg, #2a5298, #1e3c72);
}

.stat-icon {
  font-size: 30px;
  margin-bottom: 10px;
  color: #ffffff;
  transition: transform 0.3s ease-in-out;
}

.stat-card:hover .stat-icon {
  transform: rotate(15deg);
}


.profile-form {
  width: 50%;
  background: white;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

.input-group {
  display: flex;
  align-items: center;
  background: #f8f9fa;
  padding: 12px;
  border-radius: 5px;
  margin-bottom: 15px;
  transition: all 0.3s ease-in-out;
}

.input-group:hover {
  background: #e8f0fe;
}

.input-group .icon {
  font-size: 18px;
  margin-right: 10px;
  color: #2a5298;
}

.input-group input {
  border: none;
  background: none;
  outline: none;
  flex: 1;
  padding: 5px;
  font-size: 16px;
}

.input-group input:focus {
  background: #ffffff;
  border-bottom: 2px solid #2a5298;
}

.save-button {
  width: 100%;
  background: #2a5298;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease-in-out;
}

.save-button:hover {
  background: #1e3c72;
  transform: scale(1.05);
}

.chart-container {
  width: 100%;
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  margin-top: 30px;
}
</style>
