import navigation from '@freshinup/core-ui/src/store/modules/navigation'

export const DEFAULT_USER_MENU_ITEMS = [
  { title: 'My Profile', to: { name: 'myprofile' } },
  { title: 'My Teams', to: { name: 'myteams' } },
  { title: 'My Company', to: { name: 'mycompany' } },
  { title: 'My Settings', to: { name: 'settings' } },
  { title: 'My Company Settings', to: { name: '404' } }
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
    }
  }
  store.actions = {
    ...store.actions,
    // TODO move to core-ui
    setUserMenuItems (context, items) {
      context.commit('SET_USER_MENU_ITEMS', items)
    }
  }
  return store
}
