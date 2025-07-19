<template>
    <div>
      <h2>Users List</h2>
      <ul v-if="users.length">
        <li v-for="user in users" :key="user.id">
          {{ user.name }} - {{ user.email }}
        </li>
      </ul>
      <p v-else>No users found</p>
    </div>
  </template>

  <script>
  import api from '@/services/api'

  export default {
    name: 'Users',
    data() {
      return {
        users: []
      }
    },
    async mounted() {
      try {
        const response = await api.get('/users')
        this.users = response.data
      } catch (error) {
        console.error('Error fetching users:', error)
      }
    }
  }
  </script>

  <style scoped>
  /* Add your styles here */
  </style>