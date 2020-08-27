import { storiesOf } from '@storybook/vue'
import EventStatusTimeline from './EventStatusTimeline'
import { FIXTURE_EVENT_HISTORIES } from '../../../../tests/Javascript/__data__/eventHistory'

export const Default = () => ({
  components: { EventStatusTimeline },
  template: `
      <v-container>
        <EventStatusTimeline/>
      </v-container>
    `
})

export const Populated = () => ({
  components: { EventStatusTimeline },
  data () {
    return {
      statuses: FIXTURE_EVENT_HISTORIES
    }
  },
  template: `
      <v-container>
        <EventStatusTimeline
          :status="3"
          :statuses="statuses"
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
