import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import axios from 'axios';
import router from './router';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUserPlus, faUserShield, faEdit, faTrash, faCircleChevronLeft, faCircleChevronRight } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
axios.defaults.baseURL = 'http://127.0.0.1:8000/api'; // L'API Laravel

library.add(faUserPlus, faUserShield, faEdit, faTrash, faCircleChevronLeft, faCircleChevronRight);

const app = createApp(App);
app.component('font-awesome-icon', FontAwesomeIcon);
app.use(router); // Utiliser Vue Router
app.mount('#app');
