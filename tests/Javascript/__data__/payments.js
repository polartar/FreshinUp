export const FIXTURE_PAYMENTS = [
  {
    status: 1,
    event_name: 'Random event name',
    name: 'Event venue fee',
    due_date: '2020-07-08',
    amount_money: 300,
    venue: 'Convention center',
    venue_due_date: '2020-09-09',
    description: 'Pariatur et similique mollitia quia nihil'
  },
  {
    status: 2,
    event_name: 'Random event name',
    name: 'Event venue fee',
    due_date: '2020-07-08',
    amount_money: 300,
    venue: 'Convention center',
    venue_due_date: '2020-09-09',
    description: 'Pariatur et similique mollitia quia nihil'
  },
  {
    status: 3,
    event_name: 'Random event name',
    name: 'Event venue fee',
    due_date: '2020-07-08',
    amount_money: 300,
    venue: 'Convention center',
    venue_due_date: '2020-09-09',
    description: 'Pariatur et similique mollitia quia nihil'
  }
].map((payment, i) => {
  return {
    ...payment,
    store_uuid: FIXTURE_STORES[i].uuid,
    event_uuid: FIXTURE_EVENTS[i].uuid
  }
})

export const FIXTURE_PAYMENT = FIXTURE_PAYMENTS[0]
