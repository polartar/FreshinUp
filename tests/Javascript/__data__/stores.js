export const FIXTURE_STORES = [
  {
    uuid: 'abc111',
    name: 'Da Lobster',
    type_id: 1,
    tags: [
      {
        uuid: 'a111',
        name: 'ASIAN'
      },
      {
        uuid: 'a222',
        name: 'VEGAN'
      }
    ],
    pos_system: 'Square',
    square_id: 1,
    size: 200,
    owner_uuid: 'o111',
    owner: {
      uuid: 'o111',
      name: 'Josh Smith @ Restaurant Inc'
    },
    contact_phone: '938 374822',
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
    uuid: 'abc222',
    name: 'Rekindler',
    type_id: 2,
    tags: [
      {
        uuid: 'a111',
        name: 'VEGAN'
      }
    ],
    pos_system: 'Square',
    square_id: 1,
    size: 150,
    owner_uuid: 'o222',
    owner: {
      uuid: 'o222',
      name: 'Josh Smith @ Dibitery Inc'
    },
    contact_phone: '938 374822',
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
    uuid: 'abc333',
    name: 'Fiora',
    type_id: 2,
    tags: [{
      uuid: 'a111',
      name: 'ASIAN'
    }],
    pos_system: 'Square',
    square_id: 1,
    size: 340,
    owner_uuid: 'o333',
    owner: {
      uuid: 'o333',
      name: 'Josh Smith @ Dibitery Inc'
    },
    contact_phone: '938 374822',
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
    uuid: 'abc444',
    name: 'Trundle',
    type_id: 2,
    tags: [{
      uuid: 'a111',
      name: 'ASIAN'
    }, {
      uuid: 'v111',
      name: 'VEGAN'
    }],
    pos_system: 'Square',
    square_id: 1,
    size: 123,
    owner_uuid: 'o444',
    owner: {
      uuid: 'o444',
      name: 'Josh Smith @ Restaurant Inc'
    },
    contact_phone: '938 374822',
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
    uuid: 'abc555',
    name: 'La fourchette',
    type_id: 2,
    tags: [
      {
        uuid: 't1',
        name: 'VEGAN'
      }
    ],
    pos_system: 'Square',
    square_id: 1,
    size: '',
    owner_uuid: 'o555',
    owner: {
      uuid: 'o555',
      name: 'Josh Smith @ Restaurant Inc'
    },
    contact_phone: '938 374822',
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

export const FIXTURE_STORE = FIXTURE_STORES[0]
