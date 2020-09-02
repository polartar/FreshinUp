export const FIXTURE_FLEET_MEMBERS = [
  {
    uuid: 'abc123',
    name: 'Da Lobster',
    type_id: 2,
    tags: ['ASIAN', 'VEGAN'],
    pos_system: 'Square',
    business_name: '',
    size_of_truck_trailer: '',
    owner: 'Josh Smith @ Restaurant Inc',
    phone: '938 374822',
    state_of_incorporation: 'California',
    website: 'www.restaurantinc.com',
    twitter: 'www.twitter.com/restaurantinc',
    facebook: 'www.facebook.com/restaurantinc',
    instagram: 'www.instagram.com/restaurantinc',
    staff_notes: 'Only visible for Food Fleet Staff',
    has_expired_licences_docs: false,
    events: [
      {
        uuid: 'bc',
        start_at: '2019-07-12 08:31:00',
        end_at: '2030-07-12 23:59:59',
        declined: false
      }
    ]
  },
  {
    uuid: 'abc345',
    name: 'Rekindler',
    type_id: 2,
    tags: ['VEGAN'],
    pos_system: 'Square',
    business_name: '',
    size_of_truck_trailer: '',
    owner: 'Josh Smith @ Dibitery Inc',
    phone: '938 374822',
    state_of_incorporation: 'New York',
    website: 'www.restaurantinc.com',
    twitter: 'www.twitter.com/restaurantinc',
    facebook: 'www.facebook.com/restaurantinc',
    instagram: 'www.instagram.com/restaurantinc',
    staff_notes: 'Only visible for Food Fleet Staff',
    has_expired_licences_docs: false,
    events: []
  },
  {
    uuid: 'abc567',
    name: 'Fiora',
    type_id: 2,
    tags: ['ASIAN'],
    pos_system: 'Square',
    business_name: '',
    size_of_truck_trailer: '',
    owner: 'Josh Smith @ Dibitery Inc',
    phone: '938 374822',
    state_of_incorporation: 'California',
    website: 'www.restaurantinc.com',
    twitter: 'www.twitter.com/restaurantinc',
    facebook: 'www.facebook.com/restaurantinc',
    instagram: 'www.instagram.com/restaurantinc',
    staff_notes: 'Only visible for Food Fleet Staff',
    has_expired_licences_docs: true,
    events: []
  },
  {
    uuid: 'op123',
    name: 'Trundle',
    type_id: 2,
    tags: ['ASIAN', 'VEGAN'],
    pos_system: 'Square',
    business_name: '',
    size_of_truck_trailer: '',
    owner: 'Josh Smith @ Restaurant Inc',
    phone: '938 374822',
    state_of_incorporation: 'California',
    website: 'www.restaurantinc.com',
    twitter: 'www.twitter.com/restaurantinc',
    facebook: 'www.facebook.com/restaurantinc',
    instagram: 'www.instagram.com/restaurantinc',
    staff_notes: 'Only visible for Food Fleet Staff',
    has_expired_licences_docs: false,
    events: [
      {
        uuid: 'bdsgc',
        start_at: '2019-07-12 08:31:00',
        end_at: '2030-07-12 23:59:59',
        declined: false
      }
    ]
  },
  {
    uuid: 'ddd123',
    name: 'La fourchette',
    type_id: 2,
    tags: ['VEGAN'],
    pos_system: 'Square',
    business_name: '',
    size_of_truck_trailer: '',
    owner: 'Josh Smith @ Restaurant Inc',
    phone: '938 374822',
    state_of_incorporation: 'California',
    website: 'www.restaurantinc.com',
    twitter: 'www.twitter.com/restaurantinc',
    facebook: 'www.facebook.com/restaurantinc',
    instagram: 'www.instagram.com/restaurantinc',
    staff_notes: 'Only visible for Food Fleet Staff',
    has_expired_licences_docs: false,
    events: [
      {
        uuid: 'bc',
        start_at: '2019-07-12 08:31:00',
        end_at: '2030-07-12 23:59:59',
        declined: true
      }
    ]
  }
]

export const FIXTURE_MEMBER_TYPES = [
  {
    id: 2,
    name: 'Mobile'
  }
]

export const FIXTURE_MEMBER_LOCATIONS = ['Square']

export const FIXTURE_FLEET_MEMBER_EVENT = {
  uuid: 'bc',
  start_at: '2019-07-12 08:31:00',
  end_at: '2030-07-12 23:59:59'
}

export const FIXTURE_FLEET_MEMBER = FIXTURE_FLEET_MEMBERS[0]

export const FIXTURE_FLEET_MEMBERS_STATUSES = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Revision' },
  { id: 4, name: 'Rejected' },
  { id: 5, name: 'Approved' }
]
