import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import Stores from './Stores.vue'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import { FIXTURE_STORE_TAGS } from '../../../../tests/Javascript/__data__/storeTags'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'

const mock = new MockAdapter(axios)
mock.onGet('foodfleet/store-tags').reply(200, {
  data: FIXTURE_STORE_TAGS
})

mock.onGet('foodfleet/locations').reply(200, {
  data: FIXTURE_LOCATIONS
})

mock.onGet('foodfleet/owners').reply(200, {
  data: FIXTURE_USERS
})

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
      types: FIXTURE_STORE_TYPES,
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
        @manage-view="manage"
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
