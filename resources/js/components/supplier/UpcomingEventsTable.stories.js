import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import UpcomingEventsTable from './UpcomingEventsTable'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

export const Default = () => ({
  components: { UpcomingEventsTable },
  template: `
      <v-container>
        <upcoming-events-table/>
      </v-container>
    `
})

export const Loading = () => ({
  components: { UpcomingEventsTable },
  template: `
      <v-container>
        <upcoming-events-table
          is-loading
        />
      </v-container>
    `
})

export const Populated = () => ({
  components: { UpcomingEventsTable },
  data () {
    return {
      events: FIXTURE_EVENTS.map(event => {
        return {
          name: event.name,
          start: '2020-12-10',
          end: '2020-12-10',
          color: 'primary',
          timed: false
        }
      }),
      eventStatuses: FIXTURE_EVENT_STATUSES
    }
  },
  methods: {
    changeStatus (value, item) {
      action('change-status')(value, item)
    },
    manage (action_, item) {
      action('manage')(action_, item)
    },
    manageMultiple (action_, items, status) {
      action('manage-multiple')(action_, items, status)
    }
  },
  template: `
      <v-container>
        <upcoming-events-table
          :events="events"
          :statuses="eventStatuses"
          @change-status="changeStatus"
          @manage="manage"
          @manage-multiple="manageMultiple"
        />
      </v-container>
    `
})

storiesOf('FoodFleet|components/supplier/UpcomingEventsTable', module)
  .add('Default', Default)
  .add('Loading', Loading)
  .add('Populated', Populated)
