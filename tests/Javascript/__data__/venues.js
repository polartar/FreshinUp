export const FIXTURE_VENUES = [
  {
    uuid: 'acb123',
    name: 'LA Stadium',
    locations: [
      {
        uuid: '123abc',
        name: 'Parking Lot C'
      }
    ]
  }
]

export const FIXTURE_VENUE_ADDITIONAL_DATA = {
  venue: 'acb123',
  location: '123abc',
  address: '12345 LA Stadium Way Los Angeles, CA 90210',
  capacity: 300,
  spots: 30,
  location_details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum'
}

export const FIXTURE_VENUE = FIXTURE_VENUES[0]
