import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import AreaForm from './AreaForm'

export const Default = () => ({
  components: { AreaForm },
  template: `
    <v-container>
      <area-form />
    </v-container>
  `
})

export const WithData = () => ({
  components: { AreaForm },
  data () {
    return {
      store: {}
    }
  },
  methods: {
    onSave () {
      action('save')()
    }
  },
  template: `
    <v-container>
      <area-form :value="store" @input="onSave"/>
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
