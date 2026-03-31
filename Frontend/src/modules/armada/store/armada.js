import { armadaApi } from '../api/armadaApi'
import { jenisArmadaApi } from '../api/jenisArmadaApi'
import Swal from 'sweetalert2'

// State
const state = {
  armadaList: [],
  jenisArmadaList: [],
  loading: false,
  currentArmada: null
}

// Getters
const getters = {
  armadaList: (state) => state.armadaList,
  jenisArmadaList: (state) => state.jenisArmadaList,
  loading: (state) => state.loading,
  currentArmada: (state) => state.currentArmada
}

// Mutations
const mutations = {
  SET_ARMADA_LIST(state, data) {
    state.armadaList = data
  },
  SET_JENIS_ARMADA_LIST(state, data) {
    state.jenisArmadaList = data
  },
  ADD_ARMADA(state, data) {
    state.armadaList.unshift(data)
  },
  UPDATE_ARMADA(state, updatedData) {
    const index = state.armadaList.findIndex(item => item.id === updatedData.id)
    if (index !== -1) {
      state.armadaList.splice(index, 1, updatedData)
    }
  },
  REMOVE_ARMADA(state, id) {
    state.armadaList = state.armadaList.filter(item => item.id !== id)
  },
  SET_LOADING(state, status) {
    state.loading = status
  },
  SET_CURRENT_ARMADA(state, data) {
    state.currentArmada = data
  }
}

// Actions
const actions = {
  // Fetch all jenis armada
  async fetchJenisArmada({ commit }) {
    try {
      const response = await jenisArmadaApi.getAll()
      commit('SET_JENIS_ARMADA_LIST', response.data)
      return { success: true, data: response.data }
    } catch (error) {
      console.error('Error fetching jenis armada:', error)
      return { success: false, error: error.message }
    }
  },

  // Fetch all armada
  async fetchArmada({ commit }) {
    commit('SET_LOADING', true)
    try {
      const response = await armadaApi.getAll()
      // Response dari Laravel: langsung data array armada
      commit('SET_ARMADA_LIST', response.data)
      return { success: true, data: response.data }
    } catch (error) {
      console.error('Error fetching armada:', error)
      Swal.fire({
        icon: 'error',
        title: 'Gagal Memuat Data',
        text: error.response?.data?.message || 'Terjadi kesalahan saat memuat data armada',
        confirmButtonColor: '#3E3D90'
      })
      return { success: false, error: error.response?.data?.message || error.message }
    } finally {
      commit('SET_LOADING', false)
    }
  },

  // Create new armada
  async createArmada({ commit }, payload) {
    commit('SET_LOADING', true)
    try {
      const response = await armadaApi.create(payload)
      
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Armada berhasil ditambahkan',
        confirmButtonColor: '#3E3D90',
        timer: 2000,
        showConfirmButton: false
      })
      
      commit('ADD_ARMADA', response.data.data)
      return { success: true, data: response.data.data }
    } catch (error) {
      console.error('Error creating armada:', error)
      
      let errorMessage = 'Gagal menambahkan armada'
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
  },

  // Update armada
  async updateArmada({ commit }, { id, data }) {
    commit('SET_LOADING', true)
    try {
      const response = await armadaApi.update(id, data)
      
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Armada berhasil diperbarui',
        confirmButtonColor: '#3E3D90',
        timer: 2000,
        showConfirmButton: false
      })
      
      commit('UPDATE_ARMADA', response.data.data)
      return { success: true, data: response.data.data }
    } catch (error) {
      console.error('Error updating armada:', error)
      
      let errorMessage = 'Gagal memperbarui armada'
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
  },

  // Delete armada
  async deleteArmada({ commit }, id) {
    const result = await Swal.fire({
      title: 'Konfirmasi Hapus',
      text: 'Apakah Anda yakin ingin menghapus armada ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    })
    
    if (!result.isConfirmed) return { success: false }
    
    commit('SET_LOADING', true)
    try {
      await armadaApi.delete(id)
      
      Swal.fire({
        icon: 'success',
        title: 'Terhapus!',
        text: 'Armada berhasil dihapus',
        confirmButtonColor: '#3E3D90',
        timer: 2000,
        showConfirmButton: false
      })
      
      commit('REMOVE_ARMADA', id)
      return { success: true }
    } catch (error) {
      console.error('Error deleting armada:', error)
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: error.response?.data?.message || 'Gagal menghapus armada',
        confirmButtonColor: '#3E3D90'
      })
      return { success: false, error: error.response?.data?.message || error.message }
    } finally {
      commit('SET_LOADING', false)
    }
  },

  // Get detail armada
  async fetchArmadaDetail({ commit }, id) {
    commit('SET_LOADING', true)
    try {
      const response = await armadaApi.getById(id)
      commit('SET_CURRENT_ARMADA', response.data)
      return { success: true, data: response.data }
    } catch (error) {
      console.error('Error fetching armada detail:', error)
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Data armada tidak ditemukan',
        confirmButtonColor: '#3E3D90'
      })
      return { success: false, error: error.message }
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