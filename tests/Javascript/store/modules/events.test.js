import module from '~/store/modules/events'
import omit from 'lodash/omit'
import moment from 'moment'
import { DEFAULT_DATE } from 'vue-cli-plugin-freshinup/utils/testing/mockDate'
import { FIXTURE_EVENTS } from '../../__data__/events'

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
        const data = FIXTURE_EVENTS[0]
        store.actions.createItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('CREATE_ITEM', {
          data: omit(data, ['event_recurring_checked', 'event_tags', 'host', 'venue', 'manager', 'status', 'type']),
          params: {}
        })
      })

      test('when start_at & end_at date are in the past', async () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const yesterday = moment(DEFAULT_DATE).add(-1, 'day')
        const data = {
          start_at: `${yesterday.format('YYYY-MM-DD')} 00:00`,
          end_at: `${yesterday.format('YYYY-MM-DD')} 23:59`
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
      it('should picks only allowed params from filters JSON as string', () => {
        let item = {}
        let items = []
        const store = module({ items, item })
        const commit = jest.fn()
        const data = FIXTURE_EVENTS[0]
        store.actions.updateItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data: omit(data, ['event_recurring_checked', 'event_tags', 'host', 'venue', 'manager', 'status', 'type']),
          params: {}
        })
      })
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
        const data = FIXTURE_EVENTS[0]
        store.actions.updateItem({ commit }, { data })
        expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
          data: omit(FIXTURE_EVENTS[0], ['event_recurring_checked', 'type', 'host', 'venue', 'manager', 'status']),
          params: {}
        })
      })
    })
  })
})
