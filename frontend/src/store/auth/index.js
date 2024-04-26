/* eslint-disable no-unused-vars */
/* eslint-disable dot-notation */
import axios from '@/libs/axios'
import helpers from './auth-helpers'

export default {
  namespaced: true,
  state: {
    userData: {
      id: null,
      firstname: '',
      email: '',
      email_verified_at: null,
      created_at: null,
      updated_at: null,
      lastname: '',
      reverse: '',
      password_reset_code: '',
      email_reset_code: '',
      role: null,
      phone: '',
      usertype: '',
      profilePicture: '',
    },
  },
  getters: {
    getUserID: state => state.userData.id,
    getFirstname: state => state.userData.firstname,
    getEmail: state => state.userData.email,
    getEmailVerifiedAt: state => state.userData.email_verified_at,
    getCreatedAt: state => state.userData.created_at,
    getUpdatedAt: state => state.userData.updated_at,
    getLastname: state => state.userData.lastname,
    getReverse: state => state.userData.reverse,
    getPasswordResetCode: state => state.userData.password_reset_code,
    getEmailResetCode: state => state.userData.email_reset_code,
    getRole: state => state.userData.role,
    getPhone: state => state.userData.phone,
    getUserType: state => state.userData.usertype,
    getProfilePicture: state => state.userData.profilePicture,
  },
  mutations: {
    INIT_USER_DATA(state, payload) {
      state.userData.id = payload.id
      state.userData.firstname = payload.firstname
      state.userData.email = payload.email
      state.userData.email_verified_at = payload.email_verified_at
      state.userData.created_at = payload.created_at
      state.userData.updated_at = payload.updated_at
      state.userData.lastname = payload.lastname
      state.userData.reverse = payload.reverse
      state.userData.password_reset_code = payload.password_reset_code
      state.userData.email_reset_code = payload.email_reset_code
      state.userData.role = payload.role
      state.userData.phone = payload.phone
      state.userData.usertype = payload.usertype
      state.userData.profilePicture = payload.profilepictureurl
    },
    CLEAR_USER_DATA(state) {
      state.userData.id = null
      state.userData.firstname = ''
      state.userData.email = ''
      state.userData.email_verified_at = null
      state.userData.created_at = null
      state.userData.updated_at = null
      state.userData.lastname = ''
      state.userData.reverse = ''
      state.userData.password_reset_code = ''
      state.userData.email_reset_code = ''
      state.userData.role = null
      state.userData.phone = ''
      state.userData.usertype = ''
      state.userData.profilePicture = ''
    },
    SET_NEW_PROFILE_PIC(state, payload) {
      state.userData.profilePicture = payload.profilePicture
    },
  },
  actions: {
    login({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'login', data: helpers.getLoginForm(payload.form), method: 'post' }).then(res => {
          if (res.data.status === 'success') {
            const userData = res.data.data
            const { token } = userData
            delete userData.token
            localStorage.setItem('token', token)
            localStorage.setItem('user', JSON.stringify(userData))
            commit('INIT_USER_DATA', userData)
            axios.defaults.headers['Authorization'] = `Bearer ${token}`
            resolve(res)
          }
        }).catch(err => {
          reject(err)
        })
      })
    },
    logout({ commit }) {
      return new Promise(resolve => {
        localStorage.clear()
        commit('CLEAR_USER_DATA')
        delete axios.defaults.headers['Authorization']
        resolve()
      })
    },
    getResetLink({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'sendpasswordresetlink', data: helpers.getForgotPasswordForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    resetPassword({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'resetpassword', data: helpers.getResetPasswordForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getAdminDashboard({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchadmindashboard', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
  },
}
