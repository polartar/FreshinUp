import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import Stores from './Stores.vue'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'

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

storiesOf('FoodFleet|components/event/Stores', module)
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
        stores: FIXTURE_STORES
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
        :event="event"
        @filter-stores="runFilter"
        @manage-view-details="viewDetails"
        @manage-unassign="unassign"
        @manage-multiple-unassign="multipleUnassign"
      />
    </v-container>
    `
  }))
