import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

export const Default = () => {
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
}

export const Vmodel = () => {
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
}
storiesOf('FoodFleet|components/events/StatusSelect', module)
  .add('Default', Default)
  .add('Vmodel', Vmodel)
