import axios from '@/core/axios'

export const kategoriKomponenApi = {
  getAll() {
    return axios.get('/kategori_komponen')
  },
  create(data) {
    return axios.post('/kategori_komponen', data)
  }
}
