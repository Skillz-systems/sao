export default [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/Login.vue'),
    meta: {
      title: 'Login - SAO Energy',
      layout: 'full',
    },
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: () => import('@/views/auth/ForgotPassword.vue'),
    meta: {
      title: 'Forgot Password - SAO Energy',
      layout: 'full',
    },
  },
  {
    path: '/recoverpassword/:resetcode',
    name: 'recoverpassword',
    component: () => import('@/views/auth/RecoverPassword.vue'),
    meta: {
      title: 'Recover Password - SAO Energy',
      layout: 'full',
    },
  },

]
