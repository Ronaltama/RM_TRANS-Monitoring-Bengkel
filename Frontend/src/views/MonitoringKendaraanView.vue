<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">
      <header class="topbar">
        <div>
          <h2 class="page-title">Monitoring Kendaraan</h2>
          <p class="page-sub"><span class="accent">{{ monitoringList.length }}</span> kendaraan aktif</p>
        </div>
        <div class="topbar-actions">
          <button class="btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Report
          </button>
          <button class="btn-primary" @click="showAdd = true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Vehicle
          </button>
        </div>
      </header>

      <div class="content-body">
        <div class="card">
          <div class="card-header">
            <div class="search-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
              <input v-model="searchQuery" type="text" placeholder="Cari plat nomor atau jenis kendaraan..." />
            </div>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>PLAT NOMOR</th>
                  <th>JENIS KENDARAAN</th>
                  <th>TOTAL KM</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="filteredList.length === 0">
                  <td colspan="4" class="empty-row">Tidak ada kendaraan ditemukan</td>
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
                      <span class="fw-semibold">{{ item.plat_nomor }}</span>
                      <span v-if="item.critical > 0" class="critical-badge">{{ item.critical }}</span>
                    </div>
                  </td>
                  <td>{{ item.jenis_kendaraan }}</td>
                  <td class="fw-semibold">{{ formatNumber(item.total_km) }} km</td>
                  <td @click.stop>
                    <button class="icon-btn delete" @click="removeVehicle(item.id)" title="Hapus">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19,6V20a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6"/></svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- Add Modal -->
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

<style scoped>
.page-layout { display: flex; min-height: 100vh; }
.main-content { flex: 1; background: #f5f6fa; display: flex; flex-direction: column; }
.topbar { background: #fff; border-bottom: 1px solid #e8ecf0; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; }
.page-title { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; }
.page-sub { font-size: 0.8rem; color: #9ca3af; }
.accent { color: #4f46e5; font-weight: 600; }
.topbar-actions { display: flex; gap: 0.75rem; }
.btn-primary { display: flex; align-items: center; gap: 6px; padding: 0.6rem 1.25rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
.btn-secondary { display: flex; align-items: center; gap: 6px; padding: 0.6rem 1rem; background: #fff; border: 1px solid #e8ecf0; border-radius: 8px; font-size: 0.875rem; color: #374151; cursor: pointer; }
.content-body { flex: 1; padding: 1.5rem 2rem; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
.card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.search-wrap { position: relative; max-width: 380px; }
.search-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
.search-wrap input { width: 100%; padding: 0.6rem 1rem 0.6rem 34px; border: 1px solid #e8ecf0; border-radius: 8px; font-size: 0.875rem; outline: none; box-sizing: border-box; }
.search-wrap input:focus { border-color: #4f46e5; }
.table-wrap { overflow-x: auto; }
.table { width: 100%; border-collapse: collapse; }
.table thead th { padding: 0.7rem 1.25rem; font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; background: #f9fafb; text-align: left; }
.table tbody td { padding: 1rem 1.25rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f1f5f9; }
.clickable { cursor: pointer; }
.clickable:hover td { background: #f5f7ff; }
.nopol-cell { display: flex; align-items: center; gap: 8px; }
.fw-semibold { font-weight: 600; }
.critical-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; padding: 0 6px; background: #ef4444; color: #fff; border-radius: 999px; font-size: 0.7rem; font-weight: 700; }
.empty-row { text-align: center; padding: 3rem; color: #9ca3af; }
.icon-btn { padding: 6px; border: none; background: none; border-radius: 6px; cursor: pointer; color: #9ca3af; }
.icon-btn.delete:hover { color: #ef4444; background: #fef2f2; }
</style>
