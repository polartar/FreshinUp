import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Confirmed' },
  { id: 4, name: 'Past' },
  { id: 5, name: 'Cancelled' }
]

storiesOf('FoodFleet|events/StatusSelect', module)
  .add('defaults', () => {
    return {
      components: { StatusSelect },
      data () {
        return {
          statuses: statuses
        }
      },
      template: `
          <v-container>
            <status-select
              :options="statuses"
            />
          </v-container>
      `,
      methods: {
        input (val) {
          action('input')(val)
        }
      }
    }
  })
  .add('v-model', () => {
    return {
      components: { StatusSelect },
      data: () => ({
        statuses: statuses,
        val: 1
      }),
      template: `
          <v-container>
            <status-select 
              v-model="val"
              :options="statuses"
              @input="input"
            />
          </v-container>
      `,
      methods: {
        input (val) {
          action('input')(val)
        }
      }
    }
  })
