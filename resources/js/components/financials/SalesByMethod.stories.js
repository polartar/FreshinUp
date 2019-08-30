import { storiesOf } from '@storybook/vue'

// Components
import SalesByMethod from './SalesByMethod.vue'

const paymentTypeTotals = [
  { name: 'VISA', value: 30000 },
  { name: 'MASTERCARD', value: 40000 },
  { name: 'AMEX', value: 35000 },
  { name: 'CASH', value: 20000 }
]

storiesOf('FoodFleet|charts/SalesByMethod', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('with chart data set', () => {
    return {
      components: { SalesByMethod },
      data () {
        return {
          paymentTypeTotals: paymentTypeTotals
        }
      },
      template: `
          <v-container>
          <v-layout row>
            <v-flex sm6>
              <v-card>
                <v-card-title class="font-weight-bold subheading">
                    Sales By Method
                </v-card-title>
                <v-divider></v-divider>
                <sales-by-method
                :payment-type-totals="paymentTypeTotals"
              />
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      `
    }
  })
