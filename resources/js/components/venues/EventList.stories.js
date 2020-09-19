import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import EventList from './EventList'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

export const Default = () => ({
  components: { EventList },
  data () {
    return {
      items: []
    }
  },
  template: `
      <event-list
        :items="items"
      />
    `
})

export const Populated = () => ({
  components: { EventList },
  data () {
    return {
      items: FIXTURE_EVENTS,
      statuses: FIXTURE_EVENT_STATUSES
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    }
  },
  template: `
      <event-list
        :items="items"
        :statuses="statuses"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/venues/EventList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
