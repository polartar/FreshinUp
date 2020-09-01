import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StatusSelect from './StatusSelect'
import { FIXTURE_FLEET_MEMBERS_STATUSES } from 'tests/__data__/fleet-members'

export const ReadOnly = () => {
  return {
    components: { StatusSelect },
    data: () => ({
      statuses: FIXTURE_FLEET_MEMBERS_STATUSES
    }),
    template: `
      <v-container>
        <status-select
          :options="statuses"
        />
      </v-container>
    `
  }
}

export const Selected = () => {
  return {
    components: { StatusSelect },
    data: () => ({
      statuses: FIXTURE_FLEET_MEMBERS_STATUSES,
      value: 1
    }),
    template: `
      <v-container>
        <status-select
          :value="value"
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

storiesOf('FoodFleet|components/fleet-members/StatusSelect', module)
  .add('ReadOnly', ReadOnly)
  .add('Selected', Selected)
