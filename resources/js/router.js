import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Pages/Authentication/Login.vue';
import Register from './components/Pages/Authentication/Register.vue';
import Dashboard from './components/Pages/Dashboard.vue';
import Users from './components/Pages/Users.vue'; // Import Users.vue

const routes = [
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard },
  { path: '/users', component: Users },
  { path: '/', redirect: '/login' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
