import navigation from '@freshinup/core-ui/src/store/modules/navigation'

export default ({ items, item }) => {
  const store = navigation({ items, item })
  store.mutations = {
    ...store.mutations,
    /**
     * @param state
     * @param { { title: string, to: { name: string } }[] } items
     * @constructor
     */
    SET_USER_MENU_ITEMS (state, items) {
      state.userMenuItems = items
    }
  }
  store.actions = {
    ...store.actions,
    actions: {
      setUserMenuItems (context, items) {
        context.commit('SET_USER_MENU_ITEMS', items)
      }
    }
  }
  return store
}
