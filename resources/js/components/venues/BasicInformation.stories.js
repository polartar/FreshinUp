import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import BasicInformation from './BasicInformation'
import { FIXTURE_VENUE } from '../../../../tests/Javascript/__data__/venues'

export const Default = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <basic-information/>
    </v-container>
  `
})

export const Loading = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <basic-information loading/>
    </v-container>
  `
})

export const WithData = () => ({
  components: { BasicInformation },
  data () {
    return {
      venue: FIXTURE_VENUE
    }
  },
  template: `
    <v-container>
      <basic-information
        :value="venue"
        @input="onSave"
        @cancel="onCancel"
        @delete="onDelete"/>
    </v-container>
  `,
  methods: {
    onSave (payload) {
      action('onSave')(payload)
    },

    onCancel () {
      action('onCancel')()
    },

    onDelete (payload) {
      action('onDelete')(payload)
    }
  }
})

storiesOf('FoodFleet|components/venues/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('With data', WithData)
