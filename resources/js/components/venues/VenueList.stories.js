import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import VenueList from './VenueList.vue'
import { FIXTURE_VENUE_STATUSES, FIXTURE_VENUES } from '../../../../tests/Javascript/__data__/venues'

export const Empty = () => ({
  components: { VenueList },
  data () {
    return {
      venues: [],
      statuses: FIXTURE_VENUE_STATUSES,
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
})

export const Populated = () => ({
  components: { VenueList },
  data () {
    return {
      venues: FIXTURE_VENUES,
      statuses: FIXTURE_VENUE_STATUSES,
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
})

storiesOf('FoodFleet|components/venues/VenueList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Populated', Populated)
