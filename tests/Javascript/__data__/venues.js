export const FIXTURE_VENUES = [
  {
    uuid: 'abc111',
    name: 'LA Stadium',
    address: '123 LA Stadium Way North Velmaville, CA 123',
    locations: [
      {
        uuid: 'lota',
        venue_uuid: 'abc111',
        name: 'North Velmaville',
        capacity: 300,
        spots: 30,
        details: 'Lorem North Velmaville dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      },
      {
        uuid: 'lotc',
        venue_uuid: 'abc111',
        name: 'Lake Mitchellchester',
        capacity: 150,
        spots: 15,
        details: 'Lorem Mitchellchester dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      }
    ]
  },
  {
    uuid: 'abc222',
    name: 'ATL Stadium',
    address: '12345 ATL Stadium Way Lake Miles, CA 90210',
    locations: [
      {
        uuid: 'lotb',
        venue_uuid: 'abc222',
        name: 'Lake Miles',
        capacity: 250,
        spots: 20,
        details: 'Lorem Lake Miles sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      }
    ]
  }
]

export const FIXTURE_VENUE = FIXTURE_VENUES[0]
