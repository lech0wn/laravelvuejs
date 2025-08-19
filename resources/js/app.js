import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { setAuthToken } from './services/api'
import Toast from './components/Toast.vue'
import ConfirmDialog from './components/ConfirmDialog.vue'

// Initialize auth token from localStorage
const savedToken = localStorage.getItem('authToken')
if (savedToken) {
	setAuthToken(savedToken)
}

// Simple auth guard: require token for protected pages
router.beforeEach((to, from, next) => {
	const requiresAuth = ['/dashboard', '/users']
	const hasToken = !!localStorage.getItem('authToken')
	if (requiresAuth.includes(to.path) && !hasToken) {
		return next('/login')
	}
	next()
})

const app = createApp(App)
app.use(router)
app.component('Toast', Toast)
app.component('ConfirmDialog', ConfirmDialog)
app.mount('#app')
