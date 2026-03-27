<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">
      <header class="topbar">
        <div>
          <h2 class="page-title">Dashboard</h2>
          <p class="page-sub">Ringkasan aktivitas bengkel</p>
        </div>
        <button class="btn-refresh" @click="refresh">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2">
            <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
          </svg>
          Refresh
        </button>
      </header>

      <div class="content-body">
        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card" v-for="s in stats" :key="s.label">
            <div>
              <p class="stat-label">{{ s.label }}</p>
              <p class="stat-value" :style="{ color: s.valueColor || '#1f2937' }">{{ s.value }}</p>
            </div>
            <div class="stat-icon" :style="{ background: s.iconBg, color: s.iconColor }">
              <span v-html="s.icon"></span>
            </div>
          </div>
        </div>

        <!-- Reminder Table -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Reminder Servis</h3>
            <span class="card-badge">{{ reminders.length }} item</span>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>KENDARAAN</th>
                  <th class="text-center">JENIS SERVIS</th>
                  <th class="text-right">STATUS</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="r in reminders"
                  :key="r.id"
                  @click="$router.push('/monitoring-kendaraan/1')"
                  class="clickable"
                >
                  <td><span class="fw-semibold">{{ r.vehicle }}</span></td>
                  <td class="text-center">{{ r.component }}</td>
                  <td class="text-right">
                    <span class="badge" :class="r.badgeClass">{{ r.status }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Sidebar from '@/components/Sidebar.vue'

export default {
  name: 'DashboardView',
  components: { Sidebar },
  data() {
    return {
      stats: [
        {
          label: 'Total Kendaraan',
          value: 12,
          icon: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5H5v5zM5 12l2-7h10l2 7"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>',
          iconBg: '#ede9fe', iconColor: '#7c3aed'
        },
        {
          label: 'Total Supplier',
          value: 8,
          icon: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
          iconBg: '#e0e7ff', iconColor: '#4338ca'
        },
        {
          label: 'Total Barang',
          value: 47,
          icon: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>',
          iconBg: '#dcfce7', iconColor: '#16a34a'
        },
        {
          label: 'Reminder Servis',
          value: 5,
          valueColor: '#ef4444',
          icon: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',
          iconBg: '#fee2e2', iconColor: '#dc2626'
        }
      ],
      reminders: [
        { id: 1, vehicle: 'B 1234 ABC - Hino', component: 'Oli Mesin', status: 'Kritis < 500 KM', badgeClass: 'badge-red' },
        { id: 2, vehicle: 'D 5678 XYZ - Mitsubishi', component: 'Ban Depan', status: 'Segera < 2000 KM', badgeClass: 'badge-yellow' },
        { id: 3, vehicle: 'F 9012 GHI - Isuzu', component: 'Aki', status: 'Perhatian < 4000 KM', badgeClass: 'badge-orange' },
        { id: 4, vehicle: 'AB 3456 JKL - Toyota', component: 'Filter Udara', status: 'Kritis < 7 Hari', badgeClass: 'badge-red' },
        { id: 5, vehicle: 'B 7890 MNO - Hino', component: 'Kopling', status: 'Segera < 12 Hari', badgeClass: 'badge-yellow' }
      ]
    }
  },
  methods: {
    refresh() {
      // Simulasi refresh
      const oldVal = this.stats[3].value
      this.stats[3].value = oldVal
    }
  }
}
</script>

<style scoped>
.page-layout { display: flex; min-height: 100vh; }
.main-content { flex: 1; background: #f5f6fa; display: flex; flex-direction: column; min-width: 0; }
.topbar { background: #fff; border-bottom: 1px solid #e8ecf0; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; }
.page-title { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; }
.page-sub { font-size: 0.8rem; color: #9ca3af; margin-top: 2px; }
.btn-refresh { display: flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #e8ecf0; border-radius: 8px; font-size: 0.85rem; color: #374151; cursor: pointer; }
.content-body { flex: 1; padding: 1.5rem 2rem; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
.stat-card { background: #fff; border-radius: 12px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
.stat-label { font-size: 0.8rem; color: #9ca3af; margin-bottom: 4px; }
.stat-value { font-size: 2rem; font-weight: 700; }
.stat-icon { width: 52px; height: 52px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
.card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.75rem; }
.card-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.card-badge { padding: 0.2rem 0.6rem; background: #f1f5f9; border-radius: 999px; font-size: 0.75rem; color: #6b7280; }
.table-wrap { overflow-x: auto; }
.table { width: 100%; border-collapse: collapse; }
.table thead th { padding: 0.75rem 1.5rem; font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; background: #f9fafb; text-align: left; }
.table tbody td { padding: 1rem 1.5rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f1f5f9; }
.table tbody tr:last-child td { border-bottom: none; }
.clickable { cursor: pointer; }
.clickable:hover td { background: #f5f7ff; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.fw-semibold { font-weight: 600; }
.badge { display: inline-flex; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
.badge-red { background: #fee2e2; color: #991b1b; }
.badge-yellow { background: #fef9c3; color: #854d0e; }
.badge-orange { background: #ffedd5; color: #9a3412; }
@media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
