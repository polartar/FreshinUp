import { storiesOf } from '@storybook/vue'
import StoreStatusStat from './StoreStatusStat'
import { FIXTURE_STORE_STATUS_STATS } from '../../../../tests/Javascript/__data__/storeStatusStats'
import { select } from '@storybook/addon-knobs'
export const Default = () => {
  return {
    components: { StoreStatusStat },
    template: `
      <v-container>
        <store-status-stat
        />
      </v-container>
    `
  }
}

const item = FIXTURE_STORE_STATUS_STATS[1]
export const Populated = () => {
  return {
    components: { StoreStatusStat },
    data: () => ({
      label: item.label,
      value: item.value,
      color: select('Color', FIXTURE_STORE_STATUS_STATS.map(stat => stat.color), item.color),
    }),
    template: `
      <v-container>
        <store-status-stat
          :label="label"
          :value="value"
          :color="color"
        />
      </v-container>
    `,
  }
}

storiesOf('FoodFleet|components/fleet-members/StoreStatusStat', module)
  .add('Default', Default)
  .add('Populated', Populated)
