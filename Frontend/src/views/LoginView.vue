<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-header">
        <div class="logo-icon">
          <svg viewBox="0 0 24 24" fill="currentColor" width="36" height="36">
            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
          </svg>
        </div>
        <h1>RM Trans</h1>
        <p>Monitoring Bengkel</p>
      </div>

      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" v-model="form.email" type="email" placeholder="admin@rmtrans.com" required :disabled="loading" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" v-model="form.password" type="password" placeholder="••••••••" required :disabled="loading" />
        </div>
        <div v-if="error" class="error-msg">{{ error }}</div>
        <button type="submit" class="btn-login" :disabled="loading">
          {{ loading ? 'Masuk...' : 'Masuk' }}
        </button>
        <p class="demo-hint">Demo: email & password bebas, klik Masuk</p>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      form: { email: '', password: '' },
      loading: false,
      error: ''
    }
  },
  methods: {
    handleLogin() {
      if (!this.form.email || !this.form.password) {
        this.error = 'Email dan password harus diisi.'
        return
      }
      this.loading = true
      // Simulasi delay login
      setTimeout(() => {
        this.loading = false
        this.$router.push('/dashboard')
      }, 800)
    }
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}
.login-card {
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 20px;
  padding: 2.5rem;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 25px 50px rgba(0,0,0,0.4);
}
.login-header { text-align: center; margin-bottom: 2rem; }
.logo-icon { color: #e94560; margin-bottom: 0.75rem; }
.login-header h1 { font-size: 1.8rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
.login-header p { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
.form-group { margin-bottom: 1.25rem; }
label { display: block; color: rgba(255,255,255,0.7); font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; }
input { width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; color: #fff; font-size: 0.95rem; transition: border-color 0.2s; box-sizing: border-box; }
input:focus { outline: none; border-color: #e94560; }
input::placeholder { color: rgba(255,255,255,0.3); }
.error-msg { background: rgba(233,69,96,0.15); border: 1px solid rgba(233,69,96,0.4); border-radius: 8px; color: #e94560; padding: 0.7rem 1rem; font-size: 0.85rem; margin-bottom: 1rem; }
.btn-login { width: 100%; padding: 0.85rem; background: linear-gradient(135deg, #e94560, #c62a47); border: none; border-radius: 10px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.1s; }
.btn-login:hover:not(:disabled) { opacity: 0.9; transform: translateY(-1px); }
.btn-login:disabled { opacity: 0.6; cursor: not-allowed; }
.demo-hint { text-align: center; margin-top: 1rem; font-size: 0.78rem; color: rgba(255,255,255,0.35); }
</style>
