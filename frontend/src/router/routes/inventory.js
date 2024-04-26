export default [
  {
    path: '/inventory-overview',
    name: 'inventory-overview',
    component: () => import('@/views/inventory/Overview.vue'),
    meta: {
      title: 'Inventory Overview - SAO Energy',
      authRequired: true,
    },
  },
  {
    path: '/inventory-categories',
    name: 'inventory-categories',
    component: () => import('@/views/inventory/Categories.vue'),
    meta: {
      title: 'Inventory Categories - SAO Energy',
      authRequired: true,
    },
  },
  // {
  //   path: '/available-stock',
  //   name: 'available-stock',
  //   component: () => import('@/views/inventory/AvailableStock.vue'),
  //   meta: {
  //     title: 'Available Stock - SAO Energy',
  //     authRequired: true,
  //   },
  // },
  {
    path: '/audit-log',
    name: 'audit-log',
    component: () => import('@/views/inventory/AuditLog.vue'),
    meta: {
      title: 'Audit Log - SAO Energy',
      authRequired: true,
    },
  },
]
