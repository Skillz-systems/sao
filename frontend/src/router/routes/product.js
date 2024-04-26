export default [
  {
    path: '/products',
    name: 'products',
    component: () => import('@/views/products/Index.vue'),
    meta: {
      title: 'Products - SAO Energy',
      authRequired: true,
    },
  },
]
