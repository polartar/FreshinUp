import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'

let statuses = FIXTURE_DOCUMENT_STATUSES.map(i => ({
  id: i.value,
  name: i.text,
  color: i.color
}))

storiesOf('FoodFleet|doc/StatusSelect', module)
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
