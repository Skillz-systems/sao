export default [
  {
    path: '/projects',
    name: 'projects',
    component: () => import('@/views/projects/Index.vue'),
    meta: {
      title: 'Projects - SAO Energy',
      authRequired: true,
    },
  },
  {
    path: '/projects/view-project',
    name: 'view-project',
    component: () => import('@/views/projects/ViewProject.vue'),
    meta: {
      title: 'View Project - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
  
  {
    path: '/projects/view-mesh-project',
    name: 'view-mesh-project',
    component: () => import('@/views/projects/ViewMeshProject.vue'),
    meta: {
      title: 'View Mesh Project - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
  {
    path: '/projects/more-mesh-details',
    name: 'more-mesh-details',
    component: () => import('@/views/projects/MoreMeshDetails.vue'),
    meta: {
      title: 'View Mesh Details - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
  {
    path: '/projects/all-mesh-grids',
    name: 'more-mesh-grids',
    component: () => import('@/views/projects/MoreMeshGrids.vue'),
    meta: {
      title: 'View Mesh Grids - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
  {
    path: '/projects/add-mesh-grid',
    name: 'AddGrid-mesh',
    component: () => import('@/views/projects/AddGrid.vue'),
    meta: {
      title: 'Add Mesh Grids - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },

  {
    path: '/invoicing',
    name: 'invoicing',
    component: () => import('@/views/projects/InvoicingAndPayments.vue'),
    meta: {
      title: 'Invoicing - SAO Energy',
      navActiveLink: 'invoicing',
      authRequired: true,
    },
  },


  
  
  
  {
    path: '/projects/add-project',
    name: 'add-project',
    component: () => import('@/views/projects/AddProject.vue'),
    meta: {
      title: 'Add Project - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
  {
    path: '/projects/edit-project',
    name: 'edit-project',
    component: () => import('@/views/projects/EditProject.vue'),
    meta: {
      title: 'Edit Project - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
  {
    path: '/projects/edit-geolocation/:id',
    name: 'edit-geolocation',
    component: () => import('@/views/projects/GetCoordinates.vue'),
    meta: {
      title: 'Edit geolocation - SAO Energy',
      navActiveLink: 'projects',
      authRequired: true,
    },
  },
]
