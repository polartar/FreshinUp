import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
// Components
import StoreList from './StoreList.vue'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'

export const Default = () => ({
  components: { StoreList },
  data () {
    return {
      stores: [],
      statuses: FIXTURE_STORE_STATUSES,
      pagination: {
        page: 5,
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
    <store-list
      :stores="stores"
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
  components: { StoreList },
  data () {
    return {
      stores: FIXTURE_STORES,
      statuses: FIXTURE_STORE_STATUSES,
      pagination: {
        page: 5,
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
    viewDetails (params) {
      action('manage-view-details')(params)
    },
    unassign (params) {
      action('manage-unassign')(params)
    },
    unmultipleAssign (params) {
      action('manage-multiple-unassign')(params)
    }
  },
  template: `
    <store-list
      :stores="stores"
      :statuses="statuses"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @manage-view-details="viewDetails"
      @manage-unassign="unassign"
      @manage-multiple-unassign="unmultipleAssign"
    />
  `
})

storiesOf('FoodFleet|components/event/StoreList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
