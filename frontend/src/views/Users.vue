<template>
  <div>
    <TopBar />
    <router-view />
  </div>
    <div>
      <UserList :users="users" @edit-user="openModal" @delete-user="deleteUser" />
    </div>
  </template>
  
  <script>
  import TopBar from '@/components/TopBar.vue';
  import { ref } from 'vue';
  import UserList from '../components/UserList.vue';
  
  export default {
    components: { UserList, TopBar },
    setup() {
      const users = ref([
        { id: 1, name: "Assia", email: "assia.com" },
        { id: 2, name: "Linda", email: "linda@example.com" }
      ]);
      const showModal = ref(false);
      const selectedUser = ref(null);
  
      const openModal = (user) => {
        selectedUser.value = user;
        showModal.value = true;
      };
  
      const closeModal = () => {
        showModal.value = false;
        selectedUser.value = null;
      };
  
      const saveUser = (user) => {
        if (user.id) {
          const index = users.value.findIndex(u => u.id === user.id);
          if (index !== -1) users.value[index] = user;
        } else {
          users.value.push({ id: Date.now(), ...user });
        }
        closeModal();
      };
  
      const deleteUser = (id) => {
        users.value = users.value.filter(user => user.id !== id);
      };
  
      return { users, showModal, selectedUser, openModal, closeModal, saveUser, deleteUser };
    }
  };
  </script>
<style>
table tr td{
    vertical-align: middle;
}

td button{
    margin: 5px;
}

td button i{
    font-size: 20px;
}


.modal-header{
    background: #0d6efd;
    color: #fff;
}

.modal-body form {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0;
}

.modal-body form .imgholder{
    width: 200px;
    height: 200px;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
}

.imgholder .upload{
    position: absolute;
    bottom: 0;
    left: 10;
    width: 100%;
    height: 100px;
    background: rgba(0,0,0,0.3);
    display: none;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.upload i{
    color: #fff;
    font-size: 35px;
}

.imgholder:hover .upload{
    display: flex;
}

.imgholder .upload input{
    display: none;
}

.modal-body form .inputField{
    flex-basis: 68%;
    border-left: 5px groove blue;
    padding-left: 20px;
}

form .inputField > div{
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

form .inputField > div label{
    font-size: 20px;
    font-weight: 500;
}

#userForm form .inputField > div label::after{
    content: "*";
    color: red;
}

form .inputField > div input{
    width: 75%;
    padding: 10px;
    border: none;
    outline: none;
    background: transparent;
    border-bottom: 2px solid blue;
}

.modal-footer .submit{
    font-size: 18px;
}


#readData form .inputField > div input{
    color: #000;
    font-size: 18px;
}
</style>  