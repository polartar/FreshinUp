import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
// Components
import FilterSorter from './FilterSorter.vue'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'

const types = [
  { value: 1, text: 'From Template' },
  { value: 2, text: 'Downloadable' }
]

const sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
]

export const Default = () => ({
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
})

export const WithTypes = () => ({
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
})

export const WithStatuses = () => ({
  components: { FilterSorter },
  data () {
    return {
      statuses: FIXTURE_DOCUMENT_STATUSES
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
})

export const WithSortables = () => ({
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
})

storiesOf('FoodFleet|doc/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('WithTypes', WithTypes)
  .add('WithStatuses', WithStatuses)
  .add('WithSortables', WithSortables)
