<template>
  <aside class="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
      <img src="/logo.png" alt="RM Trans" class="sidebar-logo-img" />
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <p class="nav-section-label">Menu Utama</p>
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="nav-item"
        :class="{ active: $route.path === item.path || $route.path.startsWith(item.path + '/') }"
      >
        <span class="nav-icon" v-html="item.icon"></span>
        <span class="nav-label">{{ item.label }}</span>
        <span v-if="item.badge" class="nav-badge"></span>
      </router-link>
    </nav>

    <!-- User + Logout -->
    <div class="sidebar-footer">
      <div class="user-info">
        <div class="user-avatar">A</div>
        <div class="user-details">
          <span class="user-name">Admin RM Trans</span>
          <span class="user-role">Administrator</span>
        </div>
      </div>
      <button class="logout-btn" @click="$router.push('/login')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Keluar
      </button>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'Sidebar',
  data() {
    return {
      menuItems: [
        {
          path: '/dashboard',
          label: 'Dashboard',
          icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>'
        },
        {
          path: '/kendaraan',
          label: 'Daftar Kendaraan',
          icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14v-5H5v5zM5 12l2-7h10l2 7"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>'
        },
        {
          path: '/monitoring-kendaraan',
          label: 'Monitoring',
          icon: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
          badge: true
        }
      ]
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.sidebar {
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
  width: 230px;
  background: #ffffff;
  border-right: 1px solid #e8e8f0;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  font-family: 'Poppins', sans-serif;
}

/* ===== LOGO ===== */
.sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem 1.25rem 1.25rem;
  border-bottom: 1px solid #e8e8f0;
}
.sidebar-logo-img {
  max-width: 150px;
  height: auto;
  object-fit: contain;
}

/* ===== NAV ===== */
.sidebar-nav {
  flex: 1;
  padding: 1.2rem 0.85rem;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.nav-section-label {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  color: #b0b0c8;
  padding: 0 0.75rem;
  margin-bottom: 0.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.7rem 0.85rem;
  border-radius: 10px;
  color: #6b7280;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 500;
  transition: all 0.18s ease;
  position: relative;
}

.nav-item:hover {
  background: #f3f4ff;
  color: #3E3D90;
}

.nav-item.active {
  background: #f0f0fb;
  color: #3E3D90;
  font-weight: 600;
  border-left: 3px solid #3E3D90;
}

.nav-item.active .nav-icon {
  color: #3E3D90;
}

.nav-icon {
  display: flex;
  align-items: center;
  flex-shrink: 0;
}

.nav-label { flex: 1; }

.nav-badge {
  width: 7px;
  height: 7px;
  background: #ef4444;
  border-radius: 50%;
  box-shadow: 0 0 6px rgba(248, 113, 113, 0.7);
}

/* ===== FOOTER ===== */
.sidebar-footer {
  padding: 0.85rem;
  border-top: 1px solid #e8e8f0;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.6rem 0.75rem;
  margin-bottom: 6px;
  border-radius: 10px;
  background: #f5f5fb;
}

.user-avatar {
  width: 34px;
  height: 34px;
  background: #3E3D90;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 13px;
  font-weight: 700;
  flex-shrink: 0;
}

.user-details {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.user-name {
  font-size: 0.78rem;
  font-weight: 600;
  color: #1e1d4c;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-role {
  font-size: 0.68rem;
  color: #9ca3af;
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  width: 100%;
  padding: 0.65rem 0.85rem;
  border-radius: 10px;
  background: none;
  border: none;
  color: #ef4444;
  font-family: 'Poppins', sans-serif;
  font-size: 0.83rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.15s;
}

.logout-btn:hover {
  background: #fff0f0;
}
</style>