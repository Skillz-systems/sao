import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'
import auth from './auth'
import product from './product'
import client from './client'
import project from './project'
import report from './report'
import message from './message'
import account from './account'
import inventory from './inventory'


Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    app,
    appConfig,
    verticalMenu,
    auth,
    product,
    client,
    project,
    report,
    message,
    account,
    inventory,
  },
  strict: process.env.DEV,
})
