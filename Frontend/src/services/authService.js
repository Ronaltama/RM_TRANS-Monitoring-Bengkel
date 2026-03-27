import api from './api.js'

export default {
  login: function (credentials) {
    return api.post('/login', credentials)
  },
  logout: function () {
    return api.post('/logout')
  },
  setToken: function (token) {
    localStorage.setItem('auth_token', token)
  },
  getToken: function () {
    return localStorage.getItem('auth_token')
  },
  removeToken: function () {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_data')
  },
  setUser: function (user) {
    localStorage.setItem('user_data', JSON.stringify(user))
  },
  getUser: function () {
    var data = localStorage.getItem('user_data')
    return data ? JSON.parse(data) : null
  },
  isAuthenticated: function () {
    return !!this.getToken()
  }
}
