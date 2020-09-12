import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import StoreAreaList from './StoreAreaList'
import {FIXTURE_STORE_AREAS} from '../../../../tests/Javascript/__data__/storeAreas'


storiesOf('FoodFleet|store/StoreAreaList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('stores is empty', () => ({
    components: { StoreAreaList },
    data () {
      return {
        storeAreas: [],
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
      <store-area-list
        :store-areas="storeAreas"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
      />
    `
  }))
  .add('Stores is set', () => ({
    components: { StoreAreaList },
    data () {
      return {
        storeAreas: FIXTURE_STORE_AREAS,
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
      del (params) {
        action('manage-delete')(params)
      }
    },
    template: `
      <store-area-list
        :store-areas="storeAreas"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @manage-delete="del"
      />
    `
  }))
