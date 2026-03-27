import api from './api.js'

export default {
  getDashboardStats: function () {
    return api.get('/dashboard')
  }
}
