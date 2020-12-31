import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
// Components
import StoreList from './StoreList.vue'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'

export const Default = () => ({
  components: { StoreList },
  template: `
    <store-list/>
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
    manageView (params) {
      action('manage-view')(params)
    },
    manageUnassign (params) {
      action('manage-unassign')(params)
    },
    manageMultipleUnassign (params) {
      action('manage-multiple-unassign', params)
    },
    changeStatus (value, store) {
      action('change-status')(value, store)
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
      @change-status="changeStatus"
      @manage-view="manageView"
      @manage-unassign="manageUnassign"
      @manage-multiple-unassign="manageMultipleUnassign"
    />
  `
})

storiesOf('FoodFleet|components/events/StoreList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
