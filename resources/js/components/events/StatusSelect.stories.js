import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
const FIXTURE_EVENT_STATUSES = [
  { id: 1, name: 'Draft', color: 'grey' },
  { id: 2, name: 'FF Initial Review', color: 'warning' },
  { id: 3, name: 'Customer Agreement', color: 'warning' },
  { id: 4, name: 'Fleet Member Selection', color: 'warning' },
  { id: 5, name: 'Customer Review', color: 'warning' },
  { id: 6, name: 'Fleet Member Contracts', color: 'warning' },
  { id: 7, name: 'Confirmed', color: 'success' },
  { id: 8, name: 'Cancelled', color: 'error' },
  { id: 9, name: 'Past', color: 'grey' }
]

storiesOf('FoodFleet|events/StatusSelect', module)
  .add('defaults', () => {
    return {
      components: { StatusSelect },
      data () {
        return {
          statuses: FIXTURE_EVENT_STATUSES
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
        statuses: FIXTURE_EVENT_STATUSES,
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
