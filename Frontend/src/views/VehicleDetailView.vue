<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">

      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <button class="back-btn" @click="$router.push('/monitoring-kendaraan')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2"><polyline points="15,18 9,12 15,6"/></svg>
          </button>
          <div>
            <div class="vehicle-title-row">
              <h1 class="page-title">{{ vehicle.plat_nomor }}</h1>
              <span class="status-badge">Aktif</span>
            </div>
            <p class="page-sub">{{ vehicle.jenis_kendaraan }}</p>
          </div>
        </div>

        <div class="km-card" @click="showUpdateKm = true" title="Klik untuk update KM">
          <p class="km-label">Total Kilometer</p>
          <div class="km-row">
            <span class="km-value">{{ formatNumber(vehicle.total_km) }}</span>
            <span class="km-unit">km</span>
            <span class="edit-km-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="13" height="13" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </span>
          </div>
        </div>
      </header>

      <div class="content-body">
        <div class="card">

          <!-- Section Header -->
          <div class="section-header">
            <div>
              <h2 class="section-title">Component Monitoring</h2>
              <p class="section-sub">{{ filteredComponents.length }} komponen ditampilkan</p>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="13" height="13" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Komponen
              </button>
            </div>
          </div>

          <!-- Search -->
          <div class="search-row">
            <div class="search-wrap">
              <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
              <input v-model="searchQuery" type="text" placeholder="Cari komponen..." />
            </div>
          </div>

          <!-- Component List -->
          <div class="component-list">
            <div v-if="filteredComponents.length === 0" class="empty-state">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m5.2-13.2l-4.2 4.2m0 6l4.2 4.2M23 12h-6m-6 0H1"/></svg>
              <p>Tidak ada komponen ditemukan</p>
            </div>

            <div
              v-else
              v-for="comp in filteredComponents"
              :key="comp.id"
              class="component-item"
            >
              <div class="comp-icon" :class="getIconClass(comp.health)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6m5.2-13.2l-4.2 4.2m0 6l4.2 4.2M23 12h-6m-6 0H1"/></svg>
              </div>
              <div class="comp-info">
                <div class="comp-name-row">
                  <span class="comp-name">{{ comp.name }}</span>
                  <span class="comp-category">{{ comp.category }}</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill" :class="getBarClass(comp.health)" :style="{ width: (100 - comp.health) + '%' }"></div>
                </div>
                <div class="comp-stats">
                  <span :class="getHealthClass(comp.health)">{{ comp.health }}% health</span>
                  <span class="dot">·</span>
                  <span class="remaining-text">{{ comp.remaining }}</span>
                </div>
              </div>
              <div class="comp-actions">
                <button class="icon-btn green" title="Mark Complete" @click="markComplete(comp)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg>
                </button>
                <button class="icon-btn blue" title="Edit" @click="selectedComponent = comp; showEditComponent = true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="icon-btn delete" title="Hapus" @click="deleteComp(comp.id)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19,6V20a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Update KM Modal -->
    <transition name="modal-fade">
      <div v-if="showUpdateKm" class="modal-overlay" @click.self="showUpdateKm = false">
        <div class="modal-box">
          <div class="modal-header">
            <div class="modal-header-left">
              <div class="modal-icon" style="background:#f0f0fb;color:#3E3D90;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </div>
              <div>
                <h3 class="modal-title">Update Kilometer</h3>
                <p class="modal-sub">Perbarui data jarak kendaraan</p>
              </div>
            </div>
            <button class="close-btn" @click="showUpdateKm = false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="info-box">
              <p class="info-label">Kilometer Saat Ini</p>
              <p class="info-value">{{ formatNumber(vehicle.total_km) }} <span class="info-unit">km</span></p>
            </div>
            <div class="form-group">
              <label>KM Saat Ini (di Speedometer)</label>
              <input v-model.number="newTotalKm" type="number" min="0" placeholder="Contoh: 85000" />
              <p class="hint">Masukkan angka KM tanpa pemisah (contoh: 85000)</p>
            </div>
            <div class="preview-box">
              <p class="preview-label">Selisih Jarak Ditempuh</p>
              <p class="preview-value" v-if="(newTotalKm || 0) > vehicle.total_km" style="color: #16a34a;">
                + {{ formatNumber((newTotalKm || 0) - vehicle.total_km) }} <span class="preview-unit">km</span>
              </p>
              <p class="preview-value" v-else-if="(newTotalKm || 0) > 0" style="color: #dc2626; font-size: 0.9rem;">
                Harus > {{ formatNumber(vehicle.total_km) }} km
              </p>
              <p class="preview-value" v-else>0 <span class="preview-unit">km</span></p>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" @click="showUpdateKm = false">Batal</button>
            <button class="btn-submit" @click="updateKm">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Update
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Add Component Modal -->
    <transition name="modal-fade">
      <div v-if="showAddComponent" class="modal-overlay" @click.self="showAddComponent = false">
        <div class="modal-box">
          <div class="modal-header">
            <div class="modal-header-left">
              <div class="modal-icon" style="background:#ede9fe;color:#6d28d9;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </div>
              <div>
                <h3 class="modal-title">Tambah Komponen</h3>
                <p class="modal-sub">Daftarkan komponen baru</p>
              </div>
            </div>
            <button class="close-btn" @click="showAddComponent = false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Komponen</label>
              <input v-model="newComp.name" type="text" placeholder="Contoh: Ban Depan Kiri" />
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select v-model="newComp.category">
                <option value="">— Pilih Kategori —</option>
                <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
              </select>
            </div>
            <div class="form-group">
              <label>Target KM</label>
              <input v-model.number="newComp.targetKm" type="number" placeholder="Contoh: 10000" />
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" @click="showAddComponent = false">Batal</button>
            <button class="btn-submit" @click="addComponent">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Tambah
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Edit Component Modal -->
    <transition name="modal-fade">
      <div v-if="showEditComponent && selectedComponent" class="modal-overlay" @click.self="showEditComponent = false">
        <div class="modal-box">
          <div class="modal-header">
            <div class="modal-header-left">
              <div class="modal-icon" style="background:#f0f0fb;color:#3E3D90;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </div>
              <div>
                <h3 class="modal-title">Edit Komponen</h3>
                <p class="modal-sub">{{ selectedComponent.name }}</p>
              </div>
            </div>
            <button class="close-btn" @click="showEditComponent = false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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
            <button class="btn-cancel" @click="showEditComponent = false">Batal</button>
            <button class="btn-submit" @click="showEditComponent = false">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Simpan
            </button>
          </div>
        </div>
      </div>
    </transition>
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
        { label: 'Semua', value: 'all' },
        { label: 'Ban', value: 'Ban' },
        { label: 'Oli', value: 'Oli' },
        { label: 'Accu', value: 'Accu' },
        { label: 'Rem', value: 'Rem' },
      ],
      showUpdateKm: false,
      showAddComponent: false,
      showEditComponent: false,
      selectedComponent: null,
      newTotalKm: null,
      newComp: { name: '', category: '', targetKm: 10000 },
      categories: ['Ban', 'Oli', 'Accu', 'Rem', 'Kopling', 'Filter', 'Lainnya'],
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
      if (this.selectedFilter !== 'all') list = list.filter(c => c.category === this.selectedFilter)
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
      if (this.newTotalKm > this.vehicle.total_km) {
        const id = this.$route.params.id
        if (this.vehicles[id]) this.vehicles[id].total_km = this.newTotalKm
      }
      this.showUpdateKm = false; this.newTotalKm = null
    },
    markComplete(comp) {
      if (!confirm('Reset "' + comp.name + '" ke 100%?')) return
      comp.health = 100; comp.remaining = 'Baru direset'
    },
    deleteComp(id) {
      if (!confirm('Hapus komponen ini?')) return
      this.components = this.components.filter(c => c.id !== id)
    },
    addComponent() {
      if (!this.newComp.name || !this.newComp.category) return
      this.components.push({ id: this.nextCompId++, name: this.newComp.name, category: this.newComp.category, health: 100, remaining: this.formatNumber(this.newComp.targetKm) + ' km lagi' })
      this.newComp = { name: '', category: '', targetKm: 10000 }; this.showAddComponent = false
    }
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
</style>

<style scoped>
.page-layout { display: flex; min-height: 100vh; font-family: 'Poppins', sans-serif; }
.main-content { flex: 1; background: #f0f0f8; display: flex; flex-direction: column; min-width: 0; }

/* TOPBAR */
.topbar { background: #fff; border-bottom: 1px solid #e8e8f0; min-height: 80px; box-sizing: border-box; padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; position: sticky; top: 0; z-index: 10; }
.topbar-left { display: flex; align-items: center; gap: 0.85rem; }
.back-btn { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 1.5px solid #e8e8f0; background: #f5f5fb; border-radius: 9px; cursor: pointer; color: #374151; transition: all 0.15s; flex-shrink: 0; }
.back-btn:hover { background: #ede9fe; border-color: #3E3D90; color: #3E3D90; }
.vehicle-title-row { display: flex; align-items: center; gap: 0.65rem; }
.page-title { font-size: 1.25rem; font-weight: 700; color: #1e1d4c; }
.status-badge { padding: 0.2rem 0.65rem; background: #dcfce7; color: #15803d; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
.page-sub { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; }

.km-card { background: #f5f5fb; border: 1.5px solid #e8e8f0; border-radius: 12px; padding: 0.75rem 1.1rem; min-width: 170px; cursor: pointer; transition: all 0.15s; }
.km-card:hover { border-color: #3E3D90; box-shadow: 0 0 0 3px rgba(62,61,144,0.1); }
.km-label { font-size: 0.68rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 3px; }
.km-row { display: flex; align-items: baseline; gap: 5px; }
.km-value { font-size: 1.4rem; font-weight: 700; color: #1e1d4c; }
.km-unit { font-size: 0.8rem; color: #9ca3af; }
.edit-km-btn { margin-left: 4px; color: #9ca3af; display: flex; align-items: center; }

/* CONTENT */
.content-body { flex: 1; padding: 1.75rem 2rem; }
.card { background: #fff; border-radius: 14px; border: 1px solid #e8e8f0; overflow: hidden; }

/* SECTION HEADER */
.section-header { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f0f0f8; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
.section-title { font-size: 0.95rem; font-weight: 600; color: #1e1d4c; }
.section-sub { font-size: 0.72rem; color: #9ca3af; margin-top: 1px; }
.section-actions { display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap; }

.filter-tabs { display: flex; gap: 3px; background: #f5f5fb; border-radius: 9px; padding: 3px; }
.filter-tab { padding: 0.3rem 0.7rem; border: none; background: none; border-radius: 7px; font-family: 'Poppins', sans-serif; font-size: 0.78rem; font-weight: 500; color: #6b7280; cursor: pointer; transition: all 0.15s; }
.filter-tab.active { background: #fff; color: #3E3D90; font-weight: 600; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
.filter-tab:hover:not(.active) { color: #3E3D90; }

.btn-primary { display: flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; background: #3E3D90; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 600; color: #fff; cursor: pointer; box-shadow: 0 4px 12px rgba(62,61,144,0.3); transition: background 0.15s, transform 0.1s; }
.btn-primary:hover { background: #4c4bb0; transform: translateY(-1px); }

/* SEARCH ROW */
.search-row { padding: 0.85rem 1.5rem; border-bottom: 1px solid #f0f0f8; }
.search-wrap { position: relative; max-width: 300px; }
.search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
.search-wrap input { width: 100%; padding: 0.6rem 1rem 0.6rem 32px; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; outline: none; background: #fff; color: #374151; transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
.search-wrap input:focus { border-color: #3E3D90; box-shadow: 0 0 0 3px rgba(62,61,144,0.1); }
.search-wrap input::placeholder { color: #c0c0d0; }

/* COMPONENTS */
.component-item { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #f5f5fb; transition: background 0.1s; }
.component-item:last-child { border-bottom: none; }
.component-item:hover { background: #faf9ff; }

.comp-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.icon-red { background: #fff0f0; color: #ef4444; }
.icon-orange { background: #fff7ed; color: #f97316; }
.icon-green { background: #f0fdf4; color: #22c55e; }

.comp-info { flex: 1; min-width: 0; }
.comp-name-row { display: flex; align-items: center; gap: 8px; margin-bottom: 7px; }
.comp-name { font-size: 0.85rem; font-weight: 600; color: #1e1d4c; }
.comp-category { padding: 2px 7px; background: #f5f5fb; border: 1px solid #e8e8f0; color: #6b7280; border-radius: 5px; font-size: 0.68rem; font-weight: 500; }

.progress-bar { background: #f0f0f8; border-radius: 999px; height: 6px; margin-bottom: 6px; overflow: hidden; }
.progress-fill { height: 100%; border-radius: 999px; transition: width 0.4s ease; }
.bg-green { background: #22c55e; }
.bg-orange { background: #f97316; }
.bg-red { background: #ef4444; }

.comp-stats { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; }
.dot { color: #d1d5db; }
.remaining-text { color: #9ca3af; }
.text-green { color: #16a34a; font-weight: 600; }
.text-orange { color: #ea580c; font-weight: 600; }
.text-red { color: #dc2626; font-weight: 600; }

.comp-actions { display: flex; gap: 4px; flex-shrink: 0; }
.icon-btn { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: none; background: none; border-radius: 7px; cursor: pointer; color: #b0b0c8; transition: all 0.15s; }
.icon-btn.green:hover { color: #22c55e; background: #f0fdf4; }
.icon-btn.blue:hover { color: #3E3D90; background: #f0f0fb; }
.icon-btn.delete:hover { color: #ef4444; background: #fff0f0; }

.empty-state { padding: 3rem; text-align: center; color: #9ca3af; }
.empty-state svg { margin: 0 auto 0.6rem; display: block; }
.empty-state p { font-size: 0.85rem; }

/* ===== MODALS ===== */
.modal-overlay { position: fixed; inset: 0; background: rgba(15,15,40,0.55); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 1rem; backdrop-filter: blur(2px); }
.modal-box { background: #fff; border-radius: 18px; width: 100%; max-width: 440px; box-shadow: 0 24px 64px rgba(0,0,0,0.18); font-family: 'Poppins', sans-serif; overflow: hidden; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 1.4rem; border-bottom: 1px solid #f0f0f8; }
.modal-header-left { display: flex; align-items: center; gap: 0.7rem; }
.modal-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.modal-title { font-size: 0.92rem; font-weight: 600; color: #1e1d4c; }
.modal-sub { font-size: 0.73rem; color: #9ca3af; margin-top: 1px; }
.close-btn { background: #f5f5fb; border: none; border-radius: 8px; color: #9ca3af; cursor: pointer; padding: 7px; display: flex; align-items: center; transition: all 0.15s; }
.close-btn:hover { background: #f0f0f0; color: #374151; }

.modal-body { padding: 1.1rem 1.4rem; display: flex; flex-direction: column; gap: 1rem; }
.info-box { background: #f5f5fb; border: 1px solid #e8e8f0; border-radius: 10px; padding: 0.85rem 1rem; }
.info-label { font-size: 0.68rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px; }
.info-value { font-size: 1.4rem; font-weight: 700; color: #1e1d4c; }
.info-unit { font-size: 0.85rem; color: #9ca3af; font-weight: 400; }

.form-group { display: flex; flex-direction: column; gap: 5px; }
label { font-size: 0.8rem; font-weight: 600; color: #3E3D90; text-transform: uppercase; letter-spacing: 0.3px; }
input[type="text"], input[type="number"], select { padding: 0.65rem 0.9rem; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.875rem; outline: none; background: #fff; color: #374151; box-sizing: border-box; width: 100%; transition: border-color 0.2s, box-shadow 0.2s; }
input:focus, select:focus { border-color: #3E3D90; box-shadow: 0 0 0 3px rgba(62,61,144,0.1); }
input::placeholder { color: #c0c0d0; }
.hint { font-size: 0.72rem; color: #b0b0c8; }

.preview-box { background: #f0f0fb; border: 1.5px solid #ddddfb; border-radius: 10px; padding: 0.85rem 1rem; }
.preview-label { font-size: 0.68rem; font-weight: 600; color: #3E3D90; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px; }
.preview-value { font-size: 1.4rem; font-weight: 700; color: #1e1d4c; }
.preview-unit { font-size: 0.85rem; color: #6b7280; font-weight: 400; }

.modal-footer { display: flex; justify-content: flex-end; gap: 0.65rem; padding: 1rem 1.4rem; border-top: 1px solid #f0f0f8; }
.btn-cancel { padding: 0.6rem 1.1rem; background: #fff; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 500; color: #6b7280; cursor: pointer; transition: all 0.15s; }
.btn-cancel:hover { background: #f5f5fb; }
.btn-submit { display: flex; align-items: center; gap: 6px; padding: 0.6rem 1.1rem; background: #3E3D90; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; color: #fff; cursor: pointer; box-shadow: 0 4px 12px rgba(62,61,144,0.3); transition: background 0.15s, transform 0.1s; }
.btn-submit:hover { background: #4c4bb0; transform: translateY(-1px); }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter, .modal-fade-leave-to { opacity: 0; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
  .topbar { flex-wrap: wrap; gap: 0.75rem; }
  .km-card { min-width: auto; }
}

@media (max-width: 768px) {
  .topbar {
    padding: 0.85rem 1rem 0.85rem 4rem;
    flex-wrap: wrap;
    gap: 0.75rem;
  }
  .page-title { font-size: 1.05rem; }
  .content-body { padding: 1rem; }
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    padding: 0.85rem 1rem;
    gap: 0.65rem;
  }
  .section-actions {
    width: 100%;
    justify-content: space-between;
  }
  .filter-tabs { flex-wrap: wrap; }
  .search-row { padding: 0.75rem 1rem; }
  .search-wrap { max-width: 100%; }
  .component-item { padding: 0.85rem 1rem; gap: 0.75rem; }
  .comp-name { font-size: 0.82rem; }
}

@media (max-width: 480px) {
  .topbar { padding: 0.75rem 0.75rem 0.75rem 3.5rem; }
  .topbar-left { gap: 0.6rem; }
  .page-title { font-size: 0.95rem; }
  .vehicle-title-row { flex-wrap: wrap; gap: 0.4rem; }
  .km-card { padding: 0.55rem 0.85rem; }
  .km-value { font-size: 1.15rem; }
  .btn-primary { padding: 0.45rem 0.75rem; font-size: 0.75rem; }
  .btn-primary svg { display: none; }
  .comp-icon { width: 32px; height: 32px; }
  .comp-actions .icon-btn { width: 26px; height: 26px; }
  .filter-tab { padding: 0.25rem 0.55rem; font-size: 0.72rem; }
  .modal-box { border-radius: 14px; margin: 0.5rem; }
}
</style>