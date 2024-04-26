import Vue from 'vue'
import VueRouter from 'vue-router'

import auth from '@/router/routes/auth'
import product from '@/router/routes/product'
import client from '@/router/routes/client'
import project from '@/router/routes/project'
import report from '@/router/routes/report'
import message from '@/router/routes/message'
import account from '@/router/routes/account'
import inventory from '@/router/routes/inventory'
import payment from '@/router/routes/payment'
import connections from '@/router/routes/connection'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'overview',
      component: () => import('@/views/overview/Index.vue'),
      meta: {
        title: 'Overview - SAO Energy',
        authRequired: true,
      },
    },
    ...auth,
    ...product,
    ...client,
    ...project,
    ...report,
    ...message,
    ...account,
    ...inventory,
    ...payment,
    ...connections,
    {
      path: '/error-404',
      name: 'error-404',
      component: () => import('@/views/error/Error404.vue'),
      meta: {
        title: 'Not Found - SAO Energy',
        layout: 'full',
      },
    },
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

// eslint-disable-next-line no-unused-vars
router.beforeEach((to, from, next) => {
  document.title = to.meta.title
  const token = localStorage.getItem('token')
  if (to.meta.authRequired && !token) {
    router.push('login')
  }
  return next()
})

export default router
