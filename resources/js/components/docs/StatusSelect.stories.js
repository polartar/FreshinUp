import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'

let statuses = FIXTURE_DOCUMENT_STATUSES

export const Default = () => {
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
}

export const Vmodel = () => {
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
}

storiesOf('FoodFleet|components/docs/StatusSelect', module)
  .add('Default', Default)
  .add('v-model', Vmodel)
