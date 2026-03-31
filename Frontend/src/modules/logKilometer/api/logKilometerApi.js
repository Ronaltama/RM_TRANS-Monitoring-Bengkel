import axios from '@/core/axios'

export const logKilometerApi = {
  // GET all logs
  getAll() {
    return axios.get('/log_kilometer')
  },

  // POST create new log
  create(data) {
    return axios.post('/log_kilometer', data)
  }
}
