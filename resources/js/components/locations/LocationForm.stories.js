import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import LocationForm from './LocationForm'
import { FIXTURE_LOCATION } from '../../../../tests/Javascript/__data__/locations'
import { CATEGORIES } from '../../../../tests/Javascript/__data__/locationCategories'

export const Default = () => ({
  components: { LocationForm },
  template: `
    <v-container>
      <location-form/>
    </v-container>
  `
})

export const WithData = () => ({
  components: { LocationForm },
  data () {
    return {
      location: FIXTURE_LOCATION,
      categories: CATEGORIES
    }
  },
  template: `
    <v-container>
      <location-form
        :value="location"
        :categories="categories"
        @input="onSave"
        @cancel="onCancel"
      />
    </v-container>
  `,
  methods: {
    onSave (payload) {
      action('onSave')(payload)
    },
    onCancel () {
      action('onCancel')()
    }
  }
})

storiesOf('FoodFleet|components/locations/LocationForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('WithData', WithData)
