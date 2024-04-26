import axios from '@/libs/axios'
import notify from './notify'

export default {
  mixins: [notify],
  methods: {
    reinitializeSession() {
      if (localStorage.getItem('token')) {
        const token = localStorage.getItem('token')
        const userData = JSON.parse(localStorage.getItem('user'))
        this.$store.commit('auth/INIT_USER_DATA', userData)
        // eslint-disable-next-line dot-notation
        axios.defaults.headers['Authorization'] = `Bearer ${token}`
      }
    },
  },
}
