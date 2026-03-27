<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-box">
      <div class="modal-header">
        <div>
          <h3 class="modal-title">Tambah Kendaraan Baru</h3>
          <p class="modal-sub">Masukkan data kendaraan.</p>
        </div>
        <button class="close-btn" @click="$emit('close')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="modal-body">
        <div class="form-group">
          <label>Merk Kendaraan <span class="req">*</span></label>
          <select v-model="form.jenis_kendaraan" required>
            <option value="">-- Pilih Merk --</option>
            <option value="Hino">Hino</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Toyota">Toyota</option>
            <option value="Daihatsu">Daihatsu</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Nissan">Nissan</option>
          </select>
        </div>
        <div class="form-group">
          <label>Nomor Polisi <span class="req">*</span></label>
          <input v-model="form.nopol" type="text" placeholder="Contoh: B 1234 ABC" required />
          <p class="hint">Format: Kode Area, 4 angka, 3 huruf</p>
        </div>

        <div v-if="form.jenis_kendaraan || form.nopol" class="preview-box">
          <p><strong>Jenis:</strong> {{ form.jenis_kendaraan || '-' }}</p>
          <p><strong>Nopol:</strong> {{ form.nopol || '-' }}</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel" @click="$emit('close')">Batal</button>
          <button type="submit" class="btn-submit" :disabled="!canSubmit">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AddKendaraanModal',
  props: { isOpen: { type: Boolean, default: false } },
  computed: {
    canSubmit() {
      return this.form.jenis_kendaraan && this.form.nopol.trim() && /^[A-Z]{1,2}\s\d{1,4}\s[A-Z]{1,3}$/i.test(this.form.nopol.trim())
    }
  },
  data() {
    return { form: { jenis_kendaraan: '', nopol: '' } }
  },
  watch: {
    isOpen(v) { if (v) this.form = { jenis_kendaraan: '', nopol: '' } }
  },
  methods: {
    handleSubmit() {
      if (!this.canSubmit) return
      this.$emit('kendaraan-added', { jenis_kendaraan: this.form.jenis_kendaraan, nopol: this.form.nopol.trim().toUpperCase() })
    }
  }
}
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
.modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 440px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: flex-start; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.modal-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.modal-sub { font-size: 0.8rem; color: #9ca3af; margin-top: 2px; }
.close-btn { background: none; border: none; color: #9ca3af; cursor: pointer; padding: 4px; }
.close-btn:hover { color: #374151; }
.modal-body { padding: 1.25rem 1.5rem; }
.form-group { margin-bottom: 1rem; }
label { display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 5px; }
.req { color: #ef4444; }
input, select { width: 100%; padding: 0.6rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; outline: none; box-sizing: border-box; }
input:focus, select:focus { border-color: #4f46e5; }
.hint { font-size: 0.75rem; color: #9ca3af; margin-top: 4px; }
.preview-box { background: #f9fafb; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.85rem; color: #374151; margin-bottom: 1rem; }
.preview-box p { margin-bottom: 4px; }
.modal-footer { display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 0.5rem; }
.btn-cancel { padding: 0.6rem 1.25rem; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
.btn-submit { padding: 0.6rem 1.25rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
.btn-submit:disabled { background: #c7d2fe; cursor: not-allowed; }
</style>
