import api from './api.js'

export default {
  getAll: function (params) {
    return api.get('/kendaraan', { params: params })
  },
  getById: function (id) {
    return api.get('/kendaraan/' + id)
  },
  create: function (data) {
    return api.post('/kendaraan', data)
  },
  update: function (id, data) {
    return api.put('/kendaraan/' + id, data)
  },
  delete: function (id) {
    return api.delete('/kendaraan/' + id)
  }
}
