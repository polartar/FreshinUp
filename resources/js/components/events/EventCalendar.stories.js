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
        yearRange: [2010, 2020],
        year: 2019,
        month: 12,
        day: 31
      }
    },
    template: `
      <event-calendar
        :events="events"
        :type="type"
        :yearRange="yearRange"
        :year="year"
        :month="month"
        :day="day"
      />
    `
  }))
