import { storiesOf } from '@storybook/vue'

// Components
import EventCalendar from './EventCalendar.vue'

storiesOf('FoodFleet|event/EventCalendar', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('with out events', () => ({
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
  .add('with events', () => ({
    components: { EventCalendar },
    data () {
      return {
        events: [{
          name: 'Meeting A',
          start: '2019-12-27',
          end: '2019-12-27',
          status: 'draft'
        }, {
          name: 'Meeting B',
          start: '2019-12-23',
          end: '2019-12-24',
          status: 'pending'
        }, {
          name: 'Meeting C',
          start: '2019-12-24',
          end: '2019-12-24',
          status: 'confirmed'
        }, {
          name: 'Meeting D',
          start: '2019-12-12',
          end: '2019-12-15',
          status: 'past'
        }, {
          name: 'Meeting E',
          start: '2019-12-23',
          end: '2019-12-23',
          status: 'cancelled'
        }],
        type: 'month',
        yearRange: [2010, 2020],
        year: 2019,
        month: 12,
        day: 31
      }
    },
    methods: {
      clickEvent () {
        alert('click event')
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
        @click-event="clickEvent"
      />
    `
  }))
