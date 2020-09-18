import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import VenueList from './VenueList.vue'
import { FIXTURE_VENUES } from '../../../../tests/Javascript/__data__/venues'

let statuses = [
  { id: 1, name: 'Pending', color: 'warning' },
  { id: 2, name: 'Approved', color: 'success' },
  { id: 3, name: 'Rejected', color: 'error' },
  { id: 4, name: 'Expiring', color: 'warning' },
  { id: 5, name: 'Expired', color: 'accent' }
]

storiesOf('FoodFleet|components/venues/VenueList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('events is empty', () => ({
    components: { VenueList },
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
      <venue-list
        :venues="venues"
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
    components: { VenueList },
    data () {
      return {
        venues: FIXTURE_VENUES,
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
      edit (params) {
        action('manage-edit')(params)
      },
      del (params) {
        action('manage-delete')(params)
      },
      multipleDelete (params) {
        action('manage-multiple-delete')(params)
      },
      changeStatus (status, venue) {
        action('change-status')(status, venue)
      },
      changeStatusMultiple (status, venues) {
        action('change-status-multiple')(status, venues)
      }
    },
    template: `
      <venue-list
        :venues="venues"
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
  }))
