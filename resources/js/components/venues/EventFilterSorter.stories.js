import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
// Components
import EventFilterSorter from './EventFilterSorter.vue'

export const Default = () => ({
  components: { EventFilterSorter },
  template: `
    <v-container>
      <event-filter-sorter
      />
    </v-container>
  `
})

export const Set = () => ({
  components: { EventFilterSorter },
  data () {
    return {
      sortOptions: [
        { value: '-created_at', text: 'Newest' },
        { value: 'created_at', text: 'Oldest' },
        { value: 'name', text: 'Name (A - Z)' },
        { value: '-name', text: 'Name (Z - A)' }
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
      <event-filter-sorter
        :sort-options="sortOptions"
        @runFilter="onRunFilter"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/venues/EventFilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Set', Set)
