<template>
  <div class="profile-container">
    <div class="profile-card">
      <div class="avatar-container">
        <i class="fas fa-user-circle user-icon"></i>
      </div>
      <h2 class="profile-name">{{ user.name }}</h2>
      <p class="profile-role">{{ user.role }}</p>
      <p class="profile-email">{{ user.email }}</p>
    </div>

    <!-- ✅ Statistiques du profil -->
    <div class="stats-container">
      <div class="stat-card">
        <i class="fas fa-file-alt stat-icon"></i>
        <h3>{{ stats.totalAppels }}</h3>
        <p>Appels d'offres publiés</p>
      </div>
      <div class="stat-card">
        <i class="fas fa-user-check stat-icon"></i>
        <h3>{{ stats.totalCandidatures }}</h3>
        <p>Candidatures reçues</p>
      </div>
      <div class="stat-card">
        <i class="fas fa-eye stat-icon"></i>
        <h3>{{ stats.totalVues }}</h3>
        <p>Répartition des appels d'offres </p>
      </div>
      <div class="stat-card">
        <i class="fas fa-calendar stat-icon"></i>
        <h3>{{ stats.lastLogin }}</h3>
        <p>Nombre d'appels d'offres avec gestion par lots</p>
      </div>
    </div>

    <!-- ✅ Graphique d'évolution des appels d'offres -->
    <div class="chart-container">
      <h3>Statistiques des appels d'offres</h3>
      <canvas id="offerChart"></canvas>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive } from "vue";
import Chart from "chart.js/auto";
import axios from "axios";

export default {
  setup() {
    const user = reactive({
      name: "User",
      email: "",
      role: ""
    });

    const stats = reactive({
      totalAppels: 0,
      totalCandidatures: 0,
      totalVues: 0,
      lastLogin: "N/A",
      chartData: []
    });

    // ✅ Charger les statistiques depuis Laravel API
    const fetchStats = async () => {
      try {
        const response = await axios.get("/api/admin/stats");
        Object.assign(stats, response.data);
      } catch (error) {
        console.error("Erreur de chargement des stats", error);
      }
    };

    // ✅ Générer le graphique avec Chart.js
    const generateChart = () => {
      const ctx = document.getElementById("offerChart");
      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin"],
          datasets: [
            {
              label: "Appels d'offres",
              data: stats.chartData,
              backgroundColor: "rgba(54, 162, 235, 0.2)",
              borderColor: "rgba(54, 162, 235, 1)",
              borderWidth: 2
            }
          ]
        }
      });
    };

    onMounted(async () => {
      await fetchStats();
      generateChart();
    });

    return { user, stats };
  }
};
</script>

<style scoped>
/* ✅ Style pour les stats */
.stats-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
  margin-top: 20px;
}

.stat-card {
  background: white;
  color: #1e3c72;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
}

.stat-card:hover {
  transform: scale(1.05);
}

.stat-icon {
  font-size: 30px;
  margin-bottom: 10px;
  color: #2a5298;
}

/* ✅ Graphique */
.chart-container {
  margin-top: 20px;
  background: white;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}
</style>
