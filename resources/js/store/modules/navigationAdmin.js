import navigationAdmin from '@freshinup/core-ui/src/store/modules/navigationAdmin'

export const DEFAULT_ITEMS = [
  {
    action: 'dashboard',
    title: 'Dashboard',
    items: [
      { title: 'Home', to: { name: 'admin' } }
    ]
  },
  {
    action: 'supervised_user_circle',
    title: 'Base User',
    active: false,
    items: [
      { title: 'Users', to: { path: '/admin/users' } },
      { title: 'Teams', to: { path: '/admin/teams' } },
      { title: 'Companies', to: { path: '/admin/companies' } }
    ]
  },
  {
    action: 'settings',
    title: 'Platform Settings',
    active: false,
    items: [
      { title: 'Terms', to: { name: 'admin-terms' } }
    ]
  }
]

export default (initialState = {}) => {
  const store = navigationAdmin(initialState)
  store.mutations = {
    ...store.mutations,
    SET_ITEMS (state, items) {
      state.items = items
    },
    SET_DRAWER_ITEMS (state, items) {
      state.drawerItems = items
    }
  }
  store.actions = {
    ...store.actions,
    // TODO move to core-ui
    setItems (context, items) {
      context.commit('SET_ITEMS', items)
    },
    setDrawerItems (context, items) {
      context.commit('SET_DRAWER_ITEMS', items)
    }
  }
  return store
}
