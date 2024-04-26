/* eslint-disable */
import axios from '@/libs/axios'
import helpers from './product-helpers'

export default {
  namespaced: true,
  state: {
    products: [],
    numProducts: 0
  },
  getters: {
    getProducts: state => state.products,
    getNumProducts: state => state.numProducts
  },
  mutations: {
    SET_PRODUCTS(state, payload) {
      state.products = payload
      state.numProducts = payload.length
    }
  },
  actions: {
    addProduct({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'createproduct', data: helpers.getAddProductForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getProducts({ commit }, params) {

      return new Promise((resolve, reject) => {
        axios({
          url: 'fetchproducts',
          method: 'get',
          // params
        })
          .then(res => {
            const productsData = res.data.data
            commit('SET_PRODUCTS', productsData)
            resolve(res)
          })
          .catch(err => {
            reject(err)
          })
      })
    },
    getProductByID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `getprojectbyid/${payload.productID}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    editProduct({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'editproduct', data: helpers.getEditProductForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
 
    deleteProduct({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'deleteproduct', data: helpers.getDeleteProductForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    }
  },
}
