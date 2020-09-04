import AreasOfOperation from './AreasOfOperation'
import { storiesOf } from '@storybook/vue'

export const Default = () => ({
  components: { AreasOfOperation },
  template: `
      <v-container>
        <AreasOfOperation />
      </v-container>
    `
})

storiesOf('FoodFleet|fleet-member/AreasOfOperation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
