import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'

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
