import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

// Components
import AssignedEvents from './AssignedEvents.vue'

const events = FIXTURE_EVENTS
const statuses = FIXTURE_EVENT_STATUSES

storiesOf('FoodFleet|events/AssignedEvents', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('events is empty', () => ({
    components: { AssignedEvents },
    data () {
      return {
        events: [],
        statuses: statuses,
        pagination: {
          page: 1,
          rowsPerPage: 10,
          totalItems: 5
        },
        sorting: {
          descending: false,
          sortBy: ''
        }
      }
    },
    template: `
      <assigned-events
        :events="events"
        :statuses="statuses"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
      />
    `
  }))
  .add('Events is set', () => ({
    components: { AssignedEvents },
    data () {
      return {
        events: events,
        statuses: statuses,
        pagination: {
          page: 1,
          rowsPerPage: 10,
          totalItems: 5
        },
        sorting: {
          descending: false,
          sortBy: ''
        }
      }
    },
    methods: {
      viewEvent (params) {
        action('view')(params)
      },
      sort (params) {
        action('sort')(params)
      },
      searchInput (params) {
        action('searchInput')(params)
      }
    },
    template: `
      <assigned-events
        :events="events"
        :statuses="statuses"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @viewEvent="viewEvent"
        @sort="sort"
        @searchInput="searchInput"
      />
    `
  }))
