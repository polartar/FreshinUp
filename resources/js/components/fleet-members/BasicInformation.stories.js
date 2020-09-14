import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import BasicInformation from './BasicInformation'

import { FIXTURE_STORE } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'

import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'

const LOCATIONS = ['Square']
const mock = new MockAdapter(axios)

mock
  .onGet(/.*users.*/)
  .reply(200, {
    data: [
      { uuid: 'o111', name: 'John Smith' },
      { uuid: 'o222', name: 'Bob Loblaw' },
      { uuid: 'o333', name: 'Mario Brother' },
      { uuid: 'o444', name: 'Jeanette Rempel' },
      { uuid: 'o555', name: 'Miller Ortiz' }
    ]
  })

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
      locations: LOCATIONS,
      squareLocations: [
        {
          square_id: 1,
          name: 'Business One'
        },
        {
          square_id: 2,
          name: 'Business Two'
        }
      ]
    }
  },
  template: `
    <v-container>
      <basic-information
        :value="store"
        :locations="locations"
        :types="types"
        :square-locations="squareLocations"
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

storiesOf('FoodFleet|components/fleet-members/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('WithData', WithData)
