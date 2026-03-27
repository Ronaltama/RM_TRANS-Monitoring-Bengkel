<template>
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{ collapsed: sidebarCollapsed }">
      <div class="sidebar-header">
        <div class="brand">
          <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
          </svg>
          <span v-if="!sidebarCollapsed">RM Trans</span>
        </div>
        <button class="collapse-btn" @click="sidebarCollapsed = !sidebarCollapsed">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2">
            <path d="M3 12h18M3 6h18M3 18h18"/>
          </svg>
        </button>
      </div>

      <nav class="sidebar-nav">
        <router-link to="/dashboard" class="nav-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2">
            <rect x="3" y="3" width="7" height="7"/>
            <rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/>
            <rect x="3" y="14" width="7" height="7"/>
          </svg>
          <span v-if="!sidebarCollapsed">Dashboard</span>
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <button class="logout-btn" @click="logout">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          <span v-if="!sidebarCollapsed">Logout</span>
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="topbar">
        <div>
          <h2>Dashboard</h2>
          <p class="topbar-sub">Selamat datang, {{ userName }}</p>
        </div>
      </header>

      <div class="content-body">
        <p class="placeholder-text">🚗 Dashboard siap dikembangkan!</p>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'DashboardView',
  data() {
    return {
      sidebarCollapsed: false,
      userName: 'Admin',
    }
  },
  created() {
    const user = localStorage.getItem('user')
    if (user) {
      const parsed = JSON.parse(user)
      this.userName = parsed.name || parsed.email || 'Admin'
    }
    // Set token header for subsequent requests
    const token = localStorage.getItem('token')
    if (token) {
      this.$http.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }
  },
  methods: {
    logout() {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      this.$router.push('/login')
    },
  },
}
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 240px;
  min-height: 100vh;
  background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  flex-shrink: 0;
}

.sidebar.collapsed {
  width: 72px;
}

.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1rem;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  color: #e94560;
  font-weight: 700;
  font-size: 1.1rem;
  overflow: hidden;
  white-space: nowrap;
}

.collapse-btn {
  background: none;
  border: none;
  color: rgba(255,255,255,0.5);
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: color 0.2s;
}
.collapse-btn:hover { color: #fff; }

.sidebar-nav {
  flex: 1;
  padding: 1rem 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.65rem 0.85rem;
  border-radius: 10px;
  color: rgba(255,255,255,0.6);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.2s;
  white-space: nowrap;
  overflow: hidden;
}
.nav-item:hover, .nav-item.router-link-active {
  background: rgba(233, 69, 96, 0.15);
  color: #e94560;
}

.sidebar-footer {
  padding: 1rem 0.75rem;
  border-top: 1px solid rgba(255,255,255,0.08);
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.65rem 0.85rem;
  border-radius: 10px;
  background: none;
  border: none;
  color: rgba(255,255,255,0.5);
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  width: 100%;
  transition: all 0.2s;
  white-space: nowrap;
  overflow: hidden;
}
.logout-btn:hover {
  background: rgba(233,69,96,0.1);
  color: #e94560;
}

/* Main content */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #f5f6fa;
  min-width: 0;
}

.topbar {
  background: #fff;
  border-bottom: 1px solid #e8ecf0;
  padding: 1.25rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.topbar h2 {
  font-size: 1.3rem;
  font-weight: 700;
  color: #1a1a2e;
}

.topbar-sub {
  font-size: 0.8rem;
  color: #888;
  margin-top: 2px;
}

.content-body {
  flex: 1;
  padding: 2rem;
}

.placeholder-text {
  text-align: center;
  color: #aaa;
  font-size: 1.1rem;
  margin-top: 4rem;
}
</style>
