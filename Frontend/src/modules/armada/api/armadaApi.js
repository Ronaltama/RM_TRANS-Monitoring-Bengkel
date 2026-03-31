import axios from '@/core/axios'

export const armadaApi = {
  // GET all armada
  getAll() {
    return axios.get('/armada')
  },

  // GET detail armada by id
  getById(id) {
    return axios.get(`/armada/${id}`)
  },

  // POST create armada
  create(data) {
    return axios.post('/armada', data)
  },

  // PUT update armada
  update(id, data) {
    return axios.put(`/armada/${id}`, data)
  },

  // DELETE armada
  delete(id) {
    return axios.delete(`/armada/${id}`)
  }
}