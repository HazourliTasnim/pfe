<template>
  <header class="header">
    <div class="logo">
      <img src="@/assets/logo-safqatic.png" alt="Logo" />
      
      <h1>Portail des appels d’offres du secteur de la Poste et des Télécommunications</h1>
    
    </div>
   
    <nav class="nav">
      <router-link class="btn" to="/">Accueil</router-link>
      <router-link to="/dashboard-admin" v-if="user">Dashboard</router-link>
      <button class="btn" @click="showLoginModal = true" v-if="!user">Se connecter</button>
      <router-link to="/register" v-if="!user">S'inscrire</router-link>
      <button class="btn" @click="logout = true" v-if="user">deconnecter</button>
    </nav>
 
  </header>
  </br>
  <div class="container-nav-logos">
  <button class="scroll-btn left" @click="scrollLeft">
    <img src="@/assets/chevron-gauche.png" alt="Left" />


  </button>

  <div class="nav-logos" ref="logosContainer">
    <a v-for="(logo, index) in logos" :key="index" href="#">
      <img :src="logo.src" :alt="logo.alt">
    </a>
  </div>

  <button class="scroll-btn right" @click="scrollRight">
    <img src="@/assets/chevron-droit.png" alt="Right" />
  </button>
</div>
<Login 
  :showModal="showLoginModal" 
  @close="showLoginModal = false" 
  @login-success="handleLoginSuccess" 
/>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import Login from '@/components/Login.vue';

const router = useRouter();
const user = ref(null);
const showLoginModal = ref(false); // ✅ Contrôle l'affichage du modal

const logosContainer = ref(null);

const scrollLeft = () => {
  if (logosContainer.value) {
    logosContainer.value.scrollBy({ left: -200, behavior: 'smooth' });
  }
};

const scrollRight = () => {
  if (logosContainer.value) {
    logosContainer.value.scrollBy({ left: 200, behavior: 'smooth' });
  }
};
const handleLoginSuccess = (loggedInUser) => {
  user.value = loggedInUser; // 🔹 Stocker l'utilisateur
  showLoginModal.value = false; // 🔹 Fermer le modal
};

// Récupérer l'utilisateur connecté
onMounted(async () => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      const response = await api.get('/user', {
        headers: { Authorization: `Bearer ${token}` }
      });
      user.value = response.data;
    } catch (error) {
      user.value = null;
    }
  }
});


// Déconnexion
const logout = async () => {
  try {
    const token = localStorage.getItem('token');
    if (token) {
      await api.post('/logout', {}, {
        headers: { Authorization: `Bearer ${token}` }
      });
    }

    // 🔹 Supprimer le token et réinitialiser l'utilisateur
    localStorage.removeItem('token');
    user.value = null;

    // 🔹 Rediriger vers la page d'accueil
    router.push('/');
  } catch (error) {
    console.error('Erreur de déconnexion', error);
  }


};
const logos = [
  { src: new URL('@/assets/logo-gta.png', import.meta.url).href, alt: 'GTA' },
  { src: new URL('@/assets/logo-at.png', import.meta.url).href, alt: 'Algérie Télécom' },
  { src: new URL('@/assets/logo-poste.png', import.meta.url).href, alt: 'Algérie Poste' },
  { src: new URL('@/assets/logo-mobilis.png', import.meta.url).href, alt: 'Mobilis' },
  { src: new URL('@/assets/logo-comintal.png', import.meta.url).href, alt: 'Comintal' },
  { src: new URL('@/assets/logo-saticom.png', import.meta.url).href, alt: 'Saticom' },
  { src: new URL('@/assets/logo-inttic.png', import.meta.url).href, alt: 'INTTIC' },
  { src: new URL('@/assets/logo-numirisation.png', import.meta.url).href, alt: 'Numérisation' },
  { src: new URL('@/assets/logo-anf.png', import.meta.url).href, alt: 'ANF' },
  { src: new URL('@/assets/logo-ats.png', import.meta.url).href, alt: 'ATS' },
  { src: new URL('@/assets/logo-natp.png', import.meta.url).href, alt: 'NATP' },
  { src: new URL('@/assets/logo-inptic.png', import.meta.url).href, alt: 'INPTIC' },
  { src: new URL('@/assets/logo-eci-tic.png', import.meta.url).href, alt: 'ECI-TIC' },
  { src: new URL('@/assets/logo-wb.png', import.meta.url).href, alt: 'WB' },
  { src: new URL('@/assets/logo-netbeopen.png', import.meta.url).href, alt: 'NetBeOpen' },
  { src: new URL('@/assets/logo-ems.png', import.meta.url).href, alt: 'EMS' }
];



</script>

  
  <style scoped>
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #2b1682, #1f1645);
    padding: 10px 20px;
    color: white;
  }
  
  .logo {
    display: flex;
    align-items: center;
    gap:20px;
    
  }
  .logo h1 {
    font-size:30px;
  }
  .logo img {
    height: 40px;
    margin-right: 10px;
  }
  
  .nav {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  .btn{
  
  border-radius: 8px;
  border: 1px solid transparent;
  padding: 0.6em 1.2em;
  font-size: 1em;
  font-weight: 500;
  font-family: inherit;
  cursor: pointer;
  transition: border-color 0.25s;
}
button:hover {
  border-color: #646cff;
}
button:focus,
button:focus-visible {
  outline: 4px auto -webkit-focus-ring-color;
}
  
  .nav a {
    color: white;
    text-decoration: none;
    font-weight: bold;
  }
  
  .nav a:hover {
    text-decoration: underline;
  }
  
  .logout-btn {
    background: #b71c1c;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
  }
  
  .logout-btn:hover {
    background: #7f0000;
  }
 
  .container-nav-logos {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background-color: #f8f9fa;
  border-top: 2px solid #ddd;
  border-bottom: 2px solid #ddd;
  padding: 10px 0;
  position: relative;
}

.nav-logos {
  display: flex;
  gap: 50px;
  overflow-x: hidden; /* Cache la barre de défilement */
  scroll-behavior: smooth;
  max-width: 80%; /* Ajuste la taille pour le scroll */
  padding: 10px;
  white-space: nowrap;
}

.nav-logos img {
  height: 50px;
  width: auto;
  transition: transform 0.3s;
}

.nav-logos img:hover {
  transform: scale(1.1);
}
.scroll-btn img {
  width: 30px; /* Ajuste la taille de l'icône */
  height: auto;
}

.scroll-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
}

.scroll-btn.left {
  left: 10px;
}

.scroll-btn.right {
  right: 16px;
}

.scroll-btn:hover img {
  opacity: 0.7;
}

  </style>
  