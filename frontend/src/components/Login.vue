<template>
  <div v-if="showModal" class="modal-overlay">
    <div class="modal">
      <button @click="$emit('close')" class="close">&times;</button>
      <h2>Authentification</h2>
      <input type="email" placeholder="Email" v-model="email" />
      <input type="password" placeholder="Mot de passe" v-model="password" />
      <div class="form-group robot-checkbox">
        <input type="checkbox" id="notRobot" v-model="isNotRobot" />
        <label for="notRobot">I'm not a robot</label>
      </div>
      <button class="login" @click="login">Connexion</button>

      <p><a href="#">Mot de passe oublié ?</a></p>
      <p><a href="#">Pas encore inscrit ?</a></p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    showModal: Boolean, // Reçu depuis le composant parent
  },
  data() {
    return {
      email: "",
      password: "",
      isNotRobot: false,
    };
  },

  methods: {
    async login() {
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      email: this.email,
      password: this.password
    });

    console.log("Réponse du serveur :", response.data); // 🔹 Debugging

    if (response.data.success) {
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user)); // 🔹 Sauvegarde utilisateur

      const user = response.data.user;
      console.log("Utilisateur connecté :", user); // 🔹 Debugging

      // 🔹 Vérifie que le rôle est bien défini
      if (user && user.role) {
  if (user.role === 'admin') {
    console.log("Redirection vers /dashboard-admin");
    this.$router.push('/dashboard-admin');
  } else if (user.role === 'entreprise') {
    console.log("Redirection vers /dashboard-entreprise");
    this.$router.push('/dashboard-entreprise');
  } else if (user.role === 'soumissionnaire') {
    console.log("Redirection vers /dashboard-soumissionnaire");
    this.$router.push('/dashboard-soumissionnaire');
  } else {
    console.log("Redirection vers /");
    this.$router.push('/');
  }

      } else {
        console.error("Erreur : rôle utilisateur non défini.");
      }
    } else {
      alert('Erreur de connexion : ' + response.data.message);
    }
  } catch (error) {
    console.error('Erreur lors de la connexion:', error);
    alert('Une erreur est survenue.');
  }
}
  }

 
};
</script>


<style>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 30px;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.nav-links button {
  margin-left: 15px;
  color: #0077b6;
  font-weight: bold;
  background: none;
  border: none;
  cursor: pointer;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6); 
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  animation: fadeIn 0.3s ease-in-out;
}

.modal {
  background: linear-gradient(135deg, #ffffff, #f0f0f0);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  position: relative;
  width: 90%;
  max-width: 400px;
  animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideIn {
  from { transform: translateY(-30px); }
  to { transform: translateY(0); }
}


.close {
  position: absolute;
  top: 10px;
  right: 15px;
  background: none;
  border: none;
  font-size: 28px;
  color: #333;
  cursor: pointer;
}

h2 {
  margin-top: 0;
  text-align: center;
  font-family: 'Roboto', sans-serif;
  color: #333;
}


input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 16px;
  box-sizing: border-box;
  transition: border-color 0.3s ease;
}
input[type="email"]:focus,
input[type="password"]:focus {
  border-color: #007BFF;
  outline: none;
}

.form-group.robot-checkbox {
  margin: 15px 0;
  display: flex;
  align-items: center;
}
.form-group.robot-checkbox input[type="checkbox"] {
  transform: scale(1.3);
  margin-right: 10px;
}

button.login {
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #007BFF, #0056b3);
  border: none;
  color: #fff;
  font-size: 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s ease;
}
button.login:hover {
  background: linear-gradient(135deg, #0056b3, #003d80);
}

p {
  margin: 10px 0;
  font-size: 14px;
  text-align: center;
}
p a {
  color: #007BFF;
  text-decoration: none;
  transition: text-decoration 0.3s ease;
}
p a:hover {
  text-decoration: underline;
}
</style>