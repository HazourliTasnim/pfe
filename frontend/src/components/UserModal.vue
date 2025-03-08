<template>
    <div class="modal-overlay">
      <div class="modal">
        <h3>{{ mode === 'add' ? 'Ajouter un utilisateur' : 'Modifier un utilisateur' }}</h3>
        <form @submit.prevent="submitForm">
          <label>Nom :</label>
          <input type="text" v-model="formData.name" required />
  
          <label>Email :</label>
          <input type="email" v-model="formData.email" required />
  
          <label>Rôle :</label>
          <select v-model="formData.role" required>
            <option value="admin">Admin</option>
            <option value="entreprise">Entreprise</option>
            <option value="soumissionnaire">Soumissionnaire</option>
          </select>
  
          <button type="submit">{{ mode === 'add' ? 'Ajouter' : 'Modifier' }}</button>
          <button type="button" @click="$emit('close')">Annuler</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, watch, defineProps, defineEmits } from 'vue';
  
  export default {
    props: {
      user: Object, 
      mode: String, 
    },
    emits: ['close'],
    setup(props, { emit }) {
      const formData = ref({
        name: '',
        email: '',
        role: 'soumissionnaire',
      });
  
      // Remplir le formulaire si on est en mode "edit"
      watch(() => props.user, (newUser) => {
        if (newUser) {
          formData.value = { ...newUser };
        } else {
          formData.value = { name: '', email: '', role: 'soumissionnaire' };
        }
      }, { immediate: true });
  
      const submitForm = async () => {
        if (props.mode === 'add') {
          await fetch('/api/users', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData.value),
          });
        } else if (props.mode === 'edit') {
          await fetch(`/api/users/${props.user.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData.value),
          });
        }
        emit('close');
      };
  
      return { formData, submitForm };
    },
  };
  </script>
  
  <style>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .modal {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
  }
  .modal form {
    display: flex;
    flex-direction: column;
  }
  .modal label {
    margin-top: 10px;
  }
  .modal button {
    margin-top: 10px;
  }
  </style>
  