/* eslint-disable */
import axios from '@/libs/axios'
import helper from './project-helpers'

export default {
  namespaced: true,
  state: {},
  getters: {},
  mutators: {},
  actions: {
    getProjectTypes({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchavailableprojecttypes', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getProjectStatus({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchavailableprojectstatus', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addProject({ commit }, payload) {
      console.log(payload, 'sdjsddsjjks')
      return new Promise((resolve, reject) => {
        axios({ url: 'createproject', data: helper.getAddProjectForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addMeshConnection({ commit }, payload) {
      console.log(payload, 'sdjsddsjjks')
      return new Promise((resolve, reject) => {
        axios({ url: 'create-meshgrid-connections', data: helper.getAddMeshConnectionForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    addMeshGridPod({ commit }, payload) {
      console.log(payload, 'sdjsddsjjks')
      return new Promise((resolve, reject) => {
        axios({ url: `link-meshgrid-to-project/${payload.data.meshgridId}`, params: payload.data, method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    
    getProjects({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchprojects', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getProjectByID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `getprojectbyid/${payload.projectID}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getMeshProjectByID({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: `get-meshgrid-with-projectid/${payload.projectID}`, method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
   
    sendMessage({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'sendmessage', data: helper.getSendMessageForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    getClientProjects({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'fetchclientprojects', method: 'get' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    editProject({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'editproject', data: helper.getEditProjectForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    deleteProject({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios({ url: 'deleteproject', data: helper.getDeleteProjectForm(payload.form), method: 'post' }).then(res => {
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
  },
}
