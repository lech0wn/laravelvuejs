<template>
  <div class="register-container">
    <div class="register-left">
      <span class="boilerplate-text">Boilerplate</span>
    </div>
    <div class="register-right">
      <h1 class="register-title">Register</h1>
      <p class="register-subtitle">Create your account</p>
      <form @submit.prevent="handleRegister" class="register-form">
        <div class="form-group">
          <label for="firstName">First Name*</label>
          <input id="firstName" v-model="firstName" type="text" required placeholder="Enter your first name" />
        </div>
        <div class="form-group">
          <label for="middleName">Middle Name</label>
          <input id="middleName" v-model="middleName" type="text" placeholder="Enter your middle name" />
        </div>
        <div class="form-group">
          <label for="lastName">Last Name*</label>
          <input id="lastName" v-model="lastName" type="text" required placeholder="Enter your last name" />
        </div>
        <div class="form-group">
          <label for="email">Email*</label>
          <input id="email" v-model="email" type="email" required placeholder="Enter your email" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" v-model="password" type="password" required placeholder="Enter your password" />
        </div>
        <button type="submit" class="register-btn">Register</button>
      </form>
      <div class="login-text">
        Already have an account?
        <router-link to="/login" class="login-link">Log in!</router-link>
      </div>
      <div v-if="error" class="error-message">{{ error }}</div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const firstName = ref('')
const middleName = ref('')
const lastName = ref('')
const email = ref('')
const password = ref('')
const error = ref('')

function handleRegister() {
  error.value = ''
  if (!firstName.value || !lastName.value || !email.value || !password.value) {
    error.value = 'Please fill in all required fields.'
    return
  }
  // Registration logic here
  alert('Registration successful!')
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

.register-container {
  display: flex;
  min-height: 100vh;
  font-family: 'Inter', sans-serif;
}

.register-left {
  flex: 1;
  background: var(--color-primary-dark);
  display: flex;
  align-items: center;
  justify-content: center;
}

.register-right {
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

.boilerplate-text {
  color: #fff;
  font-size: 3rem;
  font-weight: 300;
  letter-spacing: 2px;
  text-align: center;
  user-select: none;
}

.register-title {
  font-size: 2rem;
  font-weight: bold;
  color: var(--color-accent);
  margin-bottom: 8px;
  text-align: center;
}

.register-subtitle {
  font-size: 1rem;
  color: var(--color-accent);
  margin-bottom: 24px;
  text-align: center;
}

.register-form {
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

.register-btn {
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

.register-btn:hover {
  background: var(--color-accent);
}

.error-message {
  color: #e53e3e;
  margin-top: 12px;
  text-align: center;
  font-size: 0.98rem;
}

.login-text {
  margin-top: 18px;
  font-size: 0.95rem;
  color: #777;
  text-align: center;
}

.login-link {
  color: var(--color-accent);
  text-decoration: underline;
  font-weight: 600;
  margin-left: 4px;
  cursor: pointer;
}
</style>
