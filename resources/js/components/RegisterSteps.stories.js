import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import RegisterSteps from './RegisterSteps'

export const Default = () => ({
  components: { RegisterSteps },
  template: `
      <v-container>
        <register-steps/>
      </v-container>
    `
})

export const Loading = () => ({
  components: { RegisterSteps },
  template: `
      <v-container>
        <register-steps is-loading/>
      </v-container>
    `
})

export const Populated = () => ({
  components: { RegisterSteps },
  data () {
    return {
      data: {
        first_name: 'John',
        last_name: 'Doe',
        email: 'john.doe@freshinup.com',
        phone_number: '645446545',
        password: '0000',
        password_confirmation: '0000'
      }
    }
  },
  methods: {
    onInput (payload) {
      action('onInput')(payload)
    },
    onStepChange (step) {
      action('onStepChange')(step)
    },
    onClose (step) {
      action('onClose')(step)
    }
  },
  template: `
      <v-container>
        <register-steps
          :current="1"
          :value="data"
          @input="onInput"
          @step-change="onStepChange"
          @close="onClose"
        >
        </register-steps>
      </v-container>
    `
})

storiesOf('FoodFleet|components/RegisterSteps', module)
  .add('Default', Default)
  .add('Loading', Loading)
  .add('Populated', Populated)
