import axios from '../core/axios.js'

const componentService = {
  // Methods for monitoring components
  getComponents(monitoringId) {
    return axios.get(`/monitoring_armada_aktif/${monitoringId}/komponen`)
  },
  
  addComponent(monitoringId, payload) {
    return axios.post(`/monitoring_armada_aktif/${monitoringId}/komponen`, payload)
  },
  
  resetComponent(id, payload) {
    return axios.post(`/komponen_armada/${id}/reset`, payload)
  },
  
  deleteComponent(id) {
    return axios.delete(`/komponen_armada/${id}`)
  },

  // Categories (already partially handled in kategoriKomponen store, but useful here too)
  getCategories() {
    return axios.get('/kategori_komponen')
  },
  
  createCategory(payload) {
    return axios.post('/kategori_komponen', payload)
  }
}

export default componentService
