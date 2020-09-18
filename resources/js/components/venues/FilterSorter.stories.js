import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FilterSorter from './FilterSorter.vue'

let statuses = [
  { id: 1, name: 'Pending', color: 'warning' },
  { id: 2, name: 'Approved', color: 'success' },
  { id: 3, name: 'Rejected', color: 'error' },
  { id: 4, name: 'Expiring', color: 'warning' },
  { id: 5, name: 'Expired', color: 'accent' }
]

storiesOf('FoodFleet|components/venues/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { FilterSorter },
    methods: {
      filterVenues (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          @runFilter="filterVenues"
        />
      </v-container>
    `
  }))
  .add('with statuses', () => ({
    components: { FilterSorter },
    data () {
      return {
        statuses: statuses,
        filters: {
          status_id: null,
          owner_uuid: null
        }
      }
    },
    methods: {
      filterVenues (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          :filters="filters"
          :statuses="statuses"
          @runFilter="filterVenues"
        />
      </v-container>
    `
  }))
