<template>
  <div>
    <h1>Users</h1>
    <button @click="showAddUserModal">Add User</button>

    <!-- Add User Modal -->
    <div v-if="isAddUserModalVisible" class="modal">
      <form @submit.prevent="addUser">
        <input v-model="newUser.firstname" placeholder="First Name" required />
        <input v-model="newUser.middlename" placeholder="Middle Name" />
        <input v-model="newUser.lastname" placeholder="Last Name" required />
        <input v-model="newUser.email" type="email" placeholder="Email" required />
        <input v-model="newUser.password" type="password" placeholder="Password" required />
        <button type="submit">Submit</button>
        <button type="button" @click="closeAddUserModal">Cancel</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { createUser } from '../../services/api';

const isAddUserModalVisible = ref(false);
const newUser = ref({
  firstname: '',
  middlename: '',
  lastname: '',
  email: '',
  password: '',
});

const showAddUserModal = () => {
  isAddUserModalVisible.value = true;
};

const closeAddUserModal = () => {
  isAddUserModalVisible.value = false;
};

const addUser = async () => {
  try {
    await createUser(newUser.value);
    alert('User added successfully!');
    closeAddUserModal();
  } catch (error) {
    console.error('Error adding user:', error);
    alert('Failed to add user.');
  }
};
</script>

<style scoped>
/* Add your modal styles here */
</style>
