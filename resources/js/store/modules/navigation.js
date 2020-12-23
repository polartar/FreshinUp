import navigation from '@freshinup/core-ui/src/store/modules/navigation'

export const DEFAULT_USER_MENU_ITEMS = [
  { title: 'My Profile', to: { name: 'myprofile' } },
  { title: 'My Teams', to: { name: 'myteams' } },
  { title: 'My Company', to: { name: 'mycompany' } },
  { title: 'My Settings', to: { name: 'settings' } },
  { title: 'My Company Settings', to: { name: '404' } }
]

export const SUPPLIER_ITEMS = [
  {
    action: 'icon-dashboard',
    title: 'Dashboard',
    to: '/admin/supplier/dashboard' // already exist as new dashboard
    // old dashboard is the onboarding screen. There is a ticket for that
  },
  {
    action: 'icon-companies',
    title: 'My company',
    to: '/admin/supplier/company' // create page with coming soon
  },
  {
    action: 'icon-trucks',
    title: 'My Fleet',
    to: '/admin/supplier/stores' // create page with coming soon
  },
  {
    action: 'icon-events',
    title: 'Events',
    to: '/admin/supplier/events' // create page with coming soon
  },
  {
    action: 'icon-documents',
    title: 'Documents',
    to: '/admin/supplier/documents' // create page with coming soon
  }
]

export const SUPPLIER_USER_MENU_ITEMS = [
  { title: 'My Profile', to: { name: 'myprofile' } }
]

export default (initialState = {}) => {
  const store = navigation(initialState)
  store.mutations = {
    ...store.mutations,
    SET_USER_MENU_ITEMS (state, items) {
      state.userMenuItems = items
    },
    SET_DRAWER_ITEMS (state, items) {
      state.drawerItems = items
    }
  }
  store.actions = {
    ...store.actions,
    // TODO move to core-ui
    setUserMenuItems (context, items) {
      context.commit('SET_USER_MENU_ITEMS', items)
    },
    setDrawerItems (context, items) {
      context.commit('SET_DRAWER_ITEMS', items)
    }
  }
  return store
}
