import Events from './Events'
import { storiesOf } from '@storybook/vue'

export const Default = () => ({
  components: { Events },
  template: `
      <v-container>
        <Events />
      </v-container>
    `
})

export const WithData = () => ({
  components: { Events },
  data () {
    return {
      events: ['Event will populate once your restaurant is assigned.']
    }
  },
  template: `
      <v-container>
        <Events :events="events" />
      </v-container>
    `
})

storiesOf('FoodFleet|fleet-member/Events', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populate', WithData)
