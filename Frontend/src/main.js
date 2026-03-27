import Vue from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'

// Set base URL for API calls
axios.defaults.baseURL = '/api'
axios.defaults.withCredentials = true

Vue.prototype.$http = axios

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
