import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import TenderDetails from "@/components/TenderDetails.vue"; 
import Login from '../components/Login.vue';
import Register from '../views/Register.vue';
import DashboardAdmin from '../views/dashboard-admin.vue';
import DashboardEntreprise from '../views/dashboard-entreprise.vue';
import Dashboardsoumissionnaire from '../views/dashboard-soumissionnaire.vue';
import AppelOffreForm from '../views/AppelOffreForm.vue';
import Dashboard from "@/views/dashboard.vue";
import Users from "@/views/Users.vue";
import Settings from "@/views/Settings.vue";
import ProfileUp from '../components/ProfileUp.vue';
import Entreprise from '../views/Entreprise.vue';


const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  
  { 
    path: '/dashboard-admin',
    component: DashboardAdmin,  
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: "", component: Dashboard },  
      { path: "Users", component: Users },
      { path: "settings", component: Settings },
      { path: "Entreprise", component: Entreprise},
      { path: "Profile", component: ProfileUp},
      { path: "appel-offre-form", component: AppelOffreForm }
    ]
  },

  { 
    path: '/dashboard-entreprise',
    component: DashboardEntreprise,
    meta: { requiresAuth: true, role: 'entreprise' } 
  },
  { 
    path: '/dashboard-soumissionnaire',
    component: Dashboardsoumissionnaire,
    meta: { requiresAuth: true, role: 'soumissionnaire' } 
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
      return next('/'); 
    }
    if (to.meta.role && user?.role !== to.meta.role) {
      return next('/'); 
    }
  }

  next();
});

export default router;
