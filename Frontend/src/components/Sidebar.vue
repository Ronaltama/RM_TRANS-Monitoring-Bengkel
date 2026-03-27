<template>
  <aside class="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
      <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
      </svg>
      <span>RM Trans</span>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
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
        <div class="user-avatar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2">
            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <div class="user-details">
          <span class="user-name">Admin RM Trans</span>
          <span class="user-role">admin</span>
        </div>
      </div>
      <button class="logout-btn" @click="$router.push('/login')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2">
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
          icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>'
        },
        {
          path: '/kendaraan',
          label: 'Daftar Kendaraan',
          icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5H5v5zM5 12l2-7h10l2 7"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>'
        },
        {
          path: '/monitoring-kendaraan',
          label: 'Monitoring',
          icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
          badge: true
        }
      ]
    }
  }
}
</script>

<style scoped>
.sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; width: 220px; background: #fff; border-right: 1px solid #e8ecf0; display: flex; flex-direction: column; flex-shrink: 0; }
.sidebar-logo { display: flex; align-items: center; gap: 0.6rem; padding: 1.25rem 1rem; color: #4f46e5; font-weight: 700; font-size: 1rem; border-bottom: 1px solid #e8ecf0; }
.sidebar-nav { flex: 1; padding: 0.75rem; display: flex; flex-direction: column; gap: 2px; }
.nav-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.65rem 0.85rem; border-radius: 8px; color: #6b7280; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.15s; position: relative; }
.nav-item:hover { background: #f3f4ff; color: #4f46e5; }
.nav-item.active { background: #4f46e5; color: #fff; }
.nav-icon { display: flex; align-items: center; }
.nav-label { flex: 1; }
.nav-badge { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; }
.sidebar-footer { padding: 0.75rem; border-top: 1px solid #e8ecf0; }
.user-info { display: flex; align-items: center; gap: 0.6rem; padding: 0.5rem 0.75rem; margin-bottom: 6px; }
.user-avatar { width: 32px; height: 32px; background: #f3f4ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f46e5; }
.user-details { display: flex; flex-direction: column; }
.user-name { font-size: 0.8rem; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px; }
.user-role { font-size: 0.7rem; color: #9ca3af; text-transform: capitalize; }
.logout-btn { display: flex; align-items: center; gap: 0.6rem; width: 100%; padding: 0.65rem 0.85rem; border-radius: 8px; background: none; border: none; color: #ef4444; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.15s; }
.logout-btn:hover { background: #fef2f2; }
</style>
