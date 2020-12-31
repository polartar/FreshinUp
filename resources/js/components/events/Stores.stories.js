import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import Stores from './Stores.vue'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'

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

const types = [
  { uuid: 1, name: 'Mobil' },
  { uuid: 2, name: 'Car' },
  { uuid: 3, name: 'Architecto' }
]

export const Default = () => ({
  components: { Stores },
  template: `
      <v-container>
        <stores
        />
      </v-container>
    `
})

export const Populated = () => ({
  components: { Stores },
  data () {
    return {
      types: types,
      statuses: FIXTURE_STORE_STATUSES,
      stores: FIXTURE_STORES
    }
  },
  methods: {
    runFilter (params) {
      action('filter-stores')(params)
    },
    manage (act, params) {
      action('manage')(act, params)
    }
  },
  template: `
    <v-container>
      <stores
        :types="types"
        :statuses="statuses"
        :stores="stores"
        @filter-stores="runFilter"
        @manage-view-details="manage"
        @manage-unassign="manage"
        @manage-multiple-unassign="manage"
      />
    </v-container>
    `
})

storiesOf('FoodFleet|components/event/Stores', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
