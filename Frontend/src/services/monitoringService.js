import api from './api.js'

export default {
  getAll: function (params) {
    return api.get('/monitoring-kendaraan', { params: params })
  },
  getById: function (id) {
    return api.get('/monitoring-kendaraan/' + id)
  },
  getAvailableKendaraan: function () {
    return api.get('/monitoring-kendaraan/available-kendaraan')
  },
  addToMonitoring: function (data) {
    return api.post('/monitoring-kendaraan', data)
  },
  removeFromMonitoring: function (id) {
    return api.delete('/monitoring-kendaraan/' + id)
  },
  updateKilometer: function (id, data) {
    return api.put('/monitoring-kendaraan/' + id + '/update-km', data)
  }
}
