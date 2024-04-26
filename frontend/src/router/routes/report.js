export default [
  {
    path: '/reports',
    name: 'reports',
    component: () => import('@/views/Reports.vue'),
    meta: {
      title: 'Reports - SAO Energy',
      authRequired: true,
    },
  },
]
