import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import AreaForm from './AreaForm'
import { FIXTURE_STORE } from '../../../../tests/Javascript/__data__/stores'

export const Default = () => ({
  components: { AreaForm },
  methods: {
    onSave () {
      action('save')()
    },
    onCancel () {
      action('cancel')()
    }
  },
  template: `
    <v-container>
      <area-form
        @input="onSave"
        @cancel="onCancel"/>
    </v-container>
  `
})

export const WithData = () => ({
  components: { AreaForm },
  data () {
    return {
      store: FIXTURE_STORE
    }
  },
  methods: {
    onSave () {
      action('save')()
    },
    onCancel () {
      action('cancel')()
    }
  },
  template: `
    <v-container>
      <area-form
        :value="store"
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
  .add('WithData', WithData)
