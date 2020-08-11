import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'FF Initial Review' },
  { id: 3, name: 'Customer Agreement' },
  { id: 4, name: ' Fleet Member Selection' },
  { id: 5, name: 'Customer Review' },
  { id: 6, name: 'Fleet Member Contracts' },
  { id: 7, name: 'Confirmed' },
  { id: 8, name: 'Cancelled' },
  { id: 9, name: 'Past' }
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
