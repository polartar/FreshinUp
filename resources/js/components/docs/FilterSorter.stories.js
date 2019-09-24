import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FilterSorter from './FilterSorter.vue'

let types = [
  { value: 1, text: 'From Template' },
  { value: 2, text: 'Downloadable' }
]

let statuses = [
  { value: 1, text: 'Pending' },
  { value: 2, text: 'Approved' },
  { value: 3, text: 'Rejected' },
  { value: 4, text: 'Expiring' },
  { value: 5, text: 'Expired' }
]

let sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
]

storiesOf('FoodFleet|doc/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { FilterSorter },
    data () {
      return {
        types: types,
        statuses: statuses,
        sortables: sortables
      }
    },
    methods: {
      filterDocs (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <filter-sorter
          :types="types"
          :statuses="statuses"
          :sortables="sortables"
          @runFilter="filterDocs"
        />
      </v-container>
    `
  }))
