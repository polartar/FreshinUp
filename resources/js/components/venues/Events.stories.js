import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import Events from './Events'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

export const Empty = () => ({
  components: { Events },
  data () {
    return {
      items: [],
      statuses: []
    }
  },
  template: `
    <events
      :items="items"
      :statuses="statuses"
    />
  `
})

export const Loading = () => ({
  components: { Events },
  template: `
    <events
      is-loading
    />
  `
})

export const Populated = () => ({
  components: { Events },
  data () {
    return {
      items: FIXTURE_EVENTS,
      statuses: FIXTURE_EVENT_STATUSES,
      sortables: [
        'status',
        'name',
        'start_at',
        'manager',
        'host'
      ]
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    },
    onRunFilter (params) {
      action('runFilter')(params)
    },
    onStatusChange (value) {
      action('onStatusChange')(value)
    }
  },
  template: `
    <events
      :sortables="sortables"
      :items="items"
      :statuses="statuses"
      @change-status="onStatusChange"
      @manage="onManage"
      @manage-multiple="onManageMultiple"
      @runFilter="onRunFilter"
    />
  `
})

storiesOf('FoodFleet|components/venues/Events', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
