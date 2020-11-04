import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
// Components
import EventList from './EventList.vue'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'

export const Empty = () => ({
  components: { EventList },
  data () {
    return {
      events: [],
      statuses: FIXTURE_EVENT_STATUSES,
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
    <event-list
      :events="events"
      :statuses="statuses"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
    />
  `
})

export const Set = () => ({
  components: { EventList },
  data () {
    return {
      events: FIXTURE_EVENTS,
      statuses: FIXTURE_EVENT_STATUSES,
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
    edit (params) {
      action('manage-edit')(params)
    },
    del (params) {
      action('manage-delete')(params)
    },
    multipleDelete (params) {
      action('manage-multiple-delete')(params)
    },
    changeStatus (status, event) {
      action('change-status')(status, event)
    },
    changeStatusMultiple (status, events) {
      action('change-status-multiple')(status, events)
    }
  },
  template: `
    <event-list
      :events="events"
      :statuses="statuses"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @manage-edit="edit"
      @manage-delete="del"
      @manage-multiple-delete="multipleDelete"
      @change-status="changeStatus"
      @change-status-multiple="changeStatusMultiple"
    />
  `
})

export const ForHost = () => ({
  components: { EventList },
  data () {
    return {
      events: FIXTURE_EVENTS,
      statuses: FIXTURE_EVENT_STATUSES,
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
    edit (params) {
      action('manage-edit')(params)
    },
    cancel (params) {
      action('manage-cancel')(params)
    },
    multipleCancel (params) {
      action('manage-multiple-cancel')(params)
    },
    changeStatus (status, event) {
      action('change-status')(status, event)
    },
    changeStatusMultiple (status, events) {
      action('change-status-multiple')(status, events)
    }
  },
  template: `
    <event-list
      :events="events"
      :statuses="statuses"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      role="host"
      @manage-edit="edit"
      @manage-cancel="cancel"
      @manage-multiple-cancel="multipleCancel"
      @change-status="changeStatus"
      @change-status-multiple="changeStatusMultiple"
    />
  `
})

export const ForSupplier = () => ({
  components: { EventList },
  data () {
    return {
      events: FIXTURE_EVENTS,
      statuses: FIXTURE_EVENT_STATUSES,
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
    edit (params) {
      action('manage-edit')(params)
    },
    leave (params) {
      action('manage-leave')(params)
    },
    multipleLeave (params) {
      action('manage-multiple-leave')(params)
    },
    changeStatus (status, event) {
      action('change-status')(status, event)
    },
    changeStatusMultiple (status, events) {
      action('change-status-multiple')(status, events)
    }
  },
  template: `
    <event-list
      :events="events"
      :statuses="statuses"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      role="supplier"
      @manage-edit="edit"
      @manage-leave="leave"
      @manage-multiple-leave="multipleLeave"
      @change-status="changeStatus"
      @change-status-multiple="changeStatusMultiple"
    />
  `
})

storiesOf('FoodFleet|components/events/EventList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('events is empty', Empty)
  .add('Events is set', Set)
  .add('for host', ForHost)
  .add('for supplier ', ForSupplier)
