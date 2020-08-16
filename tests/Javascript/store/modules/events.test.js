import module from '~/store/modules/events'
import omit from 'lodash/omit'

describe('store/modules/events', () => {
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
          host: {
            id: 89,
            uuid: '28138d6d-9605-42e8-9ceb-f2616a514ee5',
            name: 'Swift-Wehner'
          },
          attendees: 10,
          budget: 1000,
          commission_rate: 12,
          commission_type: 2,
          type: {
            id: 2,
            name: 'Cash and Carry'
          }
        }
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data: omit(data, ['event_recurring_checked', 'type', 'host', 'venue', 'manager', 'status']),
          params: {}
        })
      })
    })
  })
})
