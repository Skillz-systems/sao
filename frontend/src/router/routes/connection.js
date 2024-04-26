export default [

  {
    path: '/connections/edit-connections',
    name: 'edit-connection',
    component: () => import('@/views/connections/EditConnection.vue'),
    meta: {
      title: 'Edit Connection - SAO Energy',
      navActiveLink: 'connections',
      authRequired: true,
    },
  },
  
  {
    path: '/connections/add-connections',
    name: 'add-connection',
    component: () => import('@/views/connections/AddConnection.vue'),
    meta: {
      title: 'Add Connection - SAO Energy',
      navActiveLink: 'connections',
      authRequired: true,
    },
  },
 
]
