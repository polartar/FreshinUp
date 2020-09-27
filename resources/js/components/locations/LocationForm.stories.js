import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import LocationForm from './LocationForm'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'
import { FIXTURE_LOCATION_CATEGORIES } from '../../../../tests/Javascript/__data__/locationCategories'
import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'
const mock = new MockAdapter(axios)

mock.onPost('/foodfleet/tmp-media')
  .reply(200, {
    data: '/some-random-file'
  })

export const Default = () => ({
  components: { LocationForm },
  template: `
    <v-container class="white">
      <LocationForm/>
    </v-container>
  `
})

export const Indoor = () => ({
  components: { LocationForm },
  data () {
    return {
      location: { ...FIXTURE_LOCATIONS[0], category_id: 1 },
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

export const Outdoor = () => ({
  components: { LocationForm },
  data () {
    return {
      location: { ...FIXTURE_LOCATIONS[0], category_id: 2 },
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

export const WithDocuments = () => ({
  components: { LocationForm },
  data () {
    return {
      location: { ...FIXTURE_LOCATIONS[0], category_id: 1 },
      categories: FIXTURE_LOCATION_CATEGORIES,
      documents: [
        {
          file: {
            name: 'FLOOR_PLAN_1.PDF',
            size: 123433
          }
        },
        {
          file: {
            name: 'FLOOR_PLAN_2.PDF',
            size: 344833
          }
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
    <v-container class="white">
      <LocationForm
        class="white"
        :value="location"
        :categories="categories"
        :documents="documents"
        @input="onSave"
        @cancel="onCancel"/>
    </v-container>
  `
})

storiesOf('FoodFleet|components/locations/LocationForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Indoor', Indoor)
  .add('Outdoor', Outdoor)
  .add('WithDocuments', WithDocuments)
