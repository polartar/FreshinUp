import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StoreServiceSummary from './StoreServiceSummary'

storiesOf('FoodFleet|events/StoreServiceSummary', module)
  .add(
    'data set',
    () => ({
      components: { StoreServiceSummary },
      methods: {
        viewContract () {
          action('viewContract')()
        },
        save (value) {
          action('save')(value)
        }
      },
      data () {
        return {
          service: {
            total_services: 300,
            total_cost: '$24,000.00',
            commission_rate: 30,
            commission_type: 1
          }
        }
      },
      template: `   
        <store-service-summary
          @viewContract="viewContract"
          @save="save"
          :service="service"
        />
      `
    })
  )
