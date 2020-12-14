import { storiesOf } from '@storybook/vue'
import UpcomingEventsCalendar from './UpcomingEventsCalendar'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'
import  { action } from '@storybook/addon-actions'

export const Default = () => ({
  components: { UpcomingEventsCalendar },
  template: `
      <v-container>
        <upcoming-events-calendar/>
      </v-container>
    `
})

export const Populated = () => {
  return ({
    components: { UpcomingEventsCalendar },
    data () {
      return {
        events: FIXTURE_EVENTS,
        statuses: FIXTURE_EVENT_STATUSES,
      }
    },
    methods: {
      manageMultiple (action_, items, value) {
        action('manage-multiple')(action_, items, value)
      }
    },
    template: `
        <v-container>
          <upcoming-events-calendar
            :events="events"
            :statuses="statuses"
            @manage-multiple="manageMultiple"
          />
        </v-container>
      `
  })
}

storiesOf('FoodFleet|components/supplier/UpcomingEventsCalendar', module)
  .add('Default', Default)
  .add('Populated', Populated)
