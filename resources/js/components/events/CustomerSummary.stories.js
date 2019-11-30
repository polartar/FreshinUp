import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import CustomerSummary from './CustomerSummary'

storiesOf('FoodFleet|events/CustomerSummary', module)
  .add(
    'data set',
    () => ({
      components: { CustomerSummary },
      methods: {
        onButtonClick () {
          action('onButtonClick')('button clicked')
        }
      },
      data () {
        return {
          customer: {
            owner: 'Joan Smith',
            signed_contracts: 37,
            phone: '938 374822',
            email: 'joan.simth@gmail.com'
          }
        }
      },
      template: `
        <v-container fluid>
          <v-layout>
            <v-flex md4>
              <customer-summary
                :customer="customer"
                @onButtonClick="onButtonClick"
              />
            </v-flex>
          </v-layout>
        </v-container>
      `
    })
  )
