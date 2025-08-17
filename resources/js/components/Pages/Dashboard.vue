<template>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">Boilerplate</div>
      <nav class="nav">
        <ul>
          <li class="nav-item active">
            <i class="fas fa-users"></i>
            <span>Users</span>
          </li>
          <li class="nav-item">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
          </li>
          <li class="nav-item">
            <i class="fas fa-comments"></i>
            <span>Chat</span>
          </li>
        </ul>
      </nav>
      <div class="logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Topbar -->
      <div class="topbar">
        <div class="profile">
          <i class="fas fa-user-circle"></i>
          <span>Welcome, User Name</span>
        </div>
      </div>

      <!-- Content Header -->
      <div class="content-header">
        <h1>Users</h1>
        <div class="actions">
          <select v-model="filter" class="filter-select" @change="fetchUsers">
            <option value="">Select Filter</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <input
            v-model="search"
            placeholder="Search Users"
            class="search-input"
            @input="fetchUsers"
          />
          <button class="add-user-btn">
            <i class="fas fa-plus"></i> Add User
          </button>
        </div>
      </div>

      <!-- User Table -->
      <table class="users-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Last Active</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.lastActive }}</td>
            <td>
              <span
                :class="user.status === 'active' ? 'status-dot active' : 'status-dot inactive'"
              ></span>
              {{ user.status.charAt(0).toUpperCase() + user.status.slice(1) }}
            </td>
            <td>...</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <button @click="prevPage" :disabled="page === 1">←</button>
        <button
          v-for="n in totalPages"
          :key="n"
          @click="goToPage(n)"
          :class="{ active: page === n }"
        >
          {{ n }}
        </button>
        <button @click="nextPage" :disabled="page === totalPages">→</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// State variables
const users = ref([]);
const filter = ref('');
const search = ref('');
const page = ref(1);
const totalPages = ref(1);

// Fetch users from the API
const fetchUsers = async () => {
  try {
    const response = await axios.get('/api/users', {
      params: {
        filter: filter.value,
        search: search.value,
        page: page.value,
      },
    });

    // Update users and pagination data
    users.value = response.data.data;
    totalPages.value = response.data.last_page;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

// Pagination methods
const prevPage = () => {
  if (page.value > 1) {
    page.value--;
    fetchUsers();
  }
};

const nextPage = () => {
  if (page.value < totalPages.value) {
    page.value++;
    fetchUsers();
  }
};

const goToPage = (n) => {
  page.value = n;
  fetchUsers();
};

// Fetch users on component mount
onMounted(fetchUsers);
</script>

<style scoped>
/* General Styles */
body {
  font-family: 'Inter', Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f5f6fa;
  color: #333;
}

.dashboard-container {
  display: flex;
  height: 100vh;
}

/* Sidebar */
.sidebar {
  font-family: 'Inter', Arial, sans-serif;
  width: 250px;
  background-color: #2c3e50;
  color: white;
  display: flex;
  flex-direction: column;
  padding: 20px 0;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}

.nav {
  flex: 1;
}

.nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.nav-item i {
  margin-right: 10px;
}

.nav-item:hover,
.nav-item.active {
  background-color: #34495e;
}

.logout {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  cursor: pointer;
  border-top: 1px solid #34495e;
}

.logout i {
  margin-right: 10px;
}

/* Main Content */
.main-content {
    font-family: 'Inter', Arial, sans-serif;
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 20px;
}

.topbar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin-bottom: 20px;
}

.topbar .profile {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1rem;
  color: #555;
}

.content-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.content-header h1 {
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
}

.actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-select,
.search-input {
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}

.add-user-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
}

.add-user-btn:hover {
  background-color: #0056b3;
}

/* Table */
.users-table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.users-table th,
.users-table td {
  padding: 12px 15px;
  text-align: left;
}

.users-table th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.users-table tr:not(:last-child) {
  border-bottom: 1px solid #ddd;
}

.status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-right: 8px;
}

.status-dot.active {
  background-color: #28a745;
}

.status-dot.inactive {
  background-color: #dc3545;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 5px;
  margin-top: 20px;
}

.pagination button {
  background-color: white;
  border: 1px solid #ccc;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.pagination button:hover {
  background-color: #007bff;
  color: white;
}

.pagination button:disabled {
  background-color: #f2f2f2;
  cursor: not-allowed;
}

.pagination button.active {
  background-color: #007bff;
  color: white;
}
</style>
