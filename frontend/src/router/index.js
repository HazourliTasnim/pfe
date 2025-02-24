import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import TenderDetails from "@/components/TenderDetails.vue"; 
import Login from '../components/Login.vue';
import Register from '../views/Register.vue';
import DashboardAdmin from '../views/dashboard-admin.vue';
import DashboardEntreprise from '../views/dashboard-entreprise.vue';
import AppelOffreForm from '../views/AppelOffreForm.vue';
import Dashboard from "@/views/dashboard.vue";
import Users from '@/views/Users.vue'; 
import Profile from '../components/ProfileUp.vue';
const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  
  { 
    path: '/dashboard-admin',
    component: DashboardAdmin,  // Utilisation du Layout ici
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: "", component: Dashboard },  // Par défaut, affiche Dashboard
      { path: "users", component: Users },
      { path: 'profile', component: Profile},
      { path: "appel-offre-form", component: AppelOffreForm }
    ]
  },

  { 
    path: '/dashboard-entreprise',
    component: DashboardEntreprise,
    meta: { requiresAuth: true, role: 'entreprise' } 
  },
  { 
    path: "/details/:id",
    name: "TenderDetails",
    component: TenderDetails,
    props: true, 
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Vérification avant chaque navigation
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user'));

  if (to.meta.requiresAuth) {
    if (!token) {
      return next('/login'); 
    }
    if (to.meta.role && user?.role !== to.meta.role) {
      return next('/'); 
    }
  }

  next();
});

export default router;
