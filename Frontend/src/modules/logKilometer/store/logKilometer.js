import { logKilometerApi } from '../api/logKilometerApi'
import Swal from 'sweetalert2'

// State
const state = {
  logList: [],
  loading: false
}

// Getters
const getters = {
  logList: (state) => state.logList,
  loading: (state) => state.loading
}

// Mutations
const mutations = {
  SET_LOG_LIST(state, data) {
    state.logList = data
  },
  ADD_LOG(state, data) {
    state.logList.unshift(data)
  },
  SET_LOADING(state, status) {
    state.loading = status
  }
}

// Actions
const actions = {
  // Fetch all log
  async fetchLogs({ commit }) {
    commit('SET_LOADING', true)
    try {
      const response = await logKilometerApi.getAll()
      commit('SET_LOG_LIST', response.data.data || response.data)
      return { success: true, data: response.data }
    } catch (error) {
      console.error('Error fetching logs:', error)
      return { success: false, error: error.message }
    } finally {
      commit('SET_LOADING', false)
    }
  },

  // Create new log (Update Kilometer)
  async createLog({ commit }, payload) {
    commit('SET_LOADING', true)
    try {
      const response = await logKilometerApi.create(payload)
      
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Kilometer berhasil diperbarui (History log tersimpan)',
        confirmButtonColor: '#3E3D90',
        timer: 2000,
        showConfirmButton: false
      })
      
      commit('ADD_LOG', response.data.data)
      return { success: true, data: response.data.data, updated_monitoring: response.data.updated_monitoring }
    } catch (error) {
      console.error('Error creating log kilometer:', error)
      
      let errorMessage = 'Gagal mengupdate kilometer'
      if (error.response?.data?.errors) {
        const errors = error.response.data.errors
        errorMessage = Object.values(errors).flat().join('\n')
      } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: errorMessage,
        confirmButtonColor: '#3E3D90'
      })
      return { success: false, error: errorMessage }
    } finally {
      commit('SET_LOADING', false)
    }
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
