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
    <div class="main" :class="{ active: isCollapsed }">
      <router-view />
    </div>
  </div>
</template>


<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

export default {
  name: "DashboardAdmin",
  setup() {
    const router = useRouter();
    const isCollapsed = ref(false);
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
    return { isCollapsed, toggleSidebar, logout };
  }
};
</script>


<style>
:root {
  --white: #ffffff;
  --blue: #0d0a18;
}

body {
  min-height: 100vh;
  overflow-x: hidden;
  display: flex;
}

.container {
  display: flex;
  width: 100%;
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

.menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.menu li {
  padding: 10px;
  border-radius: 30px;
  transition: background 0.3s ease, transform 0.3s ease;
  position: relative;
}

.menu li:hover,
.menu li.active {
  background-color: var(--white);
  transform: translateX(10px);
}

.menu li:hover::before {
  content: '';
  position: absolute;
  left: -10px;
  top: 50%;
  width: 20px;
  height: 20px;
  background: var(--white);
  border-radius: 50%;
  transform: translateY(-50%);
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

.menu-item {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: var(--white);
}

.menu li:hover .menu-item,
.menu li.active .menu-item {
  color: var(--blue);
}

.icon {
  font-size: 1.75rem;
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
  flex-grow: 1;
  transition: margin-left 0.3s ease;
}

.main.active {
  margin-left: 80px;
}

.button.logout {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #bd1e0c;
  color: white;
  border: none;
  padding: 5px 60px;
  border-radius: 10px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.button.logout:hover {
  background-color: #991607;
  transform: scale(1.05);
}

.button.logout .icon {
  margin-right: 20px;
  font-size: 40px;
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
  }}
  
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
  transform: rotate(180deg);
}
</style>