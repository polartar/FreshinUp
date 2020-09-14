import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import AreaForm from './AreaForm'

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
      <area-form @input="onSave" @cancel="onCancel" />
    </v-container>
  `
})

export const WithData = () => ({
  components: { AreaForm },
  data () {
    return {
      store: {
        id: 123,
        name: '613 East Broadway, Glendale CA',
        radius: 50,
        state: 'Detroit',
        store_uuid: 'abc123'
      }
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
      <area-form :value="store" @input="onSave" @cancel="onCancel"/>
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
