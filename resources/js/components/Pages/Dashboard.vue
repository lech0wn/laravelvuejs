<template>
    <div>
      <div class="flex items-center mb-4">
        <select v-model="filter" class="mr-4"> <!-- Filter Dropdown -->
          <option value="">Select Filter</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <input v-model="search" placeholder="Search Users" class="mr-4" />
        <button @click="showAddUser = true">Add User +</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Last Active</th><th>Status</th><th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.last_active }}</td>
            <td>
              <span :class="user.status === 'active' ? 'text-green-500' : 'text-red-500'">
                ●
              </span>
              {{ user.status.charAt(0).toUpperCase() + user.status.slice(1) }}
            </td>
            <td>...</td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <div class="pagination">
        <button @click="prevPage" :disabled="page === 1">←</button>
        <button v-for="n in totalPages" :key="n" @click="goToPage(n)">{{ n }}</button>
        <button @click="nextPage" :disabled="page === totalPages">→</button>
      </div>
      <!-- Add User Modal -->
      <add-user-modal v-if="showAddUser" @close="showAddUser = false" />
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
//   import AddUserModal from './AddUserModal.vue'

  const users = ref([])
  const filter = ref('')
  const search = ref('')
  const page = ref(1)
  const totalPages = ref(5)
  const showAddUser = ref(false)

  const fetchUsers = async () => {
    // Call your Laravel API here
    // Example: /api/users?filter=active&search=John&page=1
    const response = await axios.get('/api/users', {
      params: { filter: filter.value, search: search.value, page: page.value }
    })
    users.value = response.data.data
    totalPages.value = response.data.last_page
  }

  onMounted(fetchUsers)
  </script>

<style scoped>
body {
  font-family: 'Inter', Arial, sans-serif;
  background: #f5f6fa;
  margin: 0;
  color: #22223b;
}

.dashboard-container {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 220px;
  background: #22223b;
  color: #fff;
  display: flex;
  flex-direction: column;
  padding: 32px 0;
}

.sidebar .logo {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 40px;
  text-align: center;
  letter-spacing: 2px;
}

.sidebar .nav {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.sidebar .nav-item {
  display: flex;
  align-items: center;
  padding: 12px 32px;
  cursor: pointer;
  transition: background 0.2s;
  font-size: 1.1rem;
}

.sidebar .nav-item.active,
.sidebar .nav-item:hover {
  background: #4a4e69;
  border-left: 4px solid #f2e9e4;
}

.sidebar .nav-item i {
  margin-right: 16px;
  font-size: 1.2rem;
}

.sidebar .logout {
  margin-top: auto;
  padding: 12px 32px;
  color: #f2e9e4;
  cursor: pointer;
  border-top: 1px solid #4a4e69;
}

.main-content {
  flex: 1;
  padding: 40px 48px;
  background: #f5f6fa;
  display: flex;
  flex-direction: column;
}

.topbar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin-bottom: 32px;
}

.topbar .profile {
  display: flex;
  align-items: center;
  gap: 12px;
}

.topbar .profile-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #c9ada7;
  object-fit: cover;
}

.topbar .welcome {
  font-size: 1rem;
  color: #4a4e69;
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.header-row .title {
  font-size: 2rem;
  font-weight: 600;
  color: #22223b;
}

.header-row .actions {
  display: flex;
  gap: 16px;
}

.filter-select,
.search-input {
  padding: 8px 12px;
  border: 1px solid #c9ada7;
  border-radius: 6px;
  font-size: 1rem;
  background: #fff;
  outline: none;
}

.add-user-btn {
  background: #4a4e69;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}

.add-user-btn:hover {
  background: #22223b;
}

.users-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(34, 34, 59, 0.04);
}

.users-table th,
.users-table td {
  padding: 16px 12px;
  text-align: left;
}

.users-table th {
  background: #f2e9e4;
  color: #22223b;
  font-weight: 600;
  font-size: 1rem;
}

.users-table tr:not(:last-child) {
  border-bottom: 1px solid #e0e0e0;
}

.status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-right: 8px;
}

.status-dot.active {
  background: #4caf50;
}

.status-dot.inactive {
  background: #e63946;
}

.actions-cell {
  text-align: right;
}

.pagination {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 8px;
  margin-top: 24px;
}

.pagination button {
  background: #fff;
  border: 1px solid #c9ada7;
  color: #22223b;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.pagination button.active,
.pagination button:hover {
  background: #4a4e69;
  color: #fff;
  border-color: #4a4e69;
}

</style>
