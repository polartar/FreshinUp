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
    methods: {
      filterDocs (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <filter-sorter
          @runFilter="filterDocs"
        />
      </v-container>
    `
  }))
  .add('with types', () => ({
    components: { FilterSorter },
    data () {
      return {
        types: types
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
          @runFilter="filterDocs"
        />
      </v-container>
    `
  }))
  .add('with statuses', () => ({
    components: { FilterSorter },
    data () {
      return {
        statuses: statuses
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
          :statuses="statuses"
          @runFilter="filterDocs"
        />
      </v-container>
    `
  }))
  .add('with sortables', () => ({
    components: { FilterSorter },
    data () {
      return {
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
          :sortables="sortables"
          @runFilter="filterDocs"
        />
      </v-container>
    `
  }))
