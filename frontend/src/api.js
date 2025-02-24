import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api', // Remplace par l’URL de ton backend
  headers: {
    'Content-Type': 'application/json'
  }
});

// Intercepteur pour inclure le token automatiquement
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
import { defineStore } from 'pinia';

export const useUserStore = defineStore('userStore', {
  state: () => ({
    users: [],
  }),
  actions: {
    async fetchUsers() {
      try {
        const response = await axios.get('https://api.example.com/users');
        this.users = response.data;
      } catch (error) {
        console.error('Erreur lors du chargement des utilisateurs', error);
      }
    },
    async addUser(user) {
      try {
        const response = await axios.post('https://api.example.com/users', user);
        this.users.push(response.data);
      } catch (error) {
        console.error('Erreur lors de l’ajout de l’utilisateur', error);
      }
    },
    async updateUser(user) {
      try {
        await axios.put(`https://api.example.com/users/${user.id}`, user);
        const index = this.users.findIndex(u => u.id === user.id);
        if (index !== -1) this.users[index] = user;
      } catch (error) {
        console.error('Erreur lors de la mise à jour de l’utilisateur', error);
      }
    },
    async deleteUser(id) {
      try {
        await axios.delete(`https://api.example.com/users/${id}`);
        this.users = this.users.filter(user => user.id !== id);
      } catch (error) {
        console.error('Erreur lors de la suppression de l’utilisateur', error);
      }
    }
  }
});
