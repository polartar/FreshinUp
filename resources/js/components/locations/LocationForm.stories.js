import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import LocationForm from './LocationForm'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'
import { FIXTURE_LOCATION_CATEGORIES } from '../../../../tests/Javascript/__data__/locationCategories'

export const Default = () => ({
  components: { LocationForm },
  template: `
    <v-container class="white">
      <LocationForm/>
    </v-container>
  `
})

export const WithData = () => ({
  components: { LocationForm },
  data () {
    return {
      location: FIXTURE_LOCATIONS[0],
      categories: FIXTURE_LOCATION_CATEGORIES
    }
  },
  methods: {
    onSave (payload) {
      action('onSave')(payload)
    },
    onCancel () {
      action('onCancel')()
    }
  },
  template: `
    <v-container class="white">
      <LocationForm
        class="white"
        :value="location"
        :categories="categories"
        @input="onSave"
        @cancel="onCancel"/>
    </v-container>
  `
})

storiesOf('FoodFleet|components/locations/LocationsForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('With data', WithData)
