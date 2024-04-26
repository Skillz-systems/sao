import Vue from 'vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'

import baseMixins from '@/mixins/_base-mixins'
import router from './router'
import store from './store'
import App from './App.vue'

// Global Components
import './global-components'

// 3rd party plugins
import '@/libs/portal-vue'
import '@/libs/toastification'
import '@/libs/sweet-alerts'

// BSV Plugin Registration
// Vue.use(ToastPlugin)
// Vue.use(ModalPlugin)

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
// Composition API
Vue.use(VueCompositionAPI)

// import core styles
require('@core/scss/core.scss')

// import assets styles
require('@/assets/scss/style.scss')

Vue.config.productionTip = false
Vue.mixin(baseMixins)

new Vue({
  router,
  store,
  created() {
    this.reinitializeSession()
  },
  render: h => h(App),
}).$mount('#app')
