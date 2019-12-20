import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import FinancialSummary from './FinancialSummary'

storiesOf('FoodFleet|events/FinancialSummary', module)
  .add(
    'data set',
    () => ({
      components: { FinancialSummary },
      methods: {
        onButtonClick () {
          action('onButtonClick')('button clicked')
        }
      },
      data () {
        return {
          financial: {
            total_fleet: 27,
            total_cost: '$24,000.00',
            amount_due: '$824,000.00'
          }
        }
      },
      template: `
        <v-container fluid>
          <v-layout>
            <v-flex md4>
              <financial-summary
                :financial="financial"
                @onButtonClick="onButtonClick"
              />
            </v-flex>
          </v-layout>
        </v-container>
      `
    })
  )
