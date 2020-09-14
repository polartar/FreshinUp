import { storiesOf } from '@storybook/vue'
import EventStatusTimeline from './EventStatusTimeline'
import { FIXTURE_EVENT_HISTORIES } from '../../../../tests/Javascript/__data__/eventHistory'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

export const Default = () => ({
  components: { EventStatusTimeline },
  template: `
      <v-container class="white">
        <EventStatusTimeline/>
      </v-container>
    `
})

export const Populated = () => ({
  components: { EventStatusTimeline },
  data () {
    return {
      histories: FIXTURE_EVENT_HISTORIES.slice(0, 4),
      statuses: FIXTURE_EVENT_STATUSES,
      status: 4
    }
  },
  template: `
      <v-container class="white">
        <EventStatusTimeline
          :status="status"
          :statuses="statuses"
          :histories="histories"
        />
      </v-container>
    `
})

storiesOf('FoodFleet|components/event/EventStatusTimeline', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
