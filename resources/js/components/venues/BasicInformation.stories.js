import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import BasicInformation from './BasicInformation'
import { FIXTURE_VENUE } from '../../../../tests/Javascript/__data__/venues'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'
import { FIXTURE_MAPBOX_SEARCH_RESULT } from '../../../../tests/Javascript/__data__/mapbox'

const mock = new MockAdapter(axios)
mock.onGet(/.*users.*/).reply(200, {
  data: [
    { uuid: 1, name: 'user 1' },
    { uuid: 2, name: 'user 2' },
    { uuid: 3, name: 'user 3' },
    { uuid: 4, name: 'user 4' }
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

export const AddressesLoading = () => ({
  components: { BasicInformation },
  template: `
    <v-container>
      <basic-information addresses-loading/>
    </v-container>
  `
})

export const Populated = () => ({
  components: { BasicInformation },
  data () {
    return {
      venue: FIXTURE_VENUE,
      addresses: FIXTURE_MAPBOX_SEARCH_RESULT.features,
      accessToken: 'pk.eyJ1IjoiYmNkYnVkZHkiLCJhIjoiY2toM3luOTlrMDE2dDJzazBzN2NqaGZobCJ9.azAiM_hZuTI3Ew9Q1HpFtg'// process.env.MAPBOX_ACCESS_TOKEN
    }
  },
  template: `
    <v-container>
      <basic-information
        :value="venue"
        :addresses="addresses"
        :mapbox-access-token="accessToken"
        @search-places="onSearchPlaces"
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
    },
    onSearchPlaces (payload) {
      action('onSearchPlaces')(payload)
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
  .add('AddressesLoading', AddressesLoading)
  .add('Populated', Populated)
