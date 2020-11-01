import omit from 'lodash/omit'
import module from './documentTemplates'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'

describe('store/modules/documentTemplates', () => {
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

  test.skip('is a namespaced module', () => {
    let item = {}
    let items = []
    const result = module({ items, item })
    expect(result).toHaveProperty('namespaced', true)
  })

  describe('getters', () => {
    test('STATUS', () => {
      const item = {}
      const items = []
      const store = module({ items, item })
      expect(store.getters.STATUS({})).toMatchObject({
        DRAFT: 1,
        PUBLISHED: 2
      })
    })
  })

  describe('Actions', () => {
    describe('createItem()', () => {
      test('should exclude unwanted keys', async () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = FIXTURE_DOCUMENT_TEMPLATES[0]
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data: omit(data, ['id', 'uuid', 'status', 'updated_by', 'updated_at', 'created_at']),
          params: {}
        })
      })
    })
    describe('updateItem()', () => {
      test('should exclude unwanted keys', async () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = FIXTURE_DOCUMENT_TEMPLATES[0]
        store.actions.updateItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data: omit(data, ['id', 'uuid', 'status', 'updated_by', 'updated_at', 'created_at']),
          params: {}
        })
      })
    })
  })
})
