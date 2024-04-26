export default [
  {
    path: '/clients',
    name: 'clients',
    component: () => import('@/views/clients/Index.vue'),
    meta: {
      title: 'Clients - SAO Energy',
      authRequired: true,
    },
  },
  {
    path: '/clients/add-client',
    name: 'add-client',
    component: () => import('@/views/clients/AddClient.vue'),
    meta: {
      title: 'Add Client - SAO Energy',
      navActiveLink: 'clients',
      authRequired: true,
    },
  },
  {
    path: '/clients/edit-client',
    name: 'edit-client',
    component: () => import('@/views/clients/EditClient.vue'),
    meta: {
      title: 'Edit Client - SAO Energy',
      navActiveLink: 'clients',
      authRequired: true,
    },
  },
  {
    path: '/auth/update-password/:id',
    name: 'passwordUpdate',
    component: () => import('@/views/UpdateInviteePassword/Index.vue'),
    meta: {
      title: 'Password-Update - SAO Energy',
      layout: 'full',
    },
  },
]
