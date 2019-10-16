import { storiesOf } from '@storybook/vue'

// Components
import EventCalendar from './EventCalendar.vue'

storiesOf('FoodFleet|event/EventCalendar', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('events is empty', () => ({
    components: { EventCalendar },
    data () {
      return {
        events: [],
        type: 'month',
        start: '2019-01-01',
        end: '2019-01-06'
      }
    },
    template: `
      <event-calendar
        :events="events"
        :type="type"
        :start="start"
        :end="end"
      />
    `
  }))
