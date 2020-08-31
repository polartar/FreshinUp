import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
const FIXTURE_FLEET_MEMBERS_STATUSES = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Revision' },
  { id: 4, name: 'Rejected' },
  { id: 5, name: 'Approved' }
]

storiesOf('FoodFleet|components/fleet-members/StatusSelect', module)
  .add('defaults', () => {
    return {
      components: { StatusSelect },
      data () {
        return {
          statuses: FIXTURE_FLEET_MEMBERS_STATUSES
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
        statuses: FIXTURE_FLEET_MEMBERS_STATUSES,
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
