import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import FilterSorter from './FilterSorter.vue'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'
import { FIXTURE_STORE_TAGS } from '../../../../tests/Javascript/__data__/storeTags'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import { SORTABLES } from '../../store/modules/stores'

const mock = new MockAdapter(axios)
mock.onGet(/.*\/store-tags.*/).reply(200, {
  data: FIXTURE_STORE_TAGS
})

mock.onGet(/\/users.*/).reply(200, {
  data: FIXTURE_USERS
})

export const Default = () => ({
  components: { FilterSorter },
  template: `
      <v-container class="grey">
        <filter-sorter />
      </v-container>
    `
})

export const Set = () => ({
  components: { FilterSorter },
  data () {
    return {
      statuses: FIXTURE_STORE_STATUSES,
      sortables: SORTABLES
    }
  },
  methods: {
    filterStores (params) {
      action('Run')(params)
    }
  },
  template: `
      <v-container class="grey">
        <filter-sorter
          :statuses="statuses"
          :sortables="sortables"
          @runFilter="filterStores"
        />
      </v-container>
    `
})

export const Populated = () => ({
  components: { FilterSorter },
  data () {
    return {
      statuses: FIXTURE_STORE_STATUSES,
      sortables: SORTABLES,
      filters: {
        status_id: [1, 2],
        tag: FIXTURE_STORE_TAGS.slice(0, 2),
        state_of_incorporation: 'Atlanta',
        owner_uuid: FIXTURE_USERS[0].uuid
      }
    }
  },
  methods: {
    filterStores (params) {
      action('Run')(params)
    }
  },
  template: `
      <v-container class="grey">
        <filter-sorter
          :filters="filters"
          :statuses="statuses"
          :sortables="sortables"
          @runFilter="filterStores"
        />
      </v-container>
    `
})

storiesOf('FoodFleet|components/fleet-members/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Set', Set)
  .add('Populated', Populated)
