import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import Locations from './Locations'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'
import { FIXTURE_LOCATION_CATEGORIES } from '../../../../tests/Javascript/__data__/locationCategories'

export const Empty = () => ({
  components: { Locations },
  data () {
    return {
      items: []
    }
  },
  template: `
    <locations
      :items="items"
    />
  `
})

export const Loading = () => ({
  components: { Locations },
  template: `
    <locations
      is-loading
    />
  `
})

export const Populated = () => ({
  components: { Locations },
  data () {
    return {
      items: FIXTURE_LOCATIONS,
      categories: FIXTURE_LOCATION_CATEGORIES
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    },
    onSubmit (payload) {
      action('onSubmit')(payload)
    }
  },
  template: `
    <locations
      :items="items"
      :categories="categories"
      @new-location="onSubmit"
      @manage="onManage"
      @manage-multiple="onManageMultiple"
    />
  `
})

storiesOf('FoodFleet|components/locations/Locations', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
