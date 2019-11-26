import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import EventFilter from './EventFilter.vue'

storiesOf('FoodFleet|event/EventFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { EventFilter },
    data () {
      return {
        managers: [],
        customers: []
      }
    },
    template: `
      <event-filter
        :managers="managers"
        :customers="customers"
      />
    `
  }))
