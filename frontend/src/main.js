import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import axios from 'axios';
import router from './router';
axios.defaults.baseURL = 'http://127.0.0.1:8000/api'; // L'API Laravel

const app = createApp(App);

app.use(router); // Utiliser Vue Router
app.mount('#app');
