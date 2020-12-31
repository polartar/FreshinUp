import AddStoreFilterSorter from './AddStoreFilterSorter'
import { storiesOf } from '@storybook/vue'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'
import { action } from '@storybook/addon-actions'
import { FIXTURE_STORE_TAGS } from '../../../../tests/Javascript/__data__/storeTags'

export const FIXTURE_ADD_STORE_FILTERS = {
  name: 'California',
  state_of_incorporation: 'Magnolia',
  type_id: 1,
  tags: FIXTURE_STORE_TAGS
}

export const Default = () => ({
  components: {
    FilterSorter: AddStoreFilterSorter
  },
  template: `
    <filter-sorter/>
  `
})

export const Populated = () => ({
  components: {
    FilterSorter: AddStoreFilterSorter
  },
  data () {
    return {
      value: FIXTURE_ADD_STORE_FILTERS,
      types: FIXTURE_STORE_TYPES
    }
  },
  methods: {
    runFilters (payload) {
      action('run-filter')(payload)
    },
  },
  template: `
    <filter-sorter
      :value="value"
      :types="types"
      @run-filter="runFilters"
    />
  `
})

storiesOf('FoodFleet|components/fleet-members/AddStoreFilterSorter', module)
  .add('Default', Default)
  .add('Populated', Populated)
