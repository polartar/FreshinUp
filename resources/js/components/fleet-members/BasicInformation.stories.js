import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import BasicInformation from './BasicInformation'
import { FIXTURE_STORE } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_SQUARE_LOCATIONS } from '../../../../tests/Javascript/__data__/companies'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'
import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'

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
  methods: {
    onConnect () {
      action('onConnect')()
    }
  },
  template: `
    <v-container>
      <basic-information
        @connect-square="onConnect"
      />
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

export const Populated = () => ({
  components: { BasicInformation },
  data () {
    return {
      store: { ...FIXTURE_STORE, square_id: FIXTURE_SQUARE_LOCATIONS[0].id },
      types: FIXTURE_STORE_TYPES,
      squareLocations: FIXTURE_SQUARE_LOCATIONS
    }
  },
  template: `
    <v-container>
      <basic-information
        :value="store"
        :types="types"
        :square-locations="squareLocations"
        @input="onSave"
        @connect-square="onConnect"
        @disconnect-square="onDisconnect"
        @cancel="onCancel"
        @delete="onDelete"/>
    </v-container>
  `,
  methods: {
    onConnect () {
      action('onConnect')()
    },
    onDisconnect () {
      action('onDisconnect')()
    },
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
  .add('Populated', Populated)
