<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <h2 class="page-title">Monitoring Kendaraan</h2>
          <p class="page-sub"><span class="accent">{{ monitoringList.length }}</span> kendaraan aktif</p>
        </div>
        <div class="topbar-actions">
          <button class="btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2">
              <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Download Report
          </button>
          <button class="btn-primary" @click="showAdd = true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2.5">
              <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kendaraan
          </button>
        </div>
      </header>

      <div class="content-body">
        <div class="card">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div>
                <span class="card-title">Daftar Monitoring</span>
                <span class="card-badge">{{ filteredList.length }}</span>
              </div>
            </div>
            <div class="search-wrap">
              <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
              </svg>
              <input v-model="searchQuery" type="text" placeholder="Cari plat nomor atau jenis..." />
              <button v-if="searchQuery" class="clear-btn" @click="searchQuery = ''">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="12" height="12" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
          </div>

          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Plat Nomor</th>
                  <th>Jenis Kendaraan</th>
                  <th>Total KM</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="filteredList.length === 0">
                  <td colspan="5" class="empty-row">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <p>Tidak ada kendaraan ditemukan</p>
                  </td>
                </tr>
                <tr
                  v-else
                  v-for="item in filteredList"
                  :key="item.id"
                  @click="$router.push('/monitoring-kendaraan/' + item.id)"
                  class="clickable"
                >
                  <td>
                    <div class="nopol-cell">
                      <span class="nopol-text">{{ item.plat_nomor }}</span>
                      <span v-if="item.critical > 0" class="critical-badge">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L1 21h22L12 2z"/></svg>
                        {{ item.critical }} kritis
                      </span>
                    </div>
                  </td>
                  <td>
                    <span class="merk-chip">{{ item.jenis_kendaraan }}</span>
                  </td>
                  <td>
                    <span class="km-text">{{ formatNumber(item.total_km) }} <span class="km-unit">km</span></span>
                  </td>
                  <td>
                    <span class="status-dot" :class="item.critical > 0 ? 'dot-warn' : 'dot-ok'"></span>
                    <span class="status-text" :class="item.critical > 0 ? 'text-warn' : 'text-ok'">
                      {{ item.critical > 0 ? 'Perlu Perhatian' : 'Normal' }}
                    </span>
                  </td>
                  <td @click.stop>
                    <button class="icon-btn delete" @click="removeVehicle(item.id)" title="Hapus">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19,6V20a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6"/></svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <AddVehicleToMonitoringModal
      :is-open="showAdd"
      @close="showAdd = false"
      @vehicle-added="handleVehicleAdded"
    />
  </div>
</template>

<script>
import Sidebar from '@/components/Sidebar.vue'
import AddVehicleToMonitoringModal from '@/components/AddVehicleToMonitoringModal.vue'

export default {
  name: 'MonitoringKendaraanView',
  components: { Sidebar, AddVehicleToMonitoringModal },
  data() {
    return {
      searchQuery: '',
      showAdd: false,
      monitoringList: [
        { id: 1, plat_nomor: 'B 1234 ABC', jenis_kendaraan: 'Hino', total_km: 87500, critical: 2 },
        { id: 2, plat_nomor: 'D 5678 XYZ', jenis_kendaraan: 'Mitsubishi', total_km: 55200, critical: 1 },
        { id: 3, plat_nomor: 'F 9012 GHI', jenis_kendaraan: 'Isuzu', total_km: 120000, critical: 0 },
        { id: 4, plat_nomor: 'AB 3456 JKL', jenis_kendaraan: 'Toyota', total_km: 34800, critical: 3 },
        { id: 5, plat_nomor: 'B 7890 MNO', jenis_kendaraan: 'Hino', total_km: 98700, critical: 0 },
        { id: 6, plat_nomor: 'L 1111 PQR', jenis_kendaraan: 'Mitsubishi', total_km: 67300, critical: 1 }
      ]
    }
  },
  computed: {
    filteredList() {
      if (!this.searchQuery) return this.monitoringList
      const q = this.searchQuery.toLowerCase()
      return this.monitoringList.filter(i =>
        i.plat_nomor.toLowerCase().includes(q) || i.jenis_kendaraan.toLowerCase().includes(q)
      )
    }
  },
  methods: {
    handleVehicleAdded() { this.showAdd = false },
    removeVehicle(id) {
      if (!confirm('Hapus kendaraan ini dari monitoring?')) return
      this.monitoringList = this.monitoringList.filter(i => i.id !== id)
    },
    formatNumber(n) { return (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') }
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
</style>

<style scoped>
.page-layout { display: flex; min-height: 100vh; font-family: 'Poppins', sans-serif; }
.main-content { flex: 1; background: #f0f0f8; display: flex; flex-direction: column; min-width: 0; }

.topbar { background: #fff; border-bottom: 1px solid #e8e8f0; padding: 1.1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; }
.page-title { font-size: 1.2rem; font-weight: 700; color: #1e1d4c; }
.page-sub { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; }
.accent { color: #3E3D90; font-weight: 600; }
.topbar-actions { display: flex; gap: 0.65rem; }

.btn-primary { display: flex; align-items: center; gap: 6px; padding: 0.55rem 1.1rem; background: #3E3D90; border: none; border-radius: 10px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(62,61,144,0.3); transition: background 0.15s, transform 0.1s; }
.btn-primary:hover { background: #4c4bb0; transform: translateY(-1px); }
.btn-secondary { display: flex; align-items: center; gap: 6px; padding: 0.55rem 1rem; background: #fff; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 500; color: #374151; cursor: pointer; transition: all 0.15s; }
.btn-secondary:hover { border-color: #3E3D90; color: #3E3D90; background: #f5f5fb; }

.content-body { flex: 1; padding: 1.75rem 2rem; }

.card { background: #fff; border-radius: 14px; border: 1px solid #e8e8f0; overflow: hidden; }
.card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f0f8; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.card-header-left { display: flex; align-items: center; gap: 0.7rem; }
.card-icon { width: 34px; height: 34px; background: #ede9fe; color: #6d28d9; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.card-title { font-size: 0.92rem; font-weight: 600; color: #1e1d4c; margin-right: 0.5rem; }
.card-badge { padding: 0.2rem 0.65rem; background: #f0f0f8; border-radius: 999px; font-size: 0.72rem; font-weight: 600; color: #6b7280; }

.search-wrap { position: relative; }
.search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
.search-wrap input { padding: 0.6rem 2rem 0.6rem 33px; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; outline: none; background: #fff; color: #374151; width: 260px; transition: border-color 0.2s, box-shadow 0.2s; }
.search-wrap input:focus { border-color: #3E3D90; box-shadow: 0 0 0 3px rgba(62,61,144,0.1); }
.search-wrap input::placeholder { color: #c0c0d0; }
.clear-btn { position: absolute; right: 9px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; padding: 2px; display: flex; }

.table-wrap { overflow-x: auto; }
.table { width: 100%; border-collapse: collapse; }
.table thead th { padding: 0.7rem 1.25rem; font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; background: #fafafa; text-align: left; border-bottom: 1px solid #f0f0f8; }
.table tbody td { padding: 0.95rem 1.25rem; font-size: 0.85rem; color: #374151; border-bottom: 1px solid #f5f5fb; vertical-align: middle; }
.table tbody tr:last-child td { border-bottom: none; }
.clickable { cursor: pointer; }
.clickable:hover td { background: #faf9ff; }

.nopol-cell { display: flex; align-items: center; gap: 9px; }
.nopol-text { font-weight: 700; color: #1e1d4c; letter-spacing: 0.3px; }
.critical-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background: #fee2e2; color: #991b1b; border-radius: 999px; font-size: 0.68rem; font-weight: 700; }

.merk-chip { display: inline-flex; padding: 0.22rem 0.7rem; background: #f5f5fb; border: 1px solid #e8e8f0; border-radius: 6px; font-size: 0.78rem; font-weight: 500; color: #4b5563; }

.km-text { font-weight: 600; color: #1e1d4c; }
.km-unit { font-size: 0.75rem; font-weight: 400; color: #9ca3af; }

.status-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; margin-right: 7px; vertical-align: middle; }
.dot-ok { background: #22c55e; box-shadow: 0 0 5px rgba(34,197,94,0.5); }
.dot-warn { background: #ef4444; box-shadow: 0 0 5px rgba(239,68,68,0.5); }
.status-text { font-size: 0.8rem; font-weight: 500; }
.text-ok { color: #16a34a; }
.text-warn { color: #dc2626; }

.empty-row { text-align: center; padding: 3rem !important; color: #9ca3af; }
.empty-row svg { margin: 0 auto 0.5rem; display: block; }
.empty-row p { font-size: 0.85rem; }

.icon-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none; background: none; border-radius: 8px; cursor: pointer; color: #b0b0c8; transition: all 0.15s; }
.icon-btn.delete:hover { color: #ef4444; background: #fff0f0; }
</style>