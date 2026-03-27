<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-box">
      <div class="modal-header">
        <h3 class="modal-title">Edit Kendaraan</h3>
        <button class="close-btn" @click="$emit('close')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form @submit.prevent="$emit('kendaraan-updated', form)" class="modal-body">
        <div class="form-group">
          <label>Nomor Polisi <span class="req">*</span></label>
          <input v-model="form.nopol" type="text" placeholder="B 1234 ABC" required />
        </div>
        <div class="form-group">
          <label>Merk Kendaraan <span class="req">*</span></label>
          <select v-model="form.jenis_kendaraan" required>
            <option value="" disabled>-- Pilih Merk --</option>
            <option value="Hino">Hino</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Toyota">Toyota</option>
            <option value="Daihatsu">Daihatsu</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Nissan">Nissan</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" @click="$emit('close')">Batal</button>
          <button type="submit" class="btn-submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EditKendaraanModal',
  props: {
    isOpen: { type: Boolean, required: true },
    kendaraan: { type: Object, default: null }
  },
  data() {
    return { form: { nopol: '', jenis_kendaraan: '' } }
  },
  watch: {
    kendaraan: {
      handler(v) { if (v) { this.form.nopol = v.nopol || ''; this.form.jenis_kendaraan = v.jenis_kendaraan || '' } },
      immediate: true, deep: true
    }
  }
}
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
.modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 440px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.modal-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.close-btn { background: none; border: none; color: #9ca3af; cursor: pointer; padding: 4px; }
.modal-body { padding: 1.25rem 1.5rem; }
.form-group { margin-bottom: 1rem; }
label { display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 5px; }
.req { color: #ef4444; }
input, select { width: 100%; padding: 0.6rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; outline: none; box-sizing: border-box; }
input:focus, select:focus { border-color: #4f46e5; }
.modal-footer { display: flex; gap: 0.75rem; padding-top: 0.5rem; }
.btn-cancel { flex: 1; padding: 0.65rem; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
.btn-submit { flex: 1; padding: 0.65rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
</style>
