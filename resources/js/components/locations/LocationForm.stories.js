import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import LocationForm from './LocationForm'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'

export const Empty = () => ({
  components: { LocationForm },
  template: `
    <LocationForm />
  `
})

export const WithData = () => ({
  components: { LocationForm },
  data () {
    return {
      location: FIXTURE_LOCATIONS[0],
      categories: [
        {
          id: 2,
          name: 'Outdoor'
        },
        {
          id: 1,
          name: 'Indoor'
        }
      ]
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
    <LocationForm :value="location" :categories="categories" @input="onSave" @cancel="onCancel"/>
  `
})

storiesOf('FoodFleet|components/locations/LocationsForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('With data', WithData)
