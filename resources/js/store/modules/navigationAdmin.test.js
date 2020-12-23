import module, { DEFAULT_ITEMS } from './navigationAdmin'

describe('store/modules/navigationAdmin', () => {
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
    test('logo', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('logo', 'https://freshinup.com/wp-content/uploads/2018/08/logo_Freshinup_final-1.png')
    })
    test('headerImage', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('headerImage')
    })
    test('footerColor', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('footerColor', 'accent')
    })
    test('items', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('items', DEFAULT_ITEMS)
    })
    test('isDrawerOpen', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('isDrawerOpen', true)
    })
    test('hideIcons', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('hideIcons', false)
    })
    test('breadcrumbs', () => {
      const store = module({})
      expect(store).toHaveProperty('state')
      expect(store.state).toHaveProperty('breadcrumbs', [])
    })
    // TODO test initialState override
  })

  describe('Mutations', () => {
    test('breadcrumbs', () => {
      const store = module({})
      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.mutations.breadcrumbs(store.state, items)
      expect(store.state.breadcrumbs).toMatchObject(items)
    })
    test('SET_ITEMS', () => {
      const store = module({})
      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.mutations.SET_ITEMS(store.state, items)
      expect(store.state.items).toMatchObject(items)
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
    test('setItems', () => {
      const store = module({})
      const commit = jest.fn()
      const items = [
        { title: 'My Profile', to: { name: 'myprofile' } }
      ]
      store.actions.setItems({ commit }, items)
      expect(commit).toHaveBeenCalledWith('SET_ITEMS', items)
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
