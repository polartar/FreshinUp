export const FIXTURE_VENUES = [
  {
    id: 1,
    uuid: '604061d2-7253-34ec-95bd-d31aa42e7fd4',
    name: 'LA Stadium',
    address: '381 Jade Rest Suite 601 45753, Okuneva Heights Suite 101',
    address_line_1: '381 Jade Rest Suite 601',
    address_line_2: '45753 Okuneva Heights Suite, 101',
    status_id: 3,
    owner_uuid: 'd111',
    owner: {
      uuid: 'd111',
      name: 'Demo Admin',
      email: 'demoAdmin@example.com',
      mobile_phone: '321-123-1234',
      avatar: 'https://via.placeholder.com/800x600.png'
    },
    latitude: 17.373867,
    longitude: -152.304272,
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
    created_at: '2019-09-30T03:51:14.000000Z'
  },
  {
    uuid: 'abc111',
    id: 1,
    name: '',
    owner_uuid: '',
    address: '123 LA Stadium Way North Velmaville, CA 123',
    address_line_1: '123 LA Stadium Way',
    address_line_2: 'North Velmaville, CA 123',

    status_id: 1
  },
  {
    id: 2,
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
    created_at: '2019-09-30T03:51:14.000000Z'
  },
  {
    id: 3,
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
    owner_uuid: 'o111',
    owner: {
      uuid: 'o111',
      name: 'Demo Admin',
      email: 'demoAdmin@example.com',
      mobile_phone: '321-123-1234',
      avatar: 'https://via.placeholder.com/800x600.png'
    },
    created_at: '2019-09-30T03:51:14.000000Z'
  }
]

export const FIXTURE_VENUE = FIXTURE_VENUES[0]

export const EMPTY_VENUE = {
  name: '',
  owner_uuid: '',
  address: '',
  address_line_1: '',
  address_line_2: '',
  status_id: ''
}
