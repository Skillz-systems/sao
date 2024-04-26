/* eslint-disable */
import axios from '@/libs/axios'
import helpers from './inventory-helpers'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutators: {},
  actions: {
    getInventory({ commit }, payload, id) {
      console.log(id, payload,'dflkfddkld')
      const formData = new FormData()
      formData.append('id', payload.id)
      formData.append('status', payload.status)  
      formData.append('page', payload.page)  
      return new Promise((resolve, reject) => {
        axios({ url: `viewinventory`, method: 'post', data: formData }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getItems({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchitems', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getSubItemByID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `getsubitemsbyitemid/${payload.itemID}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addStock({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'addstock', data: helpers.getAddStockForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addSubItem({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'createsubitem', data: helpers.getAddSubItemForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    editSubItem({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'editsubitem', data: helpers.getEditSubItemForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    editStock({ commit }, payload) {

      console.log(payload, 'payloadpayload')

      return new Promise((resolve, reject) => {
        axios({ url: `editstock/${payload.id}`, data: helpers.getEditStockForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getAvailableStock({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `viewavailablestocks?page=${payload.page}&perpage=${payload.perPage}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getAuditTrail({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `fetchaudittrail?page=${payload.page}&perpage=${payload.perPage}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    }
  }
}
