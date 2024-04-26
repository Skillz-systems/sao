// axios
import axios from 'axios'
// import api from '@/api-config'

const axiosIns = axios.create({
  baseURL: process.env.VUE_APP_BASE_URL,
})

export default axiosIns
