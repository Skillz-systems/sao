/* eslint-disable */
import axios from '@/libs/axios'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutators: {},
  actions: {
    getReports({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchreport', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getSalesByStateID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `fetchsalesbystateid/${payload.stateID}`, method: 'get'}).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getWeeklyLine({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchweeklylinechart', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getYearlyLine({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchyearlylinechart', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getMonthlyLine({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchmonthlylinechart', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    }
  },
}
