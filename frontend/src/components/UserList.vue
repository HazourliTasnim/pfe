<template>
    <div class="container">
      <h2>Liste des Utilisateurs</h2>
      <table>

        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Actions</th>
            <th>Statut</th>
            <th>Etat</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <button class="edit-btn" @click="$emit('edit-user', user)"> Modifier</button>
              <button class="delete-btn" @click="$emit('delete-user', user.id)"> Supprimer</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    props: ['users']
  };
  </script>
  
  <style scoped>
  .container {
    padding: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
  }
  .add-btn {
    margin-bottom: 10px;
    padding: 8px;
    background: green;
    color: white;
    border: none;
    cursor: pointer;
  }
  .edit-btn {
    padding: 5px;
    background: blue;
    color: white;
    border: none;
    cursor: pointer;
  }
  .delete-btn {
    padding: 5px;
    background: red;
    color: white;
    border: none;
    cursor: pointer;
  }
  </style>
  