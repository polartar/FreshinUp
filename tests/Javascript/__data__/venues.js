export const FIXTURE_VENUES = [
  {
    uuid: 'abc111',
    name: 'LA Stadium',
    locations: [
      {
        uuid: 'lota',
        venue_uuid: 'abc111',
        name: 'North Velmaville',
        address: '123 LA Stadium Way North Velmaville, CA 123',
        capacity: 300,
        spots: 30,
        details: 'Lorem North Velmaville dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      },
      {
        uuid: 'lotc',
        venue_uuid: 'abc111',
        name: 'Lake Mitchellchester',
        address: '12345 ATL Stadium Way Mitchellchester, CA 90210',
        capacity: 150,
        spots: 15,
        details: 'Lorem Mitchellchester dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      }
    ]
  },
  {
    uuid: 'abc222',
    name: 'ATL Stadium',
    locations: [
      {
        uuid: 'lotb',
        venue_uuid: 'abc222',
        name: 'Lake Miles',
        address: '12345 ATL Stadium Way Lake Miles, CA 90210',
        capacity: 250,
        spots: 20,
        details: 'Lorem Lake Miles sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      }
    ]
  }
]

export const FIXTURE_VENUE = FIXTURE_VENUES[0]
