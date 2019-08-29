import { storiesOf } from '@storybook/vue'
import TotalSales from './TotalSales.vue'

const paymentTypeTotals = [
  { name: 'VISA', value: 30000 },
  { name: 'MASTERCARD', value: 40000 },
  { name: 'AMEX', value: 35000 },
  { name: 'CASH', value: 20000 }
]

const gross = 200000
const net = 20000

storiesOf('FoodFleet|financials/TotalSales', module)
  .addParameters({
    backgrounds: [
      { name: 'white', value: '#c5dbe3', default: true },
      { name: 'blue', value: '#205a80' }
    ]
  })
  .add('values set', () => {
    return {
      components: { TotalSales },
      data () {
        return {
          paymentTypeTotals: paymentTypeTotals,
          gross: gross,
          net: net
        }
      },
      template: `
          <v-container>
            <v-layout row>
              <v-flex xs4>
                <v-card>
                 <v-card-title class="font-weight-bold subheading">
                    Total Sales
                  </v-card-title>
                  <v-divider></v-divider>
                  <total-sales
                      :gross="gross"
                      :net="net"
                      :payment-type-totals="paymentTypeTotals"
                  />
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
      `
    }
  })
  .add('empty payment type totals array', () => {
    return {
      components: { TotalSales },
      data () {
        return {
          paymentTypeTotals: [],
          gross: gross,
          net: net
        }
      },
      template: `
          <v-container>
            <v-layout row>
              <v-flex xs4>
                <v-card>
                 <v-card-title class="font-weight-bold subheading">
                    Total Sales
                  </v-card-title>
                  <v-divider></v-divider>
                  <total-sales
                      :gross="gross"
                      :net="net"
                      :payment-type-totals="paymentTypeTotals"
                  />
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
      `
    }
  })
