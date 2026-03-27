import Vue from 'vue'
import App from './App.vue'
import router from './router'
import api from './services/api.js'
import authService from './services/authService.js'

// Set Authorization header on startup if token exists
var token = authService.getToken()
if (token) {
  api.defaults.headers.common['Authorization'] = 'Bearer ' + token
}

Vue.prototype.$http = api

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
