export const FIXTURE_EVENT_HISTORIES = [
  {
    id: 1,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'Draft',
    completed: true,
    date: '2020-08-18T21:54:43',
    description: 'Event was created in the system and submitted for approval'
  },
  {
    id: 2,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'FoodFleet Initial Review',
    completed: true,
    date: '2020-08-19T21:58:43',
    description: 'Food Fleet Staff will review the event request'
  },
  {
    id: 3,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'Customer Agreement',
    completed: false,
    date: '2020-08-20T21:58:43',
    description: 'Customer will review / sign event agreement and terms'
  },
  {
    id: 4,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'Fleet Member Selection',
    completed: false,
    date: '2020-08-21T21:58:43',
    description: 'FoodFleet will define event menu and identify interested Fleet Members and authorize work order'
  },
  {
    id: 5,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'Customer Review',
    completed: false,
    date: '2020-08-22T21:58:43',
    description: 'Customer will review interested Fleet Members and authorize work order'
  },
  {
    id: 6,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'Fleet Member Contracts',
    completed: false,
    date: '2020-08-23T21:58:43',
    description: 'Approved Fleet Members will review and sign event contracts'
  },
  {
    id: 7,
    event_uuid: 'abc123',
    status_id: 1,
    name: 'Event Confirmation',
    completed: false,
    date: '2020-08-24T21:58:43',
    description: 'Customer will review and sign the final event contract'
  }
]

export const FIXTURE_EVENT_HISTORY = FIXTURE_EVENT_HISTORIES[0]
