import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import Stores from './Stores.vue'

const mock = new MockAdapter(axios)
mock.onGet('foodfleet/store-tags').reply(200, {
  data: [
    { uuid: 1, name: 'aperiam' },
    { uuid: 2, name: 'iure' },
    { uuid: 3, name: 'dicta' },
    { uuid: 4, name: 'voluptate' }
  ]
})

mock.onGet('foodfleet/locations').reply(200, {
  data: [
    { uuid: 1, name: 'South Abagail' },
    { uuid: 2, name: 'Lindseymouth' },
    { uuid: 3, name: 'Fredrickstad' },
    { uuid: 4, name: 'Zanderstad' }
  ]
})

mock.onGet('foodfleet/owners').reply(200, {
  data: [
    { id: 1, name: 'Level1 User' },
    { id: 2, name: 'Level2 User' },
    { id: 3, name: 'Level3 User' },
    { id: 4, name: 'Level4 User' }
  ]
})

let types = [
  { uuid: 1, name: 'Mobil' },
  { uuid: 2, name: 'Car' },
  { uuid: 3, name: 'Architecto' }
]

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Confirmed' },
  { id: 4, name: 'Declined' }
]

let stores = [
  {
    uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
    name: 'Burger Babes',
    status: 1,
    type: {
      id: 1,
      name: 'Mobil'
    },
    store_tags: [
      {
        uuid: '1',
        name: 'minus'
      },
      {
        uuid: '2',
        name: 'hic'
      }
    ],
    owner: {
      id: 1,
      name: 'Level1 User',
      company: 'Laravel'
    },
    location: {
      id: 1,
      uuid: 'c4fd0928-b7eb-43ee-871e-e63bfbd0ae7a',
      name: 'Port Gerald'
    }
  },
  {
    uuid: 'c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5',
    name: 'Holy Canoli',
    status: 2,
    type: {
      id: 1,
      name: 'Mobil'
    },
    store_tags: [
      {
        uuid: '1',
        name: 'minus'
      },
      {
        uuid: '2',
        name: 'sit'
      },
      {
        uuid: '3',
        name: 'hicsdfsdf'
      }
    ],
    owner: {
      id: 2,
      name: 'Level2 User',
      company: 'Bogisich LLC'
    },
    location: {
      id: 2,
      uuid: '00aabd70-aa77-42de-87fd-96449bbd5439',
      name: 'Carminestad'
    }
  },
  {
    uuid: '790aba97-1eb6-4630-82d9-7bd561256c67',
    name: 'Da Lobsta',
    status: 3,
    type: {
      id: 1,
      name: 'Mobil'
    },
    store_tags: [
      {
        uuid: '1',
        name: 'minus'
      },
      {
        uuid: '2',
        name: 'hic'
      },
      {
        uuid: '3',
        name: 'acsfdd'
      },
      {
        uuid: '4',
        name: 'fsdf'
      }
    ],
    owner: {
      id: 3,
      name: 'Level3 User',
      company: 'Rempel LLC'
    },
    location: {
      id: 2,
      uuid: 'dfb4bd02-c298-4dd4-ab49-2c3f156f2894',
      name: 'New Charlenebury'
    }
  }
]

storiesOf('FoodFleet|event/Stores', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { Stores },
    data () {
      return {
        types: types,
        statuses: statuses,
        stores: []
      }
    },
    methods: {
      runFilter (params) {
        action('filter-stores')(params)
      },
      viewDetails (params) {
        action('manage-view-details')(params)
      },
      unassign (params) {
        action('manage-unassign')(params)
      },
      multipleUnassign (params) {
        action('manage-multiple-unassign')(params)
      }
    },
    template: `
      <v-container>
        <stores
          :types="types"
          :statuses="statuses"
          :stores="stores"
          @filter-stores="runFilter"
          @manage-view-details="viewDetails"
          @manage-unassign="unassign"
          @manage-multiple-unassign="multipleUnassign"
        />
      </v-container>
    `
  }))
  .add('stores', () => ({
    components: { Stores },
    data () {
      return {
        types: types,
        statuses: statuses,
        stores: stores
      }
    },
    methods: {
      runFilter (params) {
        action('filter-stores')(params)
      },
      viewDetails (params) {
        action('manage-view-details')(params)
      },
      unassign (params) {
        action('manage-unassign')(params)
      },
      multipleUnassign (params) {
        action('manage-multiple-unassign')(params)
      }
    },
    template: `
    <v-container>
      <stores
        :types="types"
        :statuses="statuses"
        :stores="stores"
        @filter-stores="runFilter"
        @manage-view-details="viewDetails"
        @manage-unassign="unassign"
        @manage-multiple-unassign="multipleUnassign"
      />
    </v-container>
    `
  }))
