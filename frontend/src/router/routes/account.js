export default [
  {
    path: '/account',
    name: 'account',
    component: () => import('@/views/account/Index.vue'),
    meta: {
      title: 'Account - SAO Energy',
      authRequired: true,
    },
  },
]
