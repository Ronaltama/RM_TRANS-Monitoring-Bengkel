import api from './api.js'

export default {
  getVehicleDetail: function (monitoringId) {
    return api.get('/monitoring-kendaraan/' + monitoringId)
  },
  getComponentsByMonitoring: function (monitoringId) {
    return api.get('/monitoring-kendaraan/' + monitoringId + '/components')
  },
  getCategories: function () {
    return api.get('/component-categories')
  },
  addComponent: function (data) {
    return api.post('/components', data)
  },
  updateComponent: function (id, data) {
    return api.put('/components/' + id, data)
  },
  deleteComponent: function (id) {
    return api.delete('/components/' + id)
  },
  markComplete: function (id, data) {
    return api.post('/components/' + id + '/complete', data)
  }
}

