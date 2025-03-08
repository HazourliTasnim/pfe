<template>
    <div class="topbar">
      <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
      </div>
  
      <div class="search">
        <label>
          <input type="text" placeholder="Rechercher..." />
          <ion-icon name="search-outline"></ion-icon>
        </label>
      </div>
  
      <div class="user-menu">
        <div class="user-icon" @click="toggleMenu">
          <ion-icon name="person-circle-outline"></ion-icon>
        </div>
        <transition name="fade">
            <div v-if="showMenu" class="dropdown-menu">
  <p @click="goToProfile">
    <ion-icon name="person-outline"></ion-icon> Mon Profil
  </p>
  <p @click="logout">
    <ion-icon name="log-out-outline"></ion-icon> Déconnexion
  </p>
</div>
        </transition>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  
  export default {
    setup() {
      const showMenu = ref(false);
      const router = useRouter();
  
      const toggleMenu = () => {
        showMenu.value = !showMenu.value;
      };
  
      const goToProfile = () => {
        router.push('/dashboard-admin/profile');
        showMenu.value = false;
      };
  
      const logout = () => {
        alert('Déconnexion...');
        showMenu.value = false;
      };
  
      return { showMenu, toggleMenu, goToProfile, logout };
    }
  };
  </script>
  
  <style scoped>
  .topbar {
    position: fixed;
    top: 0;
    left: 250px; 
    width: calc(100% - 250px); 
    height: 60px;
    background: #ffffff;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 15px;
    z-index: 1000;
    transition: left 0.3s ease, width 0.3s ease;
  }
  
  .sidebar-collapsed .topbar {
    left: 70px;
    width: calc(100% - 80px);
  }
  
  .toggle {
    font-size: 24px;
    cursor: pointer;
    transition: color 0.3s ease;
  }
  
  .toggle:hover {
    color: #007bff;
  }
  
.search {
  display: flex;
  align-items: center;
  background: #f4f4f4;
  border-radius: 6px;
  padding: 4px 12px;
  width: 230px;
}

.search label {
  display: flex;
  align-items: center;
  width: 100%;
}

.search input {
  border: none;
  outline: none;
  background: transparent;
  font-size: 14px;
  padding: 5px;
  flex-grow: 1;  
  min-width: 0;  
}

.search ion-icon {
  font-size: 18px;
  color: #555;
  margin-left: 10px;
}  
  .user-menu {
    display: flex;
    align-items: center;
    position: relative;
    margin-right: 10px; 
  }
  
  .user-icon {
    font-size: 35px; 
    color: #333;
    cursor: pointer;
    transition: color 0.3s ease;
    margin-left: -60px; 
  }
  
  .user-icon:hover {
    color: #007bff;
  }
  
  .dropdown-menu {
    position: absolute;
    top: 45px;
    right: 0;
    background: #ffffff;
    color:#333;
    border-radius: 6px;
    box-shadow: 0px 4px 10px rgba(43, 10, 133, 0.15);
    padding: 8px;
    width: 120px;
  }
  
  .dropdown-menu p {
    padding: 8px;
    margin: 0;
    font-size: 13px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .dropdown-menu p:hover {
    background: #0c3766;
    color: white;
    border-radius: 4px;
  }
  
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>
  