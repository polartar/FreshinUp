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
      histories: FIXTURE_EVENT_HISTORIES,
      statuses: FIXTURE_EVENT_STATUSES,
      status: FIXTURE_EVENT_STATUSES[2].id
    }
  },
  template: `
      <v-container class="white">
        <EventStatusTimeline
          :status="3"
          :statuses="statuses"
          :histories="histories"
        />
      </v-container>
    `
})

export const AllChecked = () => ({
  components: { EventStatusTimeline },
  data () {
    return {
      histories: FIXTURE_EVENT_HISTORIES.map(h => ({
        ...h,
        completed: true
      })),
      statuses: FIXTURE_EVENT_STATUSES,
      status: FIXTURE_EVENT_STATUSES[2].id
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

export const Cancelled = () => ({
  components: { EventStatusTimeline },
  data () {
    return {
      histories: [...FIXTURE_EVENT_HISTORIES,
        {
          id: 8,
          event_uuid: 'abc123',
          status_id: 8,
          completed: false,
          date: '2020-08-24T21:58:43',
          description: 'Customer will review and sign the final event contract'
        }],
      statuses: FIXTURE_EVENT_STATUSES,
      status: FIXTURE_EVENT_STATUSES[7].id
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
  .add('AllChecked', AllChecked)
  .add('Cancelled', Cancelled)
