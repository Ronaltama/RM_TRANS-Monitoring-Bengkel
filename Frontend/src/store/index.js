// store/index.js merupakan file utama untuk menggabungkan semua module store yang ada di dalam folder modules

import Vue from 'vue'
import Vuex from 'vuex'

import auth from '../modules/auth/store/auth'
import armada from '../modules/armada/store/armada'


Vue.use(Vuex)

export default new Vuex.Store({
  // ini pendaftaran module store, nanti bisa ditambahkan module lain seperti kendaraan, monitoring, dll
  modules: {
    auth, // module auth
    armada // module armada
  }
})