export default [
  {
    path: '/messages',
    name: 'messages',
    component: () => import('@/views/Messages.vue'),
    meta: {
      title: 'Messages - SAO Energy',
      authRequired: true,
    },
  },
]
