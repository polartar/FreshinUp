import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import AreaForm from './AreaForm'
import { FIXTURE_STORE_AREAS } from '../../../../tests/Javascript/__data__/storeAreas'

export const Default = () => ({
  components: { AreaForm },
  template: `
    <v-container class="white">
      <area-form/>
    </v-container>
  `
})

export const Loading = () => ({
  components: { AreaForm },
  template: `
    <v-container class="white">
      <area-form
        is-loading
      />
    </v-container>
  `
})

export const WithData = () => ({
  components: { AreaForm },
  data () {
    return {
      storeArea: FIXTURE_STORE_AREAS[0]
    }
  },
  methods: {
    onSave (payload) {
      action('save')(payload)
    },
    onCancel () {
      action('cancel')()
    }
  },
  template: `
    <v-container class="white">
      <area-form
        :value="storeArea"
        @input="onSave"
        @cancel="onCancel"/>
    </v-container>
  `
})

storiesOf('FoodFleet|components/fleet-members/AreaForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('WithData', WithData)
