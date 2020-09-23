export const FIXTURE_VENUES = [
  {
    uuid: 'abc111',
    name: 'LA Stadium',
    owner_uuid: '',
    owner: {
      name: 'Demo Admin',
      email: 'demoAdmin@example.com',
      mobile_phone: '321-123-1234',
      avatar: 'https://via.placeholder.com/800x600.png'
    },
    address: '123 LA Stadium Way North Velmaville, CA 123',
    address_line_1: '123 LA Stadium Way North Velmaville, CA 123',
    address_line_2: '123 LA Stadium Way North Velmaville, CA 123',
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
    ],
    status_id: 1,
    owner_uuid: 'o110',
    owner: {
      uuid: 'o110',
      name: 'Josh Smith @ Restaurant Inc'
    },
    created_at: '2019-09-30T03:51:14.000000Z'
  },
  {
    uuid: 'abc222',
    name: 'ATL Stadium',
    owner_uuid: '',
    owner: {
      name: 'Demo Admin',
      email: 'demoAdmin@example.com',
      mobile_phone: '321-123-1234',
      avatar: 'https://via.placeholder.com/800x600.png'
    },
    address: '123 LA Stadium Way North Velmaville, CA 123',
    address_line_1: '123 LA Stadium Way North Velmaville, CA 123',
    address_line_2: '123 LA Stadium Way North Velmaville, CA 123',
    locations: [
      {
        uuid: 'lotb',
        venue_uuid: 'abc222',
        name: 'Lake Miles',
        capacity: 250,
        spots: 20,
        details: 'Lorem Lake Miles sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
      }
    ],
    status_id: 2,
    owner_uuid: 'o111',
    owner: {
      uuid: 'o111',
      name: 'Josh Smith @ Restaurant Inc'
    },
    created_at: '2019-09-30T03:51:14.000000Z'
  },
  {
    uuid: 'abc223',
    name: 'ATL Stadium 2',
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
    ],
    status_id: 3,
    owner_uuid: 'o222',
    owner: {
      uuid: 'o222',
      name: 'Josh Smith @ Dibitery Inc'
    },
    created_at: '2019-09-30T03:51:14.000000Z'
  }
]

export const FIXTURE_VENUE = FIXTURE_VENUES[0]
