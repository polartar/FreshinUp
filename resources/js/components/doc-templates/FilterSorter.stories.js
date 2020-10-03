import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FilterSorter from './FilterSorter.vue'
import { FIXTURE_DOCUMENT_TEMPLATE_STATUSES } from '../../../../tests/Javascript/__data__/documentTemplateStatuses'

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
      statuses: FIXTURE_DOCUMENT_TEMPLATE_STATUSES,
      filters: {
        status_id: [1, 2],
        title: 'Some title'
      }
    }
  },
  methods: {
    filterItems (params) {
      action('runFilter')(params)
    }
  },
  template: `
    <v-container>
      <filter-sorter
        :filters="filters"
        :statuses="statuses"
        @runFilter="filterItems"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/doc-templates/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Expanded', Expanded)
  .add('Populated', Populated)
