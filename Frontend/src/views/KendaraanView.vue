<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <h2 class="page-title">Daftar Kendaraan</h2>
          <p class="page-sub">Kelola data kendaraan workshop</p>
        </div>
        <button class="btn-primary" @click="showAdd = true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Tambah Kendaraan
        </button>
      </header>

      <div class="content-body">

        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card stat-total">
            <div class="stat-icon-wrap">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5H5v5zM5 12l2-7h10l2 7"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
            </div>
            <div>
              <p class="stat-label">Total Kendaraan</p>
              <p class="stat-value">{{ kendaraanList.length }}</p>
            </div>
          </div>
          <div class="stat-card" v-for="m in merkList" :key="m.name">
            <div class="stat-icon-wrap" :style="{ background: m.bg, color: m.color }">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14v-5H5v5zM5 12l2-7h10l2 7"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
            </div>
            <div>
              <p class="stat-label">{{ m.name }}</p>
              <p class="stat-value">{{ getMerkCount(m.name) }}</p>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="filters-row">
          <div class="search-wrap">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="15" height="15" stroke-width="2">
              <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Cari nomor polisi atau jenis kendaraan..." />
            <button v-if="searchQuery" class="clear-btn" @click="searchQuery = ''">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="13" height="13" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
          <select v-model="selectedMerk" class="select-filter">
            <option value="">Semua Jenis</option>
            <option v-for="m in ['Hino','Mitsubishi','Isuzu','Toyota','Daihatsu']" :key="m" :value="m">{{ m }}</option>
          </select>
        </div>

        <!-- Table -->
        <div class="card">
          <div class="card-header">
            <div class="card-header-left">
              <span class="card-title">Daftar Kendaraan</span>
              <span class="card-badge">{{ filteredList.length }}</span>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nomor Polisi</th>
                  <th>Jenis Kendaraan</th>
                  <th>Tanggal Ditambah</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="filteredList.length === 0">
                  <td colspan="5" class="empty-row">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <p>Tidak ada data yang cocok</p>
                  </td>
                </tr>
                <tr v-for="k in filteredList" :key="k.id">
                  <td class="text-muted">{{ k.id }}</td>
                  <td>
                    <div class="nopol-cell">
                      <span class="nopol-text">{{ k.nopol }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="badge" :class="getMerkBadgeClass(k.jenis_kendaraan)">{{ k.jenis_kendaraan }}</span>
                  </td>
                  <td class="text-muted">{{ k.tanggal }}</td>
                  <td>
                    <div class="action-btns">
                      <button class="icon-btn edit" @click="openEdit(k)" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="15" height="15" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </button>
                      <button class="icon-btn delete" @click="deleteItem(k.id)" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="15" height="15" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <AddKendaraanModal :is-open="showAdd" @close="showAdd = false" @kendaraan-added="addKendaraan" />
    <EditKendaraanModal :is-open="showEdit" :kendaraan="selectedKendaraan" @close="closeEdit" @kendaraan-updated="updateKendaraan" />
  </div>
</template>

<script>
import Sidebar from '@/components/Sidebar.vue'
import AddKendaraanModal from '@/components/AddKendaraanModal.vue'
import EditKendaraanModal from '@/components/EditKendaraanModal.vue'

export default {
  name: 'KendaraanView',
  components: { Sidebar, AddKendaraanModal, EditKendaraanModal },
  data() {
    return {
      searchQuery: '',
      selectedMerk: '',
      showAdd: false,
      showEdit: false,
      selectedKendaraan: null,
      nextId: 13,
      merkList: [
        { name: 'Hino', bg: '#dbeafe', color: '#1d4ed8' },
        { name: 'Mitsubishi', bg: '#fee2e2', color: '#991b1b' },
        { name: 'Isuzu', bg: '#fef9c3', color: '#854d0e' },
      ],
      kendaraanList: [
        { id: 1, nopol: 'B 1234 ABC', jenis_kendaraan: 'Hino', tanggal: '01/01/2024' },
        { id: 2, nopol: 'D 5678 XYZ', jenis_kendaraan: 'Mitsubishi', tanggal: '05/01/2024' },
        { id: 3, nopol: 'F 9012 GHI', jenis_kendaraan: 'Isuzu', tanggal: '10/01/2024' },
        { id: 4, nopol: 'AB 3456 JKL', jenis_kendaraan: 'Toyota', tanggal: '15/01/2024' },
        { id: 5, nopol: 'B 7890 MNO', jenis_kendaraan: 'Hino', tanggal: '20/01/2024' },
        { id: 6, nopol: 'L 1111 PQR', jenis_kendaraan: 'Mitsubishi', tanggal: '25/01/2024' },
        { id: 7, nopol: 'N 2222 STU', jenis_kendaraan: 'Hino', tanggal: '01/02/2024' },
        { id: 8, nopol: 'R 3333 VWX', jenis_kendaraan: 'Isuzu', tanggal: '05/02/2024' },
        { id: 9, nopol: 'S 4444 YZA', jenis_kendaraan: 'Toyota', tanggal: '10/02/2024' },
        { id: 10, nopol: 'K 5555 BCD', jenis_kendaraan: 'Daihatsu', tanggal: '15/02/2024' },
        { id: 11, nopol: 'H 6666 EFG', jenis_kendaraan: 'Hino', tanggal: '20/02/2024' },
        { id: 12, nopol: 'G 7777 HIJ', jenis_kendaraan: 'Mitsubishi', tanggal: '25/02/2024' }
      ]
    }
  },
  computed: {
    filteredList() {
      let list = this.kendaraanList
      if (this.searchQuery) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(k => k.nopol.toLowerCase().includes(q) || k.jenis_kendaraan.toLowerCase().includes(q))
      }
      if (this.selectedMerk) {
        list = list.filter(k => k.jenis_kendaraan === this.selectedMerk)
      }
      return list
    }
  },
  methods: {
    getMerkCount(m) { return this.kendaraanList.filter(k => k.jenis_kendaraan === m).length },
    addKendaraan(data) {
      this.kendaraanList.push({ id: this.nextId++, nopol: data.nopol, jenis_kendaraan: data.jenis_kendaraan, tanggal: new Date().toLocaleDateString('id-ID') })
      this.showAdd = false
    },
    openEdit(k) { this.selectedKendaraan = { ...k }; this.showEdit = true },
    closeEdit() { this.showEdit = false; this.selectedKendaraan = null },
    updateKendaraan(data) {
      const idx = this.kendaraanList.findIndex(k => k.id === this.selectedKendaraan.id)
      if (idx !== -1) this.kendaraanList.splice(idx, 1, { ...this.kendaraanList[idx], ...data })
      this.closeEdit()
    },
    deleteItem(id) {
      if (!confirm('Hapus kendaraan ini?')) return
      this.kendaraanList = this.kendaraanList.filter(k => k.id !== id)
    },
    getMerkBadgeClass(j) {
      const m = { 'Hino': 'badge-blue', 'Mitsubishi': 'badge-red', 'Isuzu': 'badge-yellow', 'Toyota': 'badge-green', 'Daihatsu': 'badge-purple' }
      return m[j] || 'badge-gray'
    }
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
</style>

<style scoped>
/* ===== LAYOUT ===== */
.page-layout { display: flex; min-height: 100vh; font-family: 'Poppins', sans-serif; }
.main-content { flex: 1; background: #f0f0f8; display: flex; flex-direction: column; min-width: 0; }

/* ===== TOPBAR ===== */
.topbar { background: #fff; border-bottom: 1px solid #e8e8f0; padding: 1.1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; }
.page-title { font-size: 1.2rem; font-weight: 700; color: #1e1d4c; }
.page-sub { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; }
.btn-primary { display: flex; align-items: center; gap: 7px; padding: 0.6rem 1.2rem; background: #3E3D90; border: none; border-radius: 10px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.83rem; font-weight: 600; cursor: pointer; transition: background 0.15s, transform 0.1s; box-shadow: 0 4px 12px rgba(62,61,144,0.3); }
.btn-primary:hover { background: #4c4bb0; transform: translateY(-1px); }

/* ===== CONTENT ===== */
.content-body { flex: 1; padding: 1.75rem 2rem; display: flex; flex-direction: column; gap: 1.25rem; }

/* ===== STATS ===== */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
.stat-card { background: #fff; border-radius: 14px; padding: 1.1rem 1.2rem; display: flex; align-items: center; gap: 0.85rem; border: 1px solid #e8e8f0; transition: transform 0.15s, box-shadow 0.15s; }
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(62,61,144,0.09); }
.stat-icon-wrap { width: 44px; height: 44px; border-radius: 12px; background: #f0f0f8; color: #3E3D90; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-total .stat-icon-wrap { background: #ede9fe; color: #6d28d9; }
.stat-label { font-size: 0.72rem; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; }
.stat-value { font-size: 1.7rem; font-weight: 700; color: #1e1d4c; line-height: 1.1; }

/* ===== FILTERS ===== */
.filters-row { display: flex; gap: 0.75rem; align-items: center; }
.search-wrap { position: relative; flex: 1; max-width: 420px; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
.search-wrap input { width: 100%; padding: 0.65rem 2.2rem 0.65rem 36px; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.83rem; outline: none; background: #fff; color: #374151; transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
.search-wrap input:focus { border-color: #3E3D90; box-shadow: 0 0 0 3px rgba(62,61,144,0.1); }
.search-wrap input::placeholder { color: #c0c0d0; }
.clear-btn { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; padding: 2px; display: flex; }
.clear-btn:hover { color: #374151; }
.select-filter { padding: 0.65rem 1rem; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.83rem; outline: none; background: #fff; color: #374151; cursor: pointer; transition: border-color 0.2s; }
.select-filter:focus { border-color: #3E3D90; }

/* ===== CARD ===== */
.card { background: #fff; border-radius: 14px; border: 1px solid #e8e8f0; overflow: hidden; }
.card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f0f0f8; display: flex; align-items: center; justify-content: space-between; }
.card-header-left { display: flex; align-items: center; gap: 0.6rem; }
.card-title { font-size: 0.95rem; font-weight: 600; color: #1e1d4c; }
.card-badge { padding: 0.2rem 0.65rem; background: #f0f0f8; border-radius: 999px; font-size: 0.72rem; font-weight: 600; color: #6b7280; }

/* ===== TABLE ===== */
.table-wrap { overflow-x: auto; }
.table { width: 100%; border-collapse: collapse; }
.table thead th { padding: 0.7rem 1.25rem; font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; background: #fafafa; text-align: left; border-bottom: 1px solid #f0f0f8; }
.table tbody td { padding: 0.9rem 1.25rem; font-size: 0.85rem; color: #374151; border-bottom: 1px solid #f5f5fb; vertical-align: middle; }
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #faf9ff; }
.text-muted { color: #b0b0c8; font-size: 0.8rem; }

/* ===== NOPOL ===== */
.nopol-cell { display: flex; align-items: center; gap: 8px; }
.nopol-text { font-weight: 700; color: #1e1d4c; letter-spacing: 0.3px; }

/* ===== EMPTY ===== */
.empty-row { text-align: center; padding: 3rem !important; color: #9ca3af; }
.empty-row svg { margin: 0 auto 0.5rem; display: block; }
.empty-row p { font-size: 0.85rem; }

/* ===== ACTIONS ===== */
.action-btns { display: flex; gap: 5px; }
.icon-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none; background: none; border-radius: 8px; cursor: pointer; color: #b0b0c8; transition: all 0.15s; }
.icon-btn.edit:hover { color: #3E3D90; background: #f0f0fb; }
.icon-btn.delete:hover { color: #ef4444; background: #fff0f0; }

/* ===== BADGES ===== */
.badge { display: inline-flex; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
.badge-blue   { background: #dbeafe; color: #1d4ed8; }
.badge-red    { background: #fee2e2; color: #991b1b; }
.badge-yellow { background: #fef9c3; color: #854d0e; }
.badge-green  { background: #dcfce7; color: #15803d; }
.badge-purple { background: #ede9fe; color: #6d28d9; }
.badge-gray   { background: #f3f4f6; color: #374151; }

@media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } .content-body { padding: 1rem; } }
</style>