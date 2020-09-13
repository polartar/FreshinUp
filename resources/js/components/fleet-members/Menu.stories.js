import Menu from './Menu'
import { storiesOf } from '@storybook/vue'

export const Default = () => ({
  components: { Menu },
  template: `
      <v-container>
        <Menu />
      </v-container>
    `
})

storiesOf('FoodFleet|components/fleet-members/Menu', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
