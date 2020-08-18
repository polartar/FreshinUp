import module from '~/store/modules/events'
import omit from 'lodash/omit'
import moment from 'moment'

describe('store/modules/events', () => {
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
      test('picks only allowed params from filters JSON as string', () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
          name: 'accusantium',
          status_id: 1,
          status: {
            id: 1,
            name: 'Draft'
          },
          start_at: '2019-10-10 11:04:19',
          end_at: '2019-10-12 11:04:19',
          manager: {
            id: 1,
            uuid: 'c6be43eb-461f-4654-82b5-7dd6a6f11e54',
            name: 'Demo Admin'
          },
          venue: {
            uuid: '4b2e762d-ec19-44ef-a1ad-78e7c45dec00',
            name: 'New Hattie'
          },
          event_recurring_checked: 'yes',
          event_tags: [
            {
              uuid: 'ff4b2b90-ad3a-41a5-aeaf-6cade3854674',
              name: 'minus'
            },
            {
              uuid: 'f05ef6a0-b149-40d1-a571-bc725ea9cf7a',
              name: 'hic'
            }
          ],
          host: {
            id: 89,
            uuid: '28138d6d-9605-42e8-9ceb-f2616a514ee5',
            name: 'Swift-Wehner'
          },
          attendees: 10,
          budget: 1000,
          commission_rate: 12,
          commission_type: 2,
          type_id: 1
        }
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data: omit(data, ['event_recurring_checked', 'event_tags', 'host', 'venue', 'manager', 'status']),
          params: {}
        })
      })

      test('when start_at & end_at date are in the past', async () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = {
          start_at: '2020-01-10',
          end_at: '2020-01-11'
        }
        store.actions.createItem({ commit }, { data })
        const tomorrow = moment().add(1, 'day')
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data: {
            start_at: `${tomorrow.format('YYYY-MM-DD')} 00:00`,
            end_at: `${tomorrow.format('YYYY-MM-DD')} 23:59`
          },
          params: {}
        })
      })
    })

    describe('updateItem', () => {
      it('should remove empty/null fields if event is draft(status_id=1)', async () => {
        const item = {}
        const items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const payload = {
          status_id: 1,
          name: 'Event name',
          type_id: null,
          location_uuid: null,
          start_at: null,
          end_at: null,
          staff_notes: null,
          member_notes: null,
          customer_notes: null,
          host_uuid: null,
          host_status: null,
          manager_uuid: '',
          budget: '',
          attendees: '',
          commission_rate: '',
          commission_type: null
        }
        store.actions.updateItem({ commit }, { data: payload })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data: {
            status_id: 1,
            name: 'Event name'
          },
          params: {}
        })
      })
      it('should send all fields otherwise', async () => {
        const item = {}
        const items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const payload = {
          uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
          name: 'accusantium',
          status_id: 1,
          start_at: '2019-10-10 11:04:19',
          end_at: '2019-10-12 11:04:19',
          staff_notes: 'Example food fleet staff notes',
          member_notes: 'Example fleet member notes',
          customer_notes: 'Example customer notes',
          host_status: 2,
          manager_uuid: 'c6be43eb-461f-4654-82b5-7dd6a6f11e54',
          venue_uuid: '4b2e762d-ec19-44ef-a1ad-78e7c45dec00',
          host_uuid: '28138d6d-9605-42e8-9ceb-f2616a514ee5',
          attendees: 10,
          budget: 1000,
          commission_rate: 12,
          commission_type: 2,
          type_id: 1
        }
        store.actions.updateItem({ commit }, { data: payload })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data: payload,
          params: {}
        })
      })
    })
  })
})
