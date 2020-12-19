import PaymentForm from './PaymentForm'
import { action } from '@storybook/addon-actions'
import { FIXTURE_PAYMENTS } from '../../../../tests/Javascript/__data__/payments'
import { storiesOf } from '@storybook/vue'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'

export const Default = () => ({
  components: { PaymentForm },
  template: `
    <v-container class="white">
        <PaymentForm/>
    </v-container>
  `
})

export const Loading = () => ({
  components: { PaymentForm },
  template: `
      <v-container class="white">
        <PaymentForm
          is-loading
        />
      </v-container>
  `
})

export const WithData = () => ({
  components: { PaymentForm },
  data () {
    return {
      item: FIXTURE_PAYMENTS[1],
      events: FIXTURE_EVENTS
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
      <PaymentForm
        :value="item"
        :events="events"
        @input="onSave"
        @cancel="onCancel"/>
    </v-container>
  `
})

storiesOf('FoodFleet|components/payments/PaymentForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('WithData', WithData)
