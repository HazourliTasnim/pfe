<template>
  <header class="header">
    <div class="logo">
      <img src="@/assets/logo-safqatic.png" alt="Logo" />
      
      <h1>Portail des appels d’offres du secteur de la Poste et des Télécommunications</h1>
    
    </div>
    <nav class="nav">
      <router-link to="/dashboard" v-if="user">Dashboard</router-link>
      <button class="btn" @click="showLoginModal = true" v-if="!user">Se connecter</button>
      <router-link to="/register" v-if="!user">S'inscrire</router-link>
      <button @click="logout" v-if="user" class="logout-btn">Déconnexion</button>

 <!-- Sélecteur de langue -->
 <div class="language-switcher">
        <select v-model="selectedLanguage" @change="changeLanguage">
          <option value="fr">Français</option>
          <option value="ar">العربية</option>
        </select>
      </div>

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
  background: linear-gradient(135deg, #1b2d5f, #3f5280); /* Bleu foncé élégant */
  padding: 15px 25px;
  color: #ffffff;
  border-bottom: 3px solid #2563eb; /* Bleu moyen pour un accent */
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.logo {
  display: flex;
  align-items: center;
  gap: 15px;
  cursor: pointer;
}

.logo h1 {
  font-size: 22px;
  font-weight: bold;
  color: #ffffff;
  transition: color 0.3s;
}

.logo h1:hover {
  color: #93c5fd; /* Bleu clair élégant au survol */
}

.logo img {
  height: 45px;
  transition: transform 0.3s ease-in-out;
}

.logo img:hover {
  transform: scale(1.1);
}

.nav {
  display: flex;
  align-items: center;
  gap: 20px;
}

.nav a {
  color: #ffffff;
  text-decoration: none;
  font-weight: bold;
  transition: color 0.3s;
}

.nav a:hover {
  color: #93c5fd; /* Bleu clair */
  text-decoration: none;
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

.language-selector {
  display: flex;
  gap: 10px;
  align-items: center;
}

.language-switcher select {
  padding: 5px;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
}
@media (max-width: 768px) {
  .menu-toggle {
    display: block;
  }

  .nav {
    position: absolute;
    top: 60px;
    right: 0;
    background: #1b2d5f;
    flex-direction: column;
    width: 200px;
    display: none;
    padding: 10px;
    border-radius: 5px;
  }

  .nav-active {
    display: flex;
  }
}
  </style>  