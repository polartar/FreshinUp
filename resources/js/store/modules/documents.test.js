import axios from 'axios'
import module from './documents'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'
import MockAdapter from 'axios-mock-adapter'
import find from 'lodash/find'

describe('store/modules/documents', () => {
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

  describe('Actions', () => {
    describe('acceptContract(uuid)', () => {
      test('when payload.params.id not defined', () => {
        expect.assertions(1)
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        return store.actions.acceptContract({ commit }, { params: { id: null } })
          .catch(error => {
            expect(error.message).toEqual('[Document/Accept]: Document uuid is not defined.')
          })
      })
      test.skip('otherwise', () => {
        // TODO: axios instance issue. skipped for now
        let item = {}
        let items = []
        const document = FIXTURE_DOCUMENTS[0]
        const updatedDocument = { ...document, signed_at: '2020-11-05T20:00' }
        const mock = new MockAdapter(axios)
        mock.onPost(/documents\/[A-z0-9-]+\/accept/)
          .reply(200, updatedDocument)
        const store = module({ items, item })
        const commit = jest.fn()
        store.actions.acceptContract({ commit }, { params: { id: document.uuid } })
        const request = find(mock.history.post, (req) => req.url === `api/documents/${document.uuid}/accept`)
        expect(request).toBeDefined()
        expect(commit).toHaveBeenCalledWith('PATCH_ITEM', {
          data: updatedDocument
        })
      })
    })
  })
})
