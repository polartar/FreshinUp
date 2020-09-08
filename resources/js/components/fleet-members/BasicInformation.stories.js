import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import BasicInformation from './BasicInformation'

import { FIXTURE_STORE } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'

const LOCATIONS = ['square']

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
      store: FIXTURE_STORE,
      types: FIXTURE_STORE_TYPES,
      locations: LOCATIONS
    }
  },
  template: `
    <v-container>
      <basic-information
        :store="store"
        :locations="locations"
        :types="types"
        @save="onSave"
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

storiesOf('FoodFleet|components/fleet-members/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('WithData', WithData)
