import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FilterSorter from './FilterSorter.vue'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'
import { FIXTURE_VENUE_STATUSES } from '../../../../tests/Javascript/__data__/venueStatuses'

const mock = new MockAdapter(axios)
mock.onGet(/\/users.*/).reply(200, {
  data: FIXTURE_USERS
})

export const Default = () => ({
  components: { FilterSorter },
  template: `
    <v-container>
      <filter-sorter
      />
    </v-container>
  `
})

export const Expanded = () => ({
  components: { FilterSorter },
  template: `
    <v-container>
      <filter-sorter
        expanded
      />
    </v-container>
  `
})

export const Populated = () => ({
  components: { FilterSorter },
  data () {
    return {
      statuses: FIXTURE_VENUE_STATUSES,
      filters: {
        status_id: [1, 2],
        owner_uuid: FIXTURE_USERS[0].uuid
      }
    }
  },
  methods: {
    filterVenues (params) {
      action('runFilter')(params)
    }
  },
  template: `
    <v-container>
      <filter-sorter
        :filters="filters"
        :statuses="statuses"
        @runFilter="filterVenues"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/venues/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Expanded', Expanded)
  .add('Populated', Populated)
