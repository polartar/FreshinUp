import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import LocationList from './LocationList'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'

export const Empty = () => ({
  components: { LocationList },
  data () {
    return {
      items: []
    }
  },
  template: `
    <location-list
      :items="items"
    />
  `
})

export const Loading = () => ({
  components: { LocationList },
  template: `
    <location-list
      is-loading
    />
  `
})

export const Populated = () => ({
  components: { LocationList },
  data () {
    return {
      items: FIXTURE_LOCATIONS
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    }
  },
  template: `
    <location-list
      :items="items"
      @manage="onManage"
      @manage-multiple="onManageMultiple"
    />
  `
})

storiesOf('FoodFleet|components/locations/LocationList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
