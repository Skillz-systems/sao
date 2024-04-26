/* eslint-disable */
import axios from '@/libs/axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutators: {},
  actions: {
    getMessageByProjectID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `fetchmessagesbyprojectid/${payload.projectID}`, method: 'get'}).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    }
  },
}
