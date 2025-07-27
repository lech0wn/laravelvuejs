<template>
  <div class="dashboard">
    <aside :class="['sidebar', { closed: !sidebarOpen }]">
      <button class="toggle-btn" @click="sidebarOpen = !sidebarOpen">
        <div v-if="!sidebarOpen" class="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <span v-else>✕</span>
      </button>
      <div v-if="sidebarOpen" class="sidebar-content">
        <h2>Menu</h2>
        <ul>
          <li>Dashboard</li>
          <li>Users</li>
          <li>Settings</li>
        </ul>
      </div>
    </aside>
    <div class="main-area">
      <!-- HEADER START -->
      <header class="header">
        <div class="site-name">Boilerplate</div>
        <div class="header-actions">
          <div class="notification" @click="showNotifications = !showNotifications">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
              <path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2zm6-6V11c0-3.07-1.63-5.64-5-6.32V4a1 1 0 10-2 0v.68C7.63 5.36 6 7.92 6 11v5l-1.29 1.29A1 1 0 006 19h12a1 1 0 00.71-1.71L18 16z" fill="#333"/>
            </svg>
            <span v-if="notifications.length" class="notif-dot"></span>
            <div v-if="showNotifications" class="notif-dropdown">
              <div v-if="notifications.length">
                <div v-for="(notif, i) in notifications" :key="i" class="notif-item">
                  {{ notif }}
                </div>
              </div>
              <div v-else class="notif-empty">No notifications</div>
            </div>
          </div>
          <div class="profile" @click="showProfileMenu = !showProfileMenu">
            <img class="avatar" src="https://i.pravatar.cc/40?img=3" alt="Profile" />
            <span class="profile-name">Issa</span>
            <svg width="16" height="16" fill="none" viewBox="0 0 20 20">
              <path d="M5.5 8l4.5 4 4.5-4" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div v-if="showProfileMenu" class="profile-dropdown">
              <div class="profile-item">Profile</div>
              <div class="profile-item">Settings</div>
              <div class="profile-item">Logout</div>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->

      <main class="main-content">
        <!-- SEARCH AND ADD USER SECTION -->
        <div class="user-controls">
          <div class="search-section">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search users..."
              class="search-input"
            />
            <svg class="search-icon" width="20" height="20" fill="none" viewBox="0 0 24 24">
              <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <button class="add-user-btn" @click="showAddUserModal = true">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
              <path d="M12 5v14m-7-7h14" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Add User
          </button>
        </div>

        <div class="filters">
          <label for="status-filter">Filter by status:</label>
          <select id="status-filter" v-model="filter" class="filter-dropdown">
            <option value="all">All Users</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in paginatedUsers" :key="user.id">
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>
                <span :class="user.status">{{ user.status }}</span>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="pagination">
          <button @click="prevPage" :disabled="page === 1">Prev</button>
          <span>Page {{ page }} of {{ totalPages }}</span>
          <button @click="nextPage" :disabled="page === totalPages">Next</button>
        </div>
      </main>
    </div>

    <!-- ADD USER MODAL -->
    <div v-if="showAddUserModal" class="modal-overlay" @click="closeModal">
      <div class="modal" @click.stop>
        <div class="modal-header">
          <h3>Add New User</h3>
          <button class="close-btn" @click="showAddUserModal = false">✕</button>
        </div>
        <form @submit.prevent="addUser" class="modal-body">
          <div class="form-group">
            <label for="userName">Name:</label>
            <input
              type="text"
              id="userName"
              v-model="newUser.name"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label for="userEmail">Email:</label>
            <input
              type="email"
              id="userEmail"
              v-model="newUser.email"
              required
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label for="userStatus">Status:</label>
            <select id="userStatus" v-model="newUser.status" class="form-input">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="modal-actions">
            <button type="button" @click="showAddUserModal = false" class="cancel-btn">Cancel</button>
            <button type="submit" class="submit-btn">Add User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const sidebarOpen = ref(true);
const filter = ref('all');
const page = ref(1);
const perPage = 5;
const searchQuery = ref('');
const showAddUserModal = ref(false);

const users = ref([
  { id: 1, name: 'Alice', email: 'alice@email.com', status: 'active' },
  { id: 2, name: 'Bob', email: 'bob@email.com', status: 'inactive' },
  { id: 3, name: 'Charlie', email: 'charlie@email.com', status: 'active' },
  { id: 4, name: 'Diana', email: 'diana@email.com', status: 'inactive' },
  { id: 5, name: 'Eve', email: 'eve@email.com', status: 'active' },
  { id: 6, name: 'Frank', email: 'frank@email.com', status: 'inactive' },
  { id: 7, name: 'Grace', email: 'grace@email.com', status: 'active' },
  { id: 8, name: 'Heidi', email: 'heidi@email.com', status: 'inactive' },
  { id: 9, name: 'Ivan', email: 'ivan@email.com', status: 'active' },
  { id: 10, name: 'Judy', email: 'judy@email.com', status: 'inactive' },
]);

const newUser = ref({
  name: '',
  email: '',
  status: 'active'
});

const filteredUsers = computed(() => {
  let result = users.value;

  // Apply search filter
  if (searchQuery.value) {
    result = result.filter(user =>
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  // Apply status filter
  if (filter.value !== 'all') {
    result = result.filter(u => u.status === filter.value);
  }

  return result;
});

const totalPages = computed(() => Math.ceil(filteredUsers.value.length / perPage));

const paginatedUsers = computed(() => {
  const start = (page.value - 1) * perPage;
  return filteredUsers.value.slice(start, start + perPage);
});

function prevPage() {
  if (page.value > 1) page.value--;
}
function nextPage() {
  if (page.value < totalPages.value) page.value++;
}

function addUser() {
  const newId = Math.max(...users.value.map(u => u.id)) + 1;
  users.value.push({
    id: newId,
    name: newUser.value.name,
    email: newUser.value.email,
    status: newUser.value.status
  });

  // Reset form
  newUser.value = { name: '', email: '', status: 'active' };
  showAddUserModal.value = false;
}

function closeModal(event) {
  if (event.target.classList.contains('modal-overlay')) {
    showAddUserModal.value = false;
  }
}

// Reset to page 1 when filter or search changes
watch([filter, searchQuery], () => {
  page.value = 1;
});

// Header logic
const notifications = ref([
  'Welcome to Boilerplate!',
  'Your profile was updated.',
]);
const showNotifications = ref(false);
const showProfileMenu = ref(false);

// Close dropdowns on click outside
function handleClickOutside(event) {
  if (!event.target.closest('.notification')) showNotifications.value = false;
  if (!event.target.closest('.profile')) showProfileMenu.value = false;
}
window.addEventListener('click', handleClickOutside);
</script>

<style scoped>
:root {
  --color-primary-dark: #2B2D42;
  --color-primary-light: #415A77;
}

.dashboard {
  display: flex;
  min-height: 100vh;
  font-family: Arial, sans-serif;
}

.sidebar {
  width: 200px;
  background: var(--color-primary-dark);
  color: #fff;
  transition: width 0.3s ease;
  padding: 1rem 0.5rem;
  position: relative;
  z-index: 2;
}

.sidebar.closed {
  width: 60px;
}

.toggle-btn {
  background: var(--color-primary-dark);
  color: #fff;
  border: none;
  padding: 0.75rem;
  margin-bottom: 1rem;
  cursor: pointer;
  width: 30%;
  border-radius: 4px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.2rem;
}

.toggle-btn:hover {
  background: #313457;
}

.hamburger {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.hamburger span {
  width: 20px;
  height: 2px;
  background: #fff;
  border-radius: 1px;
  transition: all 0.3s ease;
}

.sidebar-content {
  margin-top: 1rem;
}

.sidebar-content h2 {
  margin: 0 0 1rem 0;
  font-size: 1.2rem;
}

.sidebar-content ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-content li {
  margin: 0.75rem 0;
  padding: 0.5rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s;
}

.sidebar-content li:hover {
  background: #313457;
}

.main-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  padding: 1rem 2rem;
  border-bottom: 1px solid #eee;
  position: sticky;
  top: 0;
  z-index: 1;
}

.site-name {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--color-primary-dark);
  letter-spacing: 1px;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  position: relative;
}

.notification {
  position: relative;
  cursor: pointer;
  padding: 0.25rem;
}

.notif-dot {
  position: absolute;
  top: 2px;
  right: 2px;
  width: 8px;
  height: 8px;
  background: #dc3545;
  border-radius: 50%;
  border: 2px solid #fff;
}

.notif-dropdown {
  position: absolute;
  right: 0;
  top: 2.2rem;
  background: #fff;
  border: 1px solid #eee;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  min-width: 180px;
  z-index: 10;
  padding: 0.5rem 0;
}

.notif-item {
  padding: 0.5rem 1rem;
  font-size: 0.98rem;
  color: #333;
  border-bottom: 1px solid #f2f2f2;
}
.notif-item:last-child {
  border-bottom: none;
}
.notif-empty {
  padding: 0.5rem 1rem;
  color: #888;
  font-size: 0.95rem;
}

.profile {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  position: relative;
  user-select: none;
}

.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #eee;
}

.profile-name {
  font-weight: 500;
  color: #333;
  font-size: 1rem;
}

.profile-dropdown {
  position: absolute;
  right: 0;
  top: 2.5rem;
  background: #fff;
  border: 1px solid #eee;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  min-width: 140px;
  z-index: 10;
  padding: 0.5rem 0;
}

.profile-item {
  padding: 0.5rem 1rem;
  font-size: 0.98rem;
  color: #333;
  cursor: pointer;
  transition: background 0.2s;
}
.profile-item:hover {
  background: #f5f6fa;
}

.main-content {
  flex: 1;
  padding: 2rem;
  background: #f5f6fa;
}

/* USER CONTROLS SECTION */
.user-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  gap: 1rem;
}

.search-section {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.5rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  background: #fff;
}

.search-input:focus {
  outline: none;
  border-color: var(--color-primary-dark);
  box-shadow: 0 0 0 2px rgba(43, 45, 66, 0.1);
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.add-user-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: var(--color-primary-dark);
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
  white-space: nowrap;
}

.add-user-btn:hover {
  background: #313457;
}

.filters {
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.filters label {
  font-weight: 600;
  color: #333;
}

.filter-dropdown {
  padding: 0.5rem 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #fff;
  font-size: 1rem;
  cursor: pointer;
  min-width: 150px;
}

.filter-dropdown:focus {
  outline: none;
  border-color: var(--color-primary-dark);
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  margin-bottom: 1rem;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

th {
  background: #f8f9fa;
  font-weight: 600;
  color: #495057;
}

th, td {
  padding: 1rem;
  border-bottom: 1px solid #eee;
  text-align: left;
}

tr:last-child td {
  border-bottom: none;
}

tbody tr:hover {
  background: #f8f9fa;
}

td .active {
  color: #28a745;
  font-weight: 600;
  text-transform: capitalize;
}

td .inactive {
  color: #dc3545;
  font-weight: 600;
  text-transform: capitalize;
}

.pagination {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: center;
}

.pagination button {
  padding: 0.5rem 1rem;
  border: none;
  background: var(--color-primary-dark);
  color: #fff;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
}

.pagination button:hover:not(:disabled) {
  background: #313457;
}

.pagination button:disabled {
  background: #6c757d;
  cursor: not-allowed;
}

.pagination span {
  font-weight: 500;
  color: #495057;
}

/* MODAL STYLES */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: #fff;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #eee;
}

.modal-header h3 {
  margin: 0;
  color: var(--color-primary-dark);
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  color: #333;
}

.modal-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #333;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary-dark);
  box-shadow: 0 0 0 2px rgba(43, 45, 66, 0.1);
}

.modal-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 1.5rem;
}

.cancel-btn {
  padding: 0.75rem 1.5rem;
  border: 1px solid #ddd;
  background: #fff;
  color: #666;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.cancel-btn:hover {
  background: #f8f9fa;
}

.submit-btn {
  padding: 0.75rem 1.5rem;
  background: var(--color-primary-dark);
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.submit-btn:hover {
  background: #313457;
}
</style>
