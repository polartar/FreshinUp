import module from '~/store/modules/events'

describe('store/modules/events', () => {
  describe('updateItem', () => {
    it('should remove empty/null fields if event is draft(status_id=1)', async () => {
      const item = {}
      const items = []
      const store = module({ items, item })
      const commit = jest.fn()
      const payload = {
        status_id: 1,
        name: 'Event name',
        type: null,
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
        type: 1
      }
      store.actions.updateItem({ commit }, { data: payload })
      expect(commit).toHaveBeenCalledWith('UPDATE_ITEM', {
        data: payload,
        params: {}
      })
    })
  })
})
