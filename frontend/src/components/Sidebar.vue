<template>
  <div class="container">
    <div class="navigation">
      <div :class="['sidebar', { collapsed: isCollapsed }]">
        <div class="logo-container" v-if="!isCollapsed">
          <img src="@/assets/logo-safqatic.png" alt="Algérie Télécom Logo" class="logo" />
        </div>
        <nav>
          <ul class="menu">
            <li :class="{ active: $route.path === '/dashboard-admin' }">
              <router-link to="/dashboard-admin" class="menu-item">
                <span class="icon">
                  <ion-icon name="home-outline"></ion-icon>
                </span>
                <span class="label" v-if="!isCollapsed">Dashboard</span>
              </router-link>
            </li>
            <li :class="{ active: $route.path === '/dashboard-admin/users' }">
              <router-link to="/dashboard-admin/users" class="menu-item">
                <span class="icon">
                  <ion-icon name="people-outline"></ion-icon>
                </span>
                <span class="label" v-if="!isCollapsed">Utilisateurs</span>
              </router-link>
            </li>
            <li :class="{ active: $route.path === '/dashboard-admin/Entreprise' }">
              <router-link to="/dashboard-admin/Entreprise" class="menu-item">
                <span class="icon">
                  <ion-icon name="business-outline"></ion-icon>
                </span>
                <span class="label" v-if="!isCollapsed">Entreprises</span>
              </router-link>
            </li>
            <li :class="{ active: $route.path === '/dashboard-admin/profile' }">
              <router-link to="/dashboard-admin/profile" class="menu-item">
                <span class="icon">
                  <ion-icon name="settings-outline"></ion-icon>
                </span>
                <span class="label" v-if="!isCollapsed">Profil</span>
              </router-link>
            </li>
            <li :class="{ active: $route.path === '/dashboard-admin/appel-offre-form' }">
              <router-link to="/dashboard-admin/appel-offre-form" class="menu-item">
                <span class="icon">
                  <ion-icon name="document-text-outline"></ion-icon>
                </span>
                <span class="label" v-if="!isCollapsed">Appel Offres</span>
              </router-link>
            </li>
          </ul>
        </nav>
        <button @click="logout" class="button logout">
          <span class="icon">
            <ion-icon name="log-out-outline"></ion-icon>
          </span>
          <transition name="fade-slide">
            <span class="label" v-if="!isCollapsed">Se déconnecter</span>
          </transition>
        </button>
        <div class="toggle-btn" @click="toggleSidebar">
          <ion-icon :name="isCollapsed ? 'chevron-forward-outline' : 'chevron-back-outline'" class="toggle-icon"></ion-icon>
        </div>
      </div>
    </div>

    <!-- Loader si la page charge -->
    <div v-if="isLoading" class="loading-container">
      <div class="loader"></div>
    </div>

    <div class="main" :class="{ active: isCollapsed }" v-else>
      <router-view />
    </div>
  </div>
</template>


<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

export default {
  name: "DashboardAdmin",
  setup() {
    const router = useRouter();
    const isCollapsed = ref(false);
    const isLoading = ref(true); 

    const toggleSidebar = () => {
      isCollapsed.value = !isCollapsed.value;
    };

    const logout = async () => {
      try {
        await api.post('/logout');
        localStorage.removeItem('token');
        router.push('/');
      } catch (error) {
        console.error('Erreur lors de la déconnexion', error);
      }
    };

    // Simulation du chargement des données
    onMounted(() => {
      setTimeout(() => {
        isLoading.value = false;
      }, 2000); // Supposons que les données prennent 2 secondes à charger
    });

    return { isCollapsed, toggleSidebar, logout, isLoading };
  }
};
</script>



<style>
:root {
  --white: #efefef;
  --blue: #0d0a18;
  --primary: #4a6ee0; /* Couleur d'accent */
}

.navigation {
  position: fixed;
  left: 0;
  top: 0;
  width: 250px;
  height: 100vh;
  background: #0c1d4d;
  transition: width 0.3s ease;
  overflow: hidden;
  z-index: 1000;
}

.sidebar.collapsed {
  width: 80px;
}

.logo-container {
  text-align: center;
  padding: 15px;
}

.logo {
  width: 150px;
  height: auto;
}

.menu li {
  display: flex;
  align-items: center;
  gap: 10px;
 margin-right: 10px;
  border-radius: 100px;
  transition:  transform 0.2s ease, box-shadow 0.2s ease;
  position: relative;
}


.menu li:hover,
.menu li.active {
  background-color: var(--white);
  padding: 6px;
  transform: translateX(10px);
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}


.menu li.active::before {
  content: "";
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 5px;
  background-color: var(--primary);
  border-radius: 50%;
}


.menu li:hover .menu-item,
.menu li.active .menu-item {
  color: #0c1d4d !important;

}
.menu {
  display: flex;
  flex-direction: column;
  gap: 10px; 
}


.menu-item {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: var(--white);
  font-size: 16px;
  padding: 5px;
  width: 100%;
  transition: color 0.3s ease;
}


.menu-item .icon {
  font-size: 1rem;
  min-width: 40px;
  text-align: center;
  transition: color 0.3s ease, transform 0.3s ease;
}


.menu li:hover .icon,
.menu li.active .icon {
  color: var(--primary);
  transform: scale(1.1);
}

.navigation ul li:hover a::before,
.navigation ul li.active a::before {
  content: "";
  position: absolute;
  right: 0;
  top: -50px;
  width: 50px;
  height: 50px;
  background-color: transparent;
  border-radius: 50%;
  box-shadow: 35px 35px 0 10px var(--white);
  pointer-events: none;
}

.navigation ul li:hover a::after,
.navigation ul li.active a::after {
  content: "";
  position: absolute;
  right: 0;
  bottom: -50px;
  width: 50px;
  height: 50px;
  background-color: transparent;
  border-radius: 50%;
  box-shadow: 35px -35px 0 10px var(--white);
  pointer-events: none;
}

.icon {
  font-size: 1rem;
  min-width: 40px;
  text-align: center;
}


.toggle-btn {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 24px;
  cursor: pointer;
  background: none;
  border: none;
  color: var(--white);
}
.main {
  margin-left: 250px;
  transition: margin-left 0.3s ease-in-out;
}

.sidebar.collapsed ~ .main {
  margin-left: 80px;
}

.main.active {
  margin-left: 80px;
}


.button.logout {
  background-color: #bd1e0c;
  color: white;
  border: none;
  border-radius: 9px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.button.logout:hover {
  background-color: #991607;
  transform: scale(1.05);
}

.button.logout .icon {
  margin-top: 10px;
margin-right: 10px;
  font-size: 20px;
}

@media (max-width: 480px) {
  .navigation {
    width: 100%;
    left: -100%;
  }
  .navigation.active {
    left: 0;
  }
  .main.active {
    margin-left: 0;
  }
}

.fade-slide-enter-active, .fade-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-slide-enter-from, .fade-slide-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}

.toggle-icon {
  transition: transform 0.3s ease;
}

.sidebar.collapsed .toggle-icon {
  transform: rotate(360deg);
}
.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: rgba(255, 255, 255, 0.8);
}

.loader {
  border: 6px solid #f3f3f3;
  border-top: 10px solid #221d5f;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>
