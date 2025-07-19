<template>
  <div class="login-container">
    <div class="login-left">
      <h1 class="login-title">Log In</h1>
      <p class="login-subtitle">Enter your email and password to login</p>
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            placeholder="Enter your email"
          />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
            placeholder="Enter your password"
          />
          <div class="form-links">
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>
        </div>
        <button type="submit" class="login-btn">Login</button>
      </form>
      <div class="signup-text">
        Don't have an account?
        <router-link to="/register" class="signup-link">Sign up!</router-link>
      </div>
      <div v-if="error" class="error-message">{{ error }}</div>
    </div>
    <div class="login-right">
      <span class="boilerplate-text">Boilerplate</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const error = ref('')

function handleLogin() {
  error.value = ''
  if (!email.value || !password.value) {
    error.value = 'Please enter both email and password.'
    return
  }
  if (email.value === 'test@example.com' && password.value === 'password') {
    alert('Login successful!')
  } else {
    error.value = 'Invalid credentials.'
  }
}
</script>

<style>
:root {
  --color-primary-dark: #2B2D42;
  --color-accent: #415A77;
}
html, body {
  margin: 0;
  padding: 0;
  overflow: hidden;
  height: 100%;
}
</style>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

.login-container {
  display: flex;
  min-height: 100vh;
  font-family: 'Inter', sans-serif;
}

.login-left {
  width: 700px;
  background: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 0 40px;
  font-family: 'Inter', sans-serif;
  height: 100vh;
}

.login-right {
  flex: 1;
  background: var(--color-primary-dark);
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-title {
  font-size: 2rem;
  font-weight: bold;
  color: var(--color-accent);
  margin-bottom: 8px;
  text-align: center;
}

.login-subtitle {
  font-size: 1rem;
  color: var(--color-accent);
  margin-bottom: 24px;
  text-align: center;
}

.login-form {
  width: 100%;
  max-width: 380px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.form-group {
  width: 100%;
  margin-bottom: 18px;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: var(--color-accent);
  margin-bottom: 6px;
}

.form-group input {
  width: 100%;
  padding: 10px 12px;
  border: 2px solid var(--color-accent);
  border-radius: 6px;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.2s;
  box-sizing: border-box;
}

.form-group input:focus {
  border-color: var(--color-accent);
}

.form-group input::placeholder {
  color: #C4C3C3;
  opacity: 1;
}

.form-links {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  margin-bottom: 0;
  margin-top: 4px;
}

.forgot-link {
  font-size: 0.9rem;
  color: var(--color-accent);
  text-decoration: underline;
  cursor: pointer;
}

.login-btn {
  width: 100%;
  max-width: 100%;
  background: var(--color-accent);
  color: #fff;
  font-weight: bold;
  padding: 12px 0;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  margin-top: 8px;
  cursor: pointer;
  transition: background 0.2s;
  box-sizing: border-box;
}

.login-btn:hover {
  background: var(--color-accent);
}

.signup-text {
  margin-top: 18px;
  font-size: 0.95rem;
  color: #777;
  text-align: center;
}

.signup-link {
  color: var(--color-accent);
  text-decoration: underline;
  font-weight: 600;
  margin-left: 4px;
  cursor: pointer;
}

.error-message {
  color: #e53e3e;
  margin-top: 12px;
  text-align: center;
  font-size: 0.98rem;
}

.boilerplate-text {
  color: #fff;
  font-size: 3rem;
  font-weight: 300;
  letter-spacing: 2px;
  text-align: center;
  user-select: none;
}
</style>
