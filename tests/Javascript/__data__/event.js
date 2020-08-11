export const EMPTY_EVENT = {
  uuid: null,
  name: null,
  status_id: null,
  start_at: null,
  end_at: null,
  staff_notes: null,
  member_notes: null,
  customer_notes: null,
  attendees: null,
  budget: null,
  commission_rate: null,
  commission_type: null,
  type: null
}
export const FIXTURE_EVENT = {
  uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
  name: 'accusantium',
  status_id: 1,
  start_at: '2019-10-10 11:04:19',
  end_at: '2019-10-12 11:04:19',
  staff_notes: 'Example food fleet staff notes',
  member_notes: 'Example fleet member notes',
  customer_notes: 'Example customer notes',
  host_status: 2,
  manager: {
    id: 1,
    uuid: 'c6be43eb-461f-4654-82b5-7dd6a6f11e54',
    name: 'Demo Admin'
  },
  venue: {
    uuid: '4b2e762d-ec19-44ef-a1ad-78e7c45dec00',
    name: 'New Hattie'
  },
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
  type: 1
}
