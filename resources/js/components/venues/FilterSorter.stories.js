import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FilterSorter from './FilterSorter.vue'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

export const Default = () => ({
  components: { FilterSorter },
  template: `
    <v-container>
      <filter-sorter
      />
    </v-container>
  `
})

export const Populated = () => ({
  components: { FilterSorter },
  data () {
    return {
      statuses: FIXTURE_EVENT_STATUSES,
      sortOptions: [
        'status',
        'created_at'
      ]
    }
  },
  methods: {
    onRunFilter (params) {
      action('runFilter')(params)
    }
  },
  template: `
    <v-container>
      <filter-sorter
        :statuses="statuses"
        :sort-options="sortOptions"
        @runFilter="onRunFilter"
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
  .add('Populated', Populated)
