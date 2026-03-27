<template>
  <div class="page-layout">
    <Sidebar />
    <main class="main-content">
      <header class="topbar">
        <div>
          <h2 class="page-title">Daftar Kendaraan</h2>
          <p class="page-sub">Kelola data kendaraan workshop</p>
        </div>
        <button class="btn-primary" @click="showAdd = true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Tambah Kendaraan
        </button>
      </header>

      <div class="content-body">
        <!-- Stats singatl -->
        <div class="stats-grid">
          <div class="stat-card">
            <p class="stat-label">Total</p>
            <p class="stat-value">{{ kendaraanList.length }}</p>
          </div>
          <div class="stat-card" v-for="m in ['Hino','Mitsubishi','Isuzu']" :key="m">
            <p class="stat-label">{{ m }}</p>
            <p class="stat-value">{{ getMerkCount(m) }}</p>
          </div>
        </div>

        <!-- Filters -->
        <div class="filters-row">
          <div class="search-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input v-model="searchQuery" type="text" placeholder="Cari nomor polisi atau jenis kendaraan..." />
          </div>
          <select v-model="selectedMerk" class="select-filter">
            <option value="">Semua Jenis</option>
            <option v-for="m in ['Hino','Mitsubishi','Isuzu','Toyota','Daihatsu']" :key="m" :value="m">{{ m }}</option>
          </select>
        </div>

        <!-- Table -->
        <div class="card">
          <div class="card-header">
            <span class="card-title">Daftar Kendaraan ({{ filteredList.length }})</span>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th><th>NOMOR POLISI</th><th>JENIS KENDARAAN</th><th>TANGGAL DITAMBAH</th><th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="k in filteredList" :key="k.id">
                  <td class="text-gray">{{ k.id }}</td>
                  <td><span class="fw-bold">{{ k.nopol }}</span></td>
                  <td><span class="badge" :class="getMerkBadgeClass(k.jenis_kendaraan)">{{ k.jenis_kendaraan }}</span></td>
                  <td class="text-gray">{{ k.tanggal }}</td>
                  <td>
                    <div class="action-btns">
                      <button class="icon-btn edit" @click="openEdit(k)" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </button>
                      <button class="icon-btn delete" @click="deleteItem(k.id)" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
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

    <!-- Add Modal -->
    <AddKendaraanModal :is-open="showAdd" @close="showAdd = false" @kendaraan-added="addKendaraan" />
    <!-- Edit Modal -->
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

<style scoped>
.page-layout { display: flex; min-height: 100vh; }
.main-content { flex: 1; background: #f5f6fa; display: flex; flex-direction: column; min-width: 0; }
.topbar { background: #fff; border-bottom: 1px solid #e8ecf0; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; }
.page-title { font-size: 1.3rem; font-weight: 700; color: #1a1a2e; }
.page-sub { font-size: 0.8rem; color: #9ca3af; margin-top: 2px; }
.btn-primary { display: flex; align-items: center; gap: 6px; padding: 0.6rem 1.25rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
.content-body { flex: 1; padding: 1.5rem 2rem; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.25rem; }
.stat-card { background: #fff; border-radius: 10px; padding: 1rem 1.25rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
.stat-label { font-size: 0.78rem; color: #9ca3af; margin-bottom: 4px; }
.stat-value { font-size: 1.75rem; font-weight: 700; color: #1f2937; }
.filters-row { display: flex; gap: 0.75rem; margin-bottom: 1.25rem; align-items: center; }
.search-wrap { position: relative; flex: 1; max-width: 400px; }
.search-wrap svg { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
.search-wrap input { width: 100%; padding: 0.6rem 1rem 0.6rem 34px; border: 1px solid #e8ecf0; border-radius: 8px; font-size: 0.875rem; outline: none; box-sizing: border-box; }
.search-wrap input:focus { border-color: #4f46e5; }
.select-filter { padding: 0.6rem 1rem; border: 1px solid #e8ecf0; border-radius: 8px; font-size: 0.875rem; outline: none; background: #fff; }
.card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
.card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.card-title { font-size: 0.95rem; font-weight: 600; color: #1f2937; }
.table-wrap { overflow-x: auto; }
.table { width: 100%; border-collapse: collapse; }
.table thead th { padding: 0.7rem 1.25rem; font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; background: #f9fafb; text-align: left; }
.table tbody td { padding: 0.9rem 1.25rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f1f5f9; }
.table tbody tr:last-child td { border-bottom: none; }
.table tbody tr:hover td { background: #f9fafb; }
.fw-bold { font-weight: 700; }
.text-gray { color: #9ca3af; }
.action-btns { display: flex; gap: 4px; }
.icon-btn { padding: 6px; border: none; background: none; border-radius: 6px; cursor: pointer; color: #9ca3af; }
.icon-btn.edit:hover { color: #4f46e5; background: #f0f4ff; }
.icon-btn.delete:hover { color: #ef4444; background: #fef2f2; }
.badge { display: inline-flex; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
.badge-blue { background: #dbeafe; color: #1d4ed8; }
.badge-red { background: #fee2e2; color: #991b1b; }
.badge-yellow { background: #fef9c3; color: #854d0e; }
.badge-green { background: #dcfce7; color: #15803d; }
.badge-purple { background: #ede9fe; color: #6d28d9; }
.badge-gray { background: #f3f4f6; color: #374151; }
</style>
