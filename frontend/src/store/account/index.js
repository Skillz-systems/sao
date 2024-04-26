/* eslint-disable */
import axios from '@/libs/axios'
import helper from './account-helpers'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutators: {},
  actions: {
    getTeamMembers({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchteammembers', method: 'get'}).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addTeamMember({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'inviteteammember', data: helper.getAddTeamMember(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    updateTeamMember({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'updateteammember', data: helper.getUpdateTeamMember(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    updatePassword({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'updatepasswordfrominside', data: helper.getUpdatePassword(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getClientAccount({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'getloggedinclient', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    updateProfilePicture({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'loggedinuseruploadprofilepicture', data: helper.getUpdateProfilePicture(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    }
  },
}
