import { storiesOf } from '@storybook/vue'
// import { action } from '@storybook/addon-actions'

import Payments from './Payments'
import { FIXTURE_PAYMENTS } from '../../../../tests/Javascript/__data__/Payments'

export const Default = () => ({
  components: { Payments },
  template: `
      <v-container>
        <payments />
      </v-container>
    `
})

export const WithData = () => ({
  components: { Payments },
  data () {
    return {
      paymentList: FIXTURE_PAYMENTS
    }
  },
  template: `
      <v-container>
        <payments :payments="paymentList"/>
      </v-container>
    `,
  methods: { }
})

storiesOf('FoodFleet|fleet-member/Payments', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('With data', WithData)
