import module, { DEFAULT_USER_MENU_ITEMS } from './navigation'
describe('store/modules/navigation', () => {
  test('is a namespaced module', () => {
    const store = module({})
    expect(store).toHaveProperty('namespaced', true)
  })

  describe('State', () => {
    test('title', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('title', 'FreshPlatform')
    })
    test('drawerItems', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('drawerItems', [])
    })
    test('userMenuItems', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('userMenuItems', DEFAULT_USER_MENU_ITEMS)
    })
    test('isConsumerViewAvailable', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('isConsumerViewAvailable', true)
    })
    test('hideUserLevel', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('hideUserLevel', false)
    })
    test('displayedUserField', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('displayedUserField', 'title,company_name')
    })
    test('items', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('items', [])
    })
    test('breadcrumbs', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('breadcrumbs', [])
    })
    // TODO test initialState override
  })

  describe('Mutations', () => {
    test('SET_USER_MENU_ITEMS', () => {
      const store = module({})
      expect(store.state.userMenuItems).toMatchObject(DEFAULT_USER_MENU_ITEMS)

      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.mutations.SET_USER_MENU_ITEMS(store.state, items)
      expect(store.state.userMenuItems).toMatchObject(items)
    })

    test('SET_DRAWER_ITEMS', () => {
      const store = module({})
      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.mutations.SET_DRAWER_ITEMS(store.state, items)
      expect(store.state.drawerItems).toMatchObject(items)
    })
  })

  describe('Actions', () => {
    test('setUserMenuItems', () => {
      const store = module({})
      const commit = jest.fn()
      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.actions.setUserMenuItems({ commit }, items)
      expect(commit).toHaveBeenCalledWith('SET_USER_MENU_ITEMS', items)
    })

    test('setDrawerItems', () => {
      const store = module({})
      const commit = jest.fn()
      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.actions.setDrawerItems({ commit }, items)
      expect(commit).toHaveBeenCalledWith('SET_DRAWER_ITEMS', items)
    })
  })
})
