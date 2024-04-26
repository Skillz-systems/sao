export default [
  {
    path: '/payment/:id',
    name: 'payment',
    component: () => import('@/views/payment/Index.vue'),
    meta: {
      title: 'Payment - SAO Energy',
      layout: 'full',
    },
  },
  {
    path: '/payment-plan/:id',
    name: 'payment',
    component: () => import('@/views/payment/PaymentPattern.vue'),
    meta: {
      title: 'Payment - SAO Energy',
      layout: 'full',
    },
  },
  // {
  //   path: '/invoicing',
  //   name: 'invoicing',
  //   component: () => import('@/views/payment/InvoicingAndPayments.vue'),
  //   meta: {
  //     title: 'Invoicing - SAO Energy',
  //     layout: 'full',
  //   },
  // },

  // {
  //   path: '/invoicing',
  //   name: 'invoicing',
  //   component: () => import('@/views/payment/InvoicingAndPayments.vue'),
  //   meta: {
  //     title: 'Add Mesh Grids - SAO Energy',
  //     navActiveLink: 'projects',
  //     authRequired: true,
  //   },
  // },

  

  {
    path: '/payment-success',
    name: 'paymentSuccess',
    component: () => import('@/views/payment/PaymentSuccess.vue'),
    meta: {
      title: 'Payment-Success - SAO Energy',
      layout: 'full',
    },
  },
]
