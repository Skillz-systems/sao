/* eslint-disable */
import axios from '@/libs/axios'
import helpers from './client-helpers'

export default {
  namespaced: true,
  state: {
    clientTypes: [],
    houseSizes: [],
    states: [],
    lgas: [],
  },
  getters: {
    getClientTypes: state => state.clientTypes,
    getHouseSizes: state => state.houseSizes,
    getStates: state => state.states,
    getLgas: state => state.lgas,
  },
  mutations: {
    SET_CLIENT_TYPES(state, payload) {
      state.clientTypes = payload
    },
    SET_HOUSE_SIZES(state, payload) {
      state.houseSizes = payload
    },
    SET_STATES(state, payload) {
      state.states = payload
    },
    SET_LGAS(state, payload) {
      state.lgas = payload
    }
  },
  actions: {
    getClientTypes({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchclienttypes', method: 'get' }).then(res => {
          const { data } = res.data
          commit('SET_CLIENT_TYPES', data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getHouseSizes({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchhousesizes', method: 'get' }).then(res => {
          const { data } = res.data
          commit('SET_HOUSE_SIZES', data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getStates({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchstates', method: 'get' }).then(res => {
          const { data } = res.data
          commit('SET_STATES', data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getLGAsByStateID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `fetchlgasbystateid/${payload.stateID}`, method: 'get' }).then(res => {
          const { data } = res.data
          commit('SET_LGAS', data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addClient({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'addclient', data: helpers.getAddClientForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getClients({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchclients', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getClientByID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `getclientbyclientid/${payload.clientID}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getClientDashboard({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchclientdashboard', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    editClient({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'editclient', data: helpers.getEditClientForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
  },
}
