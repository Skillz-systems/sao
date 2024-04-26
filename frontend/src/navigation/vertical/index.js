export default [
  {
    title: 'Dashboard',
    route: 'overview',
    icon: 'overview.svg',
    access: 'client',
  },
  {
    title: 'Inventory',
    route: null,
    icon: 'inventory.svg',
    children: [
      {
        title: 'Categories',
        route: 'inventory-categories',
        icon: 'circle',
      },
      {
        title: 'Stockpile',
        route: 'inventory-overview',
        icon: 'circle',
      },
      // {
      //   title: 'Available Stock',
      //   route: 'available-stock',
      //   icon: 'circle',
      // },
      {
        title: 'Audit Log',
        route: 'audit-log',
        icon: 'circle',
      },
    ],
  },
  {
    title: 'Products',
    route: 'products',
    icon: 'products.svg',
  },
  {
    title: 'Clients',
    route: 'clients',
    icon: 'clients.svg',
  },
  {
    title: 'Projects',
    route: 'projects',
    icon: 'projects.svg',
    access: 'client',
  },
  {
    title: 'Invoicing and Payments',
    route: 'invoicing',
    icon: 'reports.svg',
  },
  {
    title: 'Reports',
    route: 'reports',
    icon: 'reports.svg',
  },
 
  {
    title: 'Communication',
    route: 'messages',
    icon: 'messages.svg',
    access: 'client',
  },
  {
    title: 'Account',
    route: 'account',
    icon: 'account.svg',
    access: 'client',
  },
]
