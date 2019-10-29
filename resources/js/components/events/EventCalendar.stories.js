import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import EventCalendar from './EventCalendar.vue'

storiesOf('FoodFleet|event/EventCalendar', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('without events', () => ({
    components: { EventCalendar },
    data () {
      return {
        events: [],
        type: 'month',
        yearRange: [2010, 2020],
        date: '2019-12-31'
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
  .add('with events', () => ({
    components: { EventCalendar },
    data () {
      return {
        events: [{
          name: 'Meeting A',
          start_at: '2019-12-27 13:15:01',
          end_at: '2019-12-27 05:33:29',
          status: 'draft'
        }, {
          name: 'Meeting B',
          start_at: '2019-12-23 13:15:02',
          end_at: '2019-12-24 05:33:28',
          status: 'pending'
        }, {
          name: 'Meeting C',
          start_at: '2019-12-24 13:15:03',
          end_at: '2019-12-24 05:33:27',
          status: 'confirmed'
        }, {
          name: 'Meeting D',
          start_at: '2019-12-12 13:15:04',
          end_at: '2019-12-15 05:33:26',
          status: 'past'
        }, {
          name: 'Meeting E',
          start_at: '2019-12-23 13:15:05',
          end_at: '2019-12-23 05:33:25',
          status: 'cancelled'
        }],
        type: 'month',
        yearRange: [2010, 2020],
        date: '2019-12-31'
      }
    },
    methods: {
      clickEvent (params) {
        action('Click Event')(params)
      }
    },
    template: `
      <event-calendar
        :events="events"
        :type="type"
        :yearRange="yearRange"
        :date="date"
        @click-event="clickEvent"
      />
    `
  }))
