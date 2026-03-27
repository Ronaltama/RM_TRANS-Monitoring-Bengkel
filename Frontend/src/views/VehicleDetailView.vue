<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <button class="back-btn" @click="$router.push('/monitoring-kendaraan')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><polyline points="15,18 9,12 15,6"/></svg>
          </button>
          <div>
            <div class="vehicle-title-row">
              <h1 class="page-title">{{ vehicle.plat_nomor }}</h1>
              <span class="status-badge">Aktif</span>
            </div>
            <p class="page-sub">{{ vehicle.jenis_kendaraan }}</p>
          </div>
        </div>

        <!-- KM Card -->
        <div class="km-card">
          <p class="km-label">Current Mileage</p>
          <div class="km-row">
            <span class="km-value">{{ formatNumber(vehicle.total_km) }}</span>
            <span class="km-unit">km</span>
            <button class="edit-km-btn" @click="showUpdateKm = true" title="Update KM">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
          </div>
        </div>
      </header>

      <div class="content-body">
        <div class="card">
          <!-- Section Header -->
          <div class="section-header">
            <div>
              <h2 class="section-title">Component Monitoring</h2>
              <p class="section-sub">{{ filteredComponents.length }} komponen</p>
            </div>
            <div class="section-actions">
              <div class="filter-tabs">
                <button
                  v-for="cat in filterCategories"
                  :key="cat.value"
                  @click="selectedFilter = cat.value"
                  class="filter-tab"
                  :class="{ active: selectedFilter === cat.value }"
                >{{ cat.label }}</button>
              </div>
              <button class="btn-primary" @click="showAddComponent = true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Component
              </button>
            </div>
          </div>

          <!-- Search -->
          <div class="search-row">
            <div class="search-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
              <input v-model="searchQuery" type="text" placeholder="Cari komponen..." />
            </div>
          </div>

          <!-- Component List -->
          <div class="component-list">
            <div v-if="filteredComponents.length === 0" class="empty-state">
              <p>Tidak ada komponen ditemukan</p>
            </div>

            <div
              v-else
              v-for="comp in filteredComponents"
              :key="comp.id"
              class="component-item"
            >
              <div class="comp-icon" :class="getIconClass(comp.health)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m5.2-13.2l-4.2 4.2m0 6l4.2 4.2M23 12h-6m-6 0H1"/></svg>
              </div>
              <div class="comp-info">
                <div class="comp-name-row">
                  <span class="comp-name">{{ comp.name }}</span>
                  <span class="comp-category">{{ comp.category }}</span>
                </div>
                <!-- Progress bar: menampilkan seberapa "habis" komponen (pemakaian) -->
                <div class="progress-bar">
                  <div class="progress-fill" :class="getBarClass(comp.health)" :style="{ width: (100 - comp.health) + '%' }"></div>
                </div>
                <div class="comp-stats">
                  <span :class="getHealthClass(comp.health)">{{ comp.health }}% health</span>
                  <span class="dot">•</span>
                  <span>{{ comp.remaining }}</span>
                </div>
              </div>
              <div class="comp-actions">
                <button class="icon-btn green" title="Mark Complete" @click="markComplete(comp)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><polyline points="20,6 9,17 4,12"/></svg>
                </button>
                <button class="icon-btn blue" title="Edit" @click="selectedComponent = comp; showEditComponent = true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="icon-btn delete" title="Hapus" @click="deleteComp(comp.id)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19,6V20a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Update KM Modal (mock) -->
    <div v-if="showUpdateKm" class="modal-overlay" @click.self="showUpdateKm = false">
      <div class="modal-box">
        <div class="modal-header">
          <h3 class="modal-title">Update Kilometer</h3>
          <button class="close-btn" @click="showUpdateKm = false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <div class="info-box">
            <p class="info-label">Current KM</p>
            <p class="info-value">{{ formatNumber(vehicle.total_km) }} km</p>
          </div>
          <div class="form-group">
            <label>Tambah KM</label>
            <input v-model.number="kmToAdd" type="number" min="0" placeholder="Contoh: 500" />
            <p class="hint">Jarak yang ditempuh</p>
          </div>
          <div class="preview-box">
            <p class="pv-label">New Total</p>
            <p class="pv-value">{{ formatNumber(vehicle.total_km + (kmToAdd || 0)) }} km</p>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-cancel" @click="showUpdateKm = false">Cancel</button>
          <button class="btn-submit" @click="updateKm">Update</button>
        </div>
      </div>
    </div>

    <!-- Add Component Modal (mock) -->
    <div v-if="showAddComponent" class="modal-overlay" @click.self="showAddComponent = false">
      <div class="modal-box">
        <div class="modal-header">
          <h3 class="modal-title">Add New Component</h3>
          <button class="close-btn" @click="showAddComponent = false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Komponen</label>
            <input v-model="newComp.name" type="text" placeholder="e.g., Front Right Tire" />
          </div>
          <div class="form-group">
            <label>Kategori</label>
            <select v-model="newComp.category">
              <option value="">-- Pilih Kategori --</option>
              <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
            </select>
          </div>
          <div class="form-group">
            <label>Target KM</label>
            <input v-model.number="newComp.targetKm" type="number" placeholder="e.g., 10000" />
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-cancel" @click="showAddComponent = false">Cancel</button>
          <button class="btn-submit" @click="addComponent">Add</button>
        </div>
      </div>
    </div>

    <!-- Edit Component Modal (mock) -->
    <div v-if="showEditComponent && selectedComponent" class="modal-overlay" @click.self="showEditComponent = false">
      <div class="modal-box">
        <div class="modal-header">
          <h3 class="modal-title">Edit Component</h3>
          <button class="close-btn" @click="showEditComponent = false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Komponen</label>
            <input v-model="selectedComponent.name" type="text" />
          </div>
          <div class="form-group">
            <label>Kategori</label>
            <select v-model="selectedComponent.category">
              <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-cancel" @click="showEditComponent = false">Cancel</button>
          <button class="btn-submit" @click="showEditComponent = false">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from '@/components/Sidebar.vue'

export default {
  name: 'VehicleDetailView',
  components: { Sidebar },
  data() {
    return {
      searchQuery: '',
      selectedFilter: 'all',
      filterCategories: [
        { label: 'All', value: 'all' },
        { label: 'Ban', value: 'Ban' },
        { label: 'Oli', value: 'Oli' },
        { label: 'Accu', value: 'Accu' }
      ],
      showUpdateKm: false,
      showAddComponent: false,
      showEditComponent: false,
      selectedComponent: null,
      kmToAdd: 0,
      newComp: { name: '', category: '', targetKm: 10000 },
      categories: ['Ban', 'Oli', 'Accu', 'Rem', 'Kopling', 'Filter', 'Lainnya'],
      // Dummy vehicle based on route id
      vehicles: {
        1: { plat_nomor: 'B 1234 ABC', jenis_kendaraan: 'Hino', total_km: 87500 },
        2: { plat_nomor: 'D 5678 XYZ', jenis_kendaraan: 'Mitsubishi', total_km: 55200 },
        3: { plat_nomor: 'F 9012 GHI', jenis_kendaraan: 'Isuzu', total_km: 120000 },
        4: { plat_nomor: 'AB 3456 JKL', jenis_kendaraan: 'Toyota', total_km: 34800 },
        5: { plat_nomor: 'B 7890 MNO', jenis_kendaraan: 'Hino', total_km: 98700 },
        6: { plat_nomor: 'L 1111 PQR', jenis_kendaraan: 'Mitsubishi', total_km: 67300 }
      },
      nextCompId: 10,
      components: [
        { id: 1, name: 'Ban Depan Kiri', category: 'Ban', health: 25, remaining: '450 km lagi' },
        { id: 2, name: 'Ban Depan Kanan', category: 'Ban', health: 30, remaining: '200 km lagi' },
        { id: 3, name: 'Ban Belakang Kiri', category: 'Ban', health: 65, remaining: '3,500 km lagi' },
        { id: 4, name: 'Ban Belakang Kanan', category: 'Ban', health: 70, remaining: '4,000 km lagi' },
        { id: 5, name: 'Oli Mesin', category: 'Oli', health: 15, remaining: '5 hari lagi' },
        { id: 6, name: 'Oli Transmisi', category: 'Oli', health: 55, remaining: '25 hari lagi' },
        { id: 7, name: 'Accu', category: 'Accu', health: 80, remaining: '90 hari lagi' },
        { id: 8, name: 'Rem Depan', category: 'Rem', health: 45, remaining: '2,200 km lagi' },
        { id: 9, name: 'Filter Udara', category: 'Filter', health: 60, remaining: '3,000 km lagi' }
      ]
    }
  },
  computed: {
    vehicle() {
      const id = this.$route.params.id
      return this.vehicles[id] || { plat_nomor: 'B 1234 ABC', jenis_kendaraan: 'Hino', total_km: 87500 }
    },
    filteredComponents() {
      let list = this.components
      if (this.selectedFilter !== 'all') {
        list = list.filter(c => c.category === this.selectedFilter)
      }
      if (this.searchQuery) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(c => c.name.toLowerCase().includes(q) || c.category.toLowerCase().includes(q))
      }
      return [...list].sort((a, b) => a.health - b.health)
    }
  },
  methods: {
    formatNumber(n) { return (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') },
    getIconClass(h) { if (h < 30) return 'icon-red'; if (h < 60) return 'icon-orange'; return 'icon-green' },
    getBarClass(h) { if (h < 30) return 'bg-red'; if (h < 60) return 'bg-orange'; return 'bg-green' },
    getHealthClass(h) { if (h < 30) return 'text-red'; if (h < 60) return 'text-orange'; return 'text-green' },
    updateKm() {
      if (this.kmToAdd > 0) {
        const id = this.$route.params.id
        if (this.vehicles[id]) this.vehicles[id].total_km += this.kmToAdd
      }
      this.showUpdateKm = false
      this.kmToAdd = 0
    },
    markComplete(comp) {
      if (!confirm('Mark "' + comp.name + '" as completed? Health akan direset ke 100%.')) return
      comp.health = 100
      comp.remaining = 'Baru direset'
    },
    deleteComp(id) {
      if (!confirm('Hapus komponen ini?')) return
      this.components = this.components.filter(c => c.id !== id)
    },
    addComponent() {
      if (!this.newComp.name || !this.newComp.category) return
      this.components.push({
        id: this.nextCompId++,
        name: this.newComp.name,
        category: this.newComp.category,
        health: 100,
        remaining: this.formatNumber(this.newComp.targetKm) + ' km lagi'
      })
      this.newComp = { name: '', category: '', targetKm: 10000 }
      this.showAddComponent = false
    }
  }
}
</script>

<style scoped>
.page-layout { display: flex; min-height: 100vh; }
.main-content { flex: 1; background: #f5f6fa; display: flex; flex-direction: column; }
.topbar { background: #fff; border-bottom: 1px solid #e8ecf0; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.topbar-left { display: flex; align-items: center; gap: 1rem; }
.back-btn { padding: 8px; border: none; background: #f5f6fa; border-radius: 8px; cursor: pointer; color: #374151; }
.back-btn:hover { background: #e8ecf0; }
.vehicle-title-row { display: flex; align-items: center; gap: 0.75rem; }
.page-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
.status-badge { padding: 0.2rem 0.65rem; background: #dcfce7; color: #15803d; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
.page-sub { font-size: 0.85rem; color: #9ca3af; margin-top: 2px; }
.km-card { background: #fff; border: 1px solid #e8ecf0; border-radius: 12px; padding: 1rem 1.25rem; min-width: 180px; }
.km-label { font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.km-row { display: flex; align-items: baseline; gap: 6px; }
.km-value { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
.km-unit { font-size: 0.875rem; color: #9ca3af; }
.edit-km-btn { padding: 4px; border: none; background: none; border-radius: 4px; cursor: pointer; color: #9ca3af; }
.edit-km-btn:hover { color: #4f46e5; background: #f0f4ff; }
.content-body { flex: 1; padding: 1.5rem 2rem; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
.section-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
.section-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.section-sub { font-size: 0.8rem; color: #9ca3af; }
.section-actions { display: flex; align-items: center; gap: 0.75rem; }
.filter-tabs { display: flex; gap: 4px; }
.filter-tab { padding: 0.35rem 0.75rem; border: none; background: none; border-radius: 6px; font-size: 0.8rem; font-weight: 500; color: #6b7280; cursor: pointer; }
.filter-tab.active { background: #e0e7ff; color: #3730a3; }
.filter-tab:hover:not(.active) { background: #f3f4f6; }
.btn-primary { display: flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.8rem; font-weight: 500; cursor: pointer; }
.search-row { padding: 0.75rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.search-wrap { position: relative; max-width: 320px; }
.search-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
.search-wrap input { width: 100%; padding: 0.55rem 1rem 0.55rem 34px; border: 1px solid #e8ecf0; border-radius: 8px; font-size: 0.875rem; outline: none; box-sizing: border-box; }
.search-wrap input:focus { border-color: #4f46e5; }
.component-item { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.component-item:last-child { border-bottom: none; }
.comp-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.icon-red { background: #fef2f2; color: #ef4444; }
.icon-orange { background: #fff7ed; color: #f97316; }
.icon-green { background: #f0fdf4; color: #22c55e; }
.comp-info { flex: 1; min-width: 0; }
.comp-name-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.comp-name { font-size: 0.875rem; font-weight: 600; color: #1f2937; }
.comp-category { padding: 0.15rem 0.5rem; background: #f3f4f6; color: #6b7280; border-radius: 4px; font-size: 0.7rem; }
.progress-bar { background: #e5e7eb; border-radius: 999px; height: 8px; margin-bottom: 6px; overflow: hidden; }
.progress-fill { height: 100%; border-radius: 999px; transition: width 0.3s; }
.bg-green { background: #22c55e; }
.bg-orange { background: #f97316; }
.bg-red { background: #ef4444; }
.comp-stats { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #9ca3af; }
.dot { color: #d1d5db; }
.text-green { color: #16a34a; font-weight: 600; }
.text-orange { color: #ea580c; font-weight: 600; }
.text-red { color: #dc2626; font-weight: 600; }
.comp-actions { display: flex; gap: 4px; flex-shrink: 0; }
.icon-btn { padding: 6px; border: none; background: none; border-radius: 6px; cursor: pointer; color: #9ca3af; }
.icon-btn.green:hover { color: #22c55e; background: #f0fdf4; }
.icon-btn.blue:hover { color: #3b82f6; background: #eff6ff; }
.icon-btn.delete:hover { color: #ef4444; background: #fef2f2; }
.empty-state { padding: 3rem; text-align: center; color: #9ca3af; }
/* Modals */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
.modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 440px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.modal-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.close-btn { background: none; border: none; color: #9ca3af; cursor: pointer; }
.modal-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.info-box { background: #f9fafb; border-radius: 8px; padding: 0.875rem 1rem; }
.info-label { font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.info-value { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
.preview-box { background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 8px; padding: 0.875rem 1rem; }
.pv-label { font-size: 0.7rem; font-weight: 600; color: #4f46e5; text-transform: uppercase; margin-bottom: 4px; }
.pv-value { font-size: 1.4rem; font-weight: 700; color: #3730a3; }
.form-group { display: flex; flex-direction: column; gap: 4px; }
label { font-size: 0.85rem; font-weight: 500; color: #374151; }
input[type="text"], input[type="number"], select { padding: 0.6rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; outline: none; }
input:focus, select:focus { border-color: #4f46e5; }
.hint { font-size: 0.75rem; color: #9ca3af; }
.modal-footer { display: flex; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; }
.btn-cancel { padding: 0.6rem 1.25rem; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
.btn-submit { padding: 0.6rem 1.25rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
</style>
