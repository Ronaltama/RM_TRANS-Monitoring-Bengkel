<template>
  <div v-if="isOpen && component" class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-box">
      <div class="modal-header">
        <h3 class="modal-title">Edit Component</h3>
        <button class="close-btn" @click="$emit('close')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label>Component Category <span class="req">*</span></label>
          <select v-model="form.component_category_id">
            <option value="">-- Select Category --</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nama_kategori }}</option>
          </select>
        </div>
        <div class="form-group">
          <label>Component Name <span class="req">*</span></label>
          <input v-model="form.nama_component" type="text" placeholder="e.g., Front Right Tire" />
        </div>
        <div class="form-group">
          <label>Tracking Type</label>
          <div class="radio-group">
            <label class="radio-label"><input type="radio" v-model="form.tracking_type" value="km" /> KM-based</label>
            <label class="radio-label"><input type="radio" v-model="form.tracking_type" value="date" /> Date-based</label>
          </div>
        </div>
        <div v-if="form.tracking_type === 'km'" class="form-group">
          <label>Target KM <span class="req">*</span></label>
          <input v-model.number="form.target_km" type="number" min="1" />
        </div>
        <div v-if="form.tracking_type === 'date'" class="form-group">
          <label>Durasi (Hari) <span class="req">*</span></label>
          <input v-model.number="form.target_days" type="number" min="1" />
          <div v-if="form.target_days > 0" class="date-preview">
            📅 <strong>Tanggal habis:</strong> {{ calcTargetDate(form.target_days) }}
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn-cancel" @click="$emit('close')" :disabled="loading">Cancel</button>
        <button class="btn-submit" @click="submit" :disabled="loading">
          {{ loading ? 'Updating...' : 'Update Component' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import componentService from '@/services/componentService.js'

export default {
  name: 'EditComponentModal',
  props: {
    isOpen: { type: Boolean, default: false },
    component: { type: Object, default: null }
  },
  data() {
    return {
      loading: false,
      categories: [],
      form: { component_category_id: '', nama_component: '', tracking_type: 'km', target_km: '', target_days: '' }
    }
  },
  watch: {
    isOpen(v) {
      if (v && this.component) {
        this.loadCategories()
        this.form = {
          component_category_id: this.component.category?.id || '',
          nama_component: this.component.nama_component || '',
          tracking_type: this.component.tracking_type || 'km',
          target_km: this.component.target_km || '',
          target_days: this.component.target_days || ''
        }
      }
    }
  },
  methods: {
    loadCategories() {
      componentService.getCategories()
        .then(res => { if (res.data.success) this.categories = res.data.data })
        .catch(err => console.error('Error loading categories:', err))
    },
    submit() {
      this.loading = true
      const payload = {
        component_category_id: this.form.component_category_id,
        nama_component: this.form.nama_component,
        tracking_type: this.form.tracking_type,
        target_km: this.form.tracking_type === 'km' ? parseInt(this.form.target_km) : null,
        target_days: this.form.tracking_type === 'date' ? parseInt(this.form.target_days) : null
      }
      componentService.updateComponent(this.component.id, payload)
        .then(res => {
          if (res.data.success) {
            this.$emit('component-updated')
            this.$emit('close')
          }
        })
        .catch(err => {
          console.error('Error updating component:', err)
          alert(err.response?.data?.message || 'Failed to update component')
        })
        .finally(() => { this.loading = false })
    },
    calcTargetDate(days) {
      if (!days) return '-'
      const d = new Date(); d.setDate(d.getDate() + parseInt(days))
      return d.toLocaleDateString('id-ID')
    }
  }
}
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 1rem; }
.modal-box { background: #fff; border-radius: 14px; width: 100%; max-width: 460px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; position: sticky; top: 0; background: #fff; }
.modal-title { font-size: 1rem; font-weight: 600; color: #1f2937; }
.close-btn { background: none; border: none; color: #9ca3af; cursor: pointer; }
.modal-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
.form-group { display: flex; flex-direction: column; gap: 4px; }
label { font-size: 0.85rem; font-weight: 500; color: #374151; }
.req { color: #ef4444; }
input[type="text"], input[type="number"], select { padding: 0.6rem 0.9rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; outline: none; }
input:focus, select:focus { border-color: #4f46e5; }
.radio-group { display: flex; gap: 1.5rem; }
.radio-label { display: flex; align-items: center; gap: 6px; font-size: 0.875rem; color: #374151; cursor: pointer; font-weight: 400; }
.date-preview { padding: 0.5rem 0.75rem; background: #eff6ff; border-radius: 6px; font-size: 0.8rem; color: #1d4ed8; }
.modal-footer { display: flex; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; position: sticky; bottom: 0; background: #fff; }
.btn-cancel { padding: 0.6rem 1.25rem; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.875rem; cursor: pointer; }
.btn-submit { padding: 0.6rem 1.25rem; background: #4f46e5; border: none; border-radius: 8px; color: #fff; font-size: 0.875rem; font-weight: 500; cursor: pointer; }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
