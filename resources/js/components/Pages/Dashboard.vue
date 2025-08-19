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
      <div class="logout" @click="handleLogout">
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
          <span>Welcome, {{ currentUserName }}</span>
        </div>
      </div>

      <!-- Content Header -->
      <div class="content-header">
        <h1>Users</h1>
        <div class="actions">
          <select v-model="filter" class="filter-select" @change="onFilterChange">
            <option value="">Select Filter</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <input
            v-model="search"
            placeholder="Search Users"
            class="search-input"
            @input="onSearchInput"
          />
          <button class="add-user-btn" @click="showAddUserModal">
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
            <td>
              <div class="actions-dropdown" @click.stop>
                <button class="actions-btn" @click="toggleRowMenu(user.id, $event)">...</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Fixed-position dropdown menu -->
      <div
        v-if="openMenuUserId"
        class="dropdown-menu"
        :style="{ top: menuPosition.top + 'px', left: menuPosition.left + 'px' }"
        @click.stop
      >
        <button @click="openEditUser(getUserById(openMenuUserId))">Edit</button>
        <button class="danger" @click="confirmDeleteUser(getUserById(openMenuUserId))">Delete</button>
      </div>

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

    <!-- Add User Modal -->
    <div v-if="isAddUserModalVisible" class="modal-overlay">
      <div class="modal">
        <div class="modal-header">
          <h2><i class="fas fa-user-plus"></i> Add User</h2>
          <button class="close-btn" @click="closeAddUserModal">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="addUser">
            <div class="form-row">
              <div class="form-group">
                <label for="firstname">First Name*</label>
                <input v-model="newUser.firstname" id="firstname" type="text" required />
              </div>
              <div class="form-group">
                <label for="middlename">Middle Name</label>
                <input v-model="newUser.middlename" id="middlename" type="text" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="lastname">Last Name*</label>
                <input v-model="newUser.lastname" id="lastname" type="text" required />
              </div>
              <div class="form-group">
                <label for="email">Email*</label>
                <input v-model="newUser.email" id="email" type="email" required />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="password">Password*</label>
                <input v-model="newUser.password" id="password" type="password" required />
              </div>
            </div>
            <button type="submit" class="submit-btn">Add User</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div v-if="isEditUserModalVisible" class="modal-overlay">
      <div class="modal">
        <div class="modal-header">
          <h2><i class="fas fa-user-edit"></i> Edit User</h2>
          <button class="close-btn" @click="closeEditUserModal">×</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveUserEdits">
            <div class="form-row">
              <div class="form-group">
                <label for="edit_firstname">First Name*</label>
                <input v-model="editUser.firstname" id="edit_firstname" type="text" required />
              </div>
              <div class="form-group">
                <label for="edit_middlename">Middle Name</label>
                <input v-model="editUser.middlename" id="edit_middlename" type="text" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="edit_lastname">Last Name*</label>
                <input v-model="editUser.lastname" id="edit_lastname" type="text" required />
              </div>
              <div class="form-group">
                <label for="edit_email">Email*</label>
                <input v-model="editUser.email" id="edit_email" type="email" required />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group full-width">
                <label for="edit_password">Password (leave blank to keep existing)</label>
                <input v-model="editUser.password" id="edit_password" type="password" />
              </div>
            </div>
            <button type="submit" class="submit-btn">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import api, { createUser, updateUser, deleteUser, logout as logoutApi, setAuthToken } from '../../services/api';

// State variables
const router = useRouter();
const users = ref([]);
const filter = ref('');
const search = ref('');
const page = ref(1);
const totalPages = ref(1);
const openMenuUserId = ref(null);
const menuPosition = ref({ top: 0, left: 0 });
const isAddUserModalVisible = ref(false);
const isEditUserModalVisible = ref(false);
const newUser = ref({
  firstname: '',
  middlename: '',
  lastname: '',
  email: '',
  password: '',
});
const editUser = ref({ id: null, firstname: '', middlename: '', lastname: '', email: '', password: '' });

const currentUserName = computed(() => {
  try {
    const u = JSON.parse(localStorage.getItem('authUser') || 'null')
    return u?.name || 'User'
  } catch {
    return 'User'
  }
});

// Close dropdown on outside click/scroll/resize
const handleDocumentClick = () => { openMenuUserId.value = null; };

document.addEventListener('click', handleDocumentClick);
window.addEventListener('scroll', handleDocumentClick, true);
window.addEventListener('resize', handleDocumentClick);

onBeforeUnmount(() => {
  document.removeEventListener('click', handleDocumentClick);
  window.removeEventListener('scroll', handleDocumentClick, true);
  window.removeEventListener('resize', handleDocumentClick);
});

const getUserById = (id) => users.value.find(u => u.id === id) || null;

const toggleRowMenu = (userId, event) => {
  if (openMenuUserId.value === userId) {
    openMenuUserId.value = null;
    return;
  }
  const btn = event.currentTarget;
  const rect = btn.getBoundingClientRect();
  const menuWidth = 160;
  let left = rect.right - menuWidth;
  if (left < 8) left = 8;
  if (left + menuWidth > window.innerWidth - 8) left = window.innerWidth - menuWidth - 8;
  const top = rect.bottom + 6;
  menuPosition.value = { top, left };
  openMenuUserId.value = userId;
};

const openEditUser = (user) => {
  // Pre-fill edit form by fetching structured fields
  api.get(`/users/${user.id}`).then(({ data }) => {
    editUser.value = {
      id: user.id,
      firstname: data.firstname || '',
      middlename: data.middlename || '',
      lastname: data.lastname || '',
      email: data.email || '',
      password: '',
    };
    isEditUserModalVisible.value = true;
    openMenuUserId.value = null;
  }).catch(() => {
    // Fallback to best-effort split if fetch fails
    const parts = (user.name || '').split(' ');
    editUser.value = {
      id: user.id,
      firstname: parts[0] || '',
      middlename: parts.length > 2 ? parts.slice(1, -1).join(' ') : '',
      lastname: parts.length > 1 ? parts[parts.length - 1] : '',
      email: user.email,
      password: '',
    };
    isEditUserModalVisible.value = true;
    openMenuUserId.value = null;
  });
};

const closeEditUserModal = () => {
  isEditUserModalVisible.value = false;
};

// Debounce helper to limit API calls on typing
let searchDebounceId;
const onSearchInput = () => {
  clearTimeout(searchDebounceId);
  searchDebounceId = setTimeout(() => {
    page.value = 1;
    fetchUsers();
  }, 300);
};

const onFilterChange = () => {
  page.value = 1;
  fetchUsers();
};

// Logout handler
const handleLogout = async () => {
  try {
    await logoutApi();
  } catch (e) {
    // ignore
  }
  localStorage.removeItem('authToken');
  localStorage.removeItem('authUser');
  setAuthToken(null);
  router.push('/login');
};

// Fetch users from the API
const fetchUsers = async () => {
  try {
    const response = await api.get('/users', {
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

// Show and hide modal
const showAddUserModal = () => {
  isAddUserModalVisible.value = true;
};

const closeAddUserModal = () => {
  isAddUserModalVisible.value = false;
};

// Add user
const addUser = async () => {
  try {
    await createUser(newUser.value);
    newUser.value = { firstname: '', middlename: '', lastname: '', email: '', password: '' };
    page.value = 1;
    await fetchUsers();
    window.$toast.push('User added successfully!', 'success');
    closeAddUserModal();
  } catch (error) {
    console.error('Error adding user:', error?.response || error);
    window.$toast.push(error?.response?.data?.message || 'Failed to add user.', 'error');
  }
};

// Save edits with confirmation
const saveUserEdits = async () => {
  const ok = await window.$confirm.open('Save changes to this user?', 'Confirm Edit');
  if (!ok) return;
  try {
    const payload = {
      firstname: editUser.value.firstname,
      middlename: editUser.value.middlename || null,
      lastname: editUser.value.lastname,
      email: editUser.value.email,
    };
    if (editUser.value.password) payload.password = editUser.value.password;

    await updateUser(editUser.value.id, payload);

    await fetchUsers();
    window.$toast.push('User updated successfully!', 'success');
    closeEditUserModal();
  } catch (error) {
    console.error('Error updating user:', error?.response || error);
    window.$toast.push(error?.response?.data?.message || 'Failed to update user.', 'error');
  }
};

// Delete with confirmation
const confirmDeleteUser = async (user) => {
  openMenuUserId.value = null;
  const ok = await window.$confirm.open(`Delete user "${user.name}"? This cannot be undone.`, 'Confirm Delete');
  if (!ok) return;
  try {
    await deleteUser(user.id);
    await fetchUsers();
    window.$toast.push('User deleted successfully!', 'success');
  } catch (error) {
    console.error('Error deleting user:', error?.response || error);
    window.$toast.push(error?.response?.data?.message || 'Failed to delete user.', 'error');
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

/* Row actions dropdown trigger */
.actions-dropdown {
  position: relative;
  display: inline-block;
}

.actions-btn {
  background: #fff;
  border: 1px solid #ccc;
  padding: 4px 8px;
  border-radius: 4px;
  cursor: pointer;
}

/* Fixed dropdown menu to avoid clipping */
.dropdown-menu {
  position: fixed;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  min-width: 160px;
  z-index: 1500;
  display: flex;
  flex-direction: column;
}

.dropdown-menu button {
  background: transparent;
  border: none;
  padding: 10px 12px;
  text-align: left;
  cursor: pointer;
}

.dropdown-menu button:hover {
  background: #f5f5f5;
}

.dropdown-menu .danger {
  color: #dc3545;
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

/* Modal Styles */
.modal-overlay {
    font-family: 'Inter';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal {
  background-color: #415a77;
  color: white;
  padding: 28px; /* increased padding */
  border-radius: 10px;
  width: 640px; /* wider modal */
  max-width: 90vw; /* responsive cap */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px; /* more spacing */
}

.modal-header h2 {
  font-size: 1.6rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.6rem;
  cursor: pointer;
}

.modal-body {
  display: flex;
  flex-direction: column;
  gap: 20px; /* more spacing between sections */
}

/* Use grid layout inside forms to avoid overlaps */
.modal-body form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  column-gap: 24px;
  row-gap: 16px;
}

/* Let former rows act as grouping only; children become grid items */
.form-row {
  display: contents;
}

.form-group {
  flex: 1;
  min-width: 0;
}

.form-group.full-width {
  flex: 100%;
  grid-column: 1 / -1;
}

.form-group label {
  display: block;
  margin-bottom: 8px; /* more label spacing */
}

.form-group input {
  width: 100%;
  padding: 12px; /* larger input padding */
  border: 1px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box; /* ensure inputs don't overflow */
}

.submit-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px 16px; /* larger button padding */
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
  font-size: 1rem;
}

.submit-btn:hover {
  background-color: #0056b3;
}

@media (max-width: 600px) {
  .modal-body form {
    grid-template-columns: 1fr; /* stack on small screens */
    column-gap: 0;
    row-gap: 12px;
  }
  .modal {
    width: 95vw;
    padding: 24px;
  }
}
</style>
