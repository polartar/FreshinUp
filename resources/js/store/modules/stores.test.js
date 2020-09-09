import module from './stores'

describe('store/modules/stores', () => {
  test('the state has items', () => {
    const item = {}
    const items = []
    const result = module({ items, item })
    expect(result).toHaveProperty('state')
    expect(result.state).toHaveProperty('items', items)
  })

  test('the state has item', () => {
    const item = {}
    const items = []
    const result = module({ items, item })
    expect(result).toHaveProperty('state')
    expect(result.state).toHaveProperty('item', item)
  })

  test('is a namespaced module', () => {
    let item = {}
    let items = []
    const result = module({ items, item })
    expect(result).toHaveProperty('namespaced', true)
  })

  describe('Actions', () => {
    describe('createItem()', () => {
      test('when there is no tag', () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          name: 'some name'
        }
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data,
          params: {}
        })
      })

      test('when `tags` is an array of object', () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          name: 'some name',
          tags: [
            { uuid: 'a1', name: 'aa' },
            { uuid: 'b1', name: 'bb' }
          ]
        }
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data: {
            name: 'some name',
            tags: ['a1', 'b1']
          },
          params: {}
        })
      })

      test('when `tags` is an array of string', async () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          name: 'some name',
          tags: ['a1', 'b1']
        }
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data,
          params: {}
        })
      })
    })
    describe('updateItem()', () => {
      test('when there is no tag', () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          name: 'some name'
        }
        store.actions.updateItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data,
          params: {}
        })
      })

      test('when `tags` is an array of object', () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          name: 'some name',
          tags: [
            { uuid: 'a1', name: 'aa' },
            { uuid: 'b1', name: 'bb' }
          ]
        }
        store.actions.updateItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data: {
            name: 'some name',
            tags: ['a1', 'b1']
          },
          params: {}
        })
      })

      test('when `tags` is an array of string', async () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          name: 'some name',
          tags: ['a1', 'b1']
        }
        store.actions.updateItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data,
          params: {}
        })
      })
    })
  })
})
