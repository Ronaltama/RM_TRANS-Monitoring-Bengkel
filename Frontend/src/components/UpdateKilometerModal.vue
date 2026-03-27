<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-box">
      <div class="modal-header">
        <h3 class="modal-title">Update Kilometer</h3>
        <button class="close-btn" @click="$emit('close')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="modal-body">
        <!-- Current KM -->
        <div class="info-box">
          <p class="info-label">Current Kilometer</p>
          <p class="info-value">{{ formatNumber(currentKm) }} <span class="info-unit">km</span></p>
        </div>

        <!-- Base KM -->
        <div class="form-group">
          <label>Kilometer Awal <span class="note red">(tidak update progress)</span></label>
          <input v-model.number="form.baseKm" type="number" min="0" placeholder="Contoh: 50000" @input="validate" />
          <p class="hint">Set kilometer awal kendaraan saat ini</p>
        </div>

        <!-- Additional KM -->
        <div class="form-group">
          <label>Tambah Kilometer <span class="note green">(✓ update progress komponen)</span></label>
          <input v-model.number="form.additionalKm" type="number" min="0" placeholder="Contoh: 500" @input="validate" class="input-green" />
          <p class="hint">Jarak yang ditempuh — ini yang update progress komponen</p>
        </div>

        <!-- Preview total -->
        <div class="preview-box">
          <p class="preview-label">New Total Kilometer</p>
          <p class="preview-value">{{ formatNumber(newTotal) }} <span class="preview-unit">km</span></p>
          <p class="preview-detail">Base: {{ formatNumber(form.baseKm || 0) }} km</p>
          <p v-if="form.additionalKm > 0" class="preview-detail green">+ {{ formatNumber(form.additionalKm) }} km (increment)</p>
        </div>

        <p v-if="errorMsg" class="err-msg">{{ errorMsg }}</p>
      </div>

      <div class="modal-footer">
        <button class="btn-cancel" @click="$emit('close')" :disabled="loading">Cancel</button>
        <button class="btn-submit" @click="update" :disabled="loading">
          {{ loading ? 'Updating...' : 'Update' }}
        </button>
      </div>
    </div>
  </div>
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
        .then(res => {
          if (res.data.success) {
            this.$emit('updated', res.data.data)
            this.$emit('close')
          }
        })
        .catch(err => {
          this.errorMsg = err.response?.data?.message || 'Gagal mengupdate kilometer'
        })
        .finally(() => { this.loading = false })
    },
    formatNumber(n) { return (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') }
  }
}
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
.modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 480px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
.modal-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.close-btn { background: none; border: none; color: #9ca3af; cursor: pointer; }
.modal-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.info-box { background: #f9fafb; border-radius: 8px; padding: 0.875rem 1rem; }
.info-label { font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.info-value { font-size: 1.5rem; font-weight: 700; color: #1f2937; }
.info-unit { font-size: 0.875rem; color: #9ca3af; font-weight: 400; }
.form-group { display: flex; flex-direction: column; gap: 4px; }
label { font-size: 0.85rem; font-weight: 500; color: #374151; }
.note { font-size: 0.75rem; font-weight: 400; }
.note.red { color: #ef4444; }
.note.green { color: #16a34a; }
input { padding: 0.65rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; outline: none; }
input:focus { border-color: #4f46e5; }
input.input-green:focus { border-color: #22c55e; }
.hint { font-size: 0.75rem; color: #9ca3af; }
.preview-box { background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 8px; padding: 0.875rem 1rem; }
.preview-label { font-size: 0.7rem; font-weight: 600; color: #4f46e5; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.preview-value { font-size: 1.5rem; font-weight: 700; color: #3730a3; }
.preview-unit { font-size: 0.875rem; color: #6366f1; font-weight: 400; }
.preview-detail { font-size: 0.75rem; color: #6366f1; margin-top: 2px; }
.preview-detail.green { color: #16a34a; font-weight: 500; }
.err-msg { font-size: 0.8rem; color: #ef4444; padding: 0.5rem 0.75rem; background: #fef2f2; border-radius: 6px; border: 1px solid #fecaca; }
.modal-footer { display: flex; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; background: #f9fafb; border-radius: 0 0 14px 14px; }
.btn-cancel { padding: 0.6rem 1.25rem; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
.btn-submit { padding: 0.6rem 1.25rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
