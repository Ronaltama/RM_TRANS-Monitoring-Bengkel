<template>
  <transition name="modal-fade">
    <div v-if="isOpen" class="modal-overlay" @click.self="$emit('close')">
      <div class="modal-box">
        <div class="modal-header">
          <div class="modal-header-left">
            <div class="modal-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </div>
            <div>
              <h3 class="modal-title">Update Kilometer</h3>
              <p class="modal-sub">Perbarui data jarak kendaraan</p>
            </div>
          </div>
          <button class="close-btn" @click="$emit('close')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <div class="info-box">
            <p class="info-label">Kilometer Saat Ini</p>
            <p class="info-value">{{ formatNumber(currentKm) }} <span class="info-unit">km</span></p>
          </div>

          <div class="form-group">
            <label>Kilometer Awal <span class="note note-red">tidak update progress komponen</span></label>
            <input v-model.number="form.baseKm" type="number" min="0" placeholder="Contoh: 50000" @input="validate" />
            <p class="hint">Set ulang kilometer dasar kendaraan</p>
          </div>

          <div class="form-group">
            <label>Tambah Kilometer <span class="note note-green">✓ update progress komponen</span></label>
            <input v-model.number="form.additionalKm" type="number" min="0" placeholder="Contoh: 500" @input="validate" class="input-green" />
            <p class="hint">Jarak yang baru ditempuh</p>
          </div>

          <div class="preview-box">
            <p class="preview-label">Total Kilometer Baru</p>
            <p class="preview-value">{{ formatNumber(newTotal) }} <span class="preview-unit">km</span></p>
            <div class="preview-details">
              <span>Base: {{ formatNumber(form.baseKm || 0) }} km</span>
              <span v-if="form.additionalKm > 0" class="detail-green">+ {{ formatNumber(form.additionalKm) }} km</span>
            </div>
          </div>

          <div v-if="errorMsg" class="error-msg">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ errorMsg }}
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn-cancel" @click="$emit('close')" :disabled="loading">Batal</button>
          <button class="btn-submit" @click="update" :disabled="loading">
            <svg v-if="!loading" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            {{ loading ? 'Updating...' : 'Update' }}
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import monitoringService from '@/services/monitoringService.js'

export default {
  name: 'UpdateKilometerModal',
  props: {
    isOpen: { type: Boolean, required: true },
    currentKm: { type: Number, required: true },
    monitoringId: { required: true }
  },
  data() {
    return {
      loading: false,
      form: { baseKm: 0, additionalKm: 0 },
      errorMsg: ''
    }
  },
  computed: {
    newTotal() { return (this.form.baseKm || 0) + (this.form.additionalKm || 0) }
  },
  watch: {
    isOpen(v) {
      if (v) { this.form.baseKm = this.currentKm; this.form.additionalKm = 0; this.errorMsg = '' }
    }
  },
  methods: {
    validate() {
      if (this.form.baseKm < 0) this.form.baseKm = 0
      if (this.form.additionalKm < 0) this.form.additionalKm = 0
      this.errorMsg = ''
    },
    update() {
      this.loading = true
      const payload = { base_km: this.form.baseKm, additional_km: this.form.additionalKm, new_km: this.newTotal }
      monitoringService.updateKilometer(this.monitoringId, payload)
        .then(res => { if (res.data.success) { this.$emit('updated', res.data.data); this.$emit('close') } })
        .catch(err => { this.errorMsg = err.response?.data?.message || 'Gagal mengupdate kilometer' })
        .finally(() => { this.loading = false })
    },
    formatNumber(n) { return (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.modal-overlay { position: fixed; inset: 0; background: rgba(15,15,40,0.55); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 1rem; backdrop-filter: blur(2px); }
.modal-box { background: #fff; border-radius: 18px; width: 100%; max-width: 460px; box-shadow: 0 24px 64px rgba(0,0,0,0.18); font-family: 'Poppins', sans-serif; overflow: hidden; }

.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 1.4rem; border-bottom: 1px solid #f0f0f8; }
.modal-header-left { display: flex; align-items: center; gap: 0.7rem; }
.modal-icon { width: 38px; height: 38px; background: #f0f0fb; color: #3E3D90; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
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
label { font-size: 0.8rem; font-weight: 600; color: #3E3D90; text-transform: uppercase; letter-spacing: 0.3px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
.note { font-size: 0.7rem; font-weight: 400; padding: 2px 7px; border-radius: 999px; text-transform: none; letter-spacing: 0; }
.note-red { background: #fee2e2; color: #991b1b; }
.note-green { background: #dcfce7; color: #15803d; }

input { padding: 0.65rem 0.9rem; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.875rem; outline: none; background: #fff; color: #374151; width: 100%; box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s; }
input:focus { border-color: #3E3D90; box-shadow: 0 0 0 3px rgba(62,61,144,0.1); }
input.input-green:focus { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.1); }
input::placeholder { color: #c0c0d0; }
.hint { font-size: 0.72rem; color: #b0b0c8; }

.preview-box { background: #f0f0fb; border: 1.5px solid #ddddfb; border-radius: 10px; padding: 0.9rem 1rem; }
.preview-label { font-size: 0.68rem; font-weight: 600; color: #3E3D90; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 5px; }
.preview-value { font-size: 1.4rem; font-weight: 700; color: #1e1d4c; margin-bottom: 4px; }
.preview-unit { font-size: 0.85rem; color: #6b7280; font-weight: 400; }
.preview-details { display: flex; gap: 0.75rem; font-size: 0.75rem; color: #6b7280; }
.detail-green { color: #16a34a; font-weight: 600; }

.error-msg { display: flex; align-items: center; gap: 7px; background: #fff0f0; border: 1px solid #ffd0d0; border-radius: 8px; padding: 8px 12px; font-size: 0.8rem; color: #dc2626; font-weight: 500; }

.modal-footer { display: flex; justify-content: flex-end; gap: 0.65rem; padding: 1rem 1.4rem; border-top: 1px solid #f0f0f8; }
.btn-cancel { padding: 0.6rem 1.1rem; background: #fff; border: 1.5px solid #e8e8f0; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 500; color: #6b7280; cursor: pointer; transition: all 0.15s; }
.btn-cancel:hover { background: #f5f5fb; }
.btn-submit { display: flex; align-items: center; gap: 6px; padding: 0.6rem 1.1rem; background: #3E3D90; border: none; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; color: #fff; cursor: pointer; box-shadow: 0 4px 12px rgba(62,61,144,0.3); transition: background 0.15s, transform 0.1s; }
.btn-submit:hover:not(:disabled) { background: #4c4bb0; transform: translateY(-1px); }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.2s ease; }
.modal-fade-enter, .modal-fade-leave-to { opacity: 0; }
</style>