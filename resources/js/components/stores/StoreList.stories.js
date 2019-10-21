import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import StoreList from './StoreList.vue'

let stores = [
  {
    'uuid': '2e76e6cd-a60e-4428-91df-b198d0ad854b',
    'name': 'voluptate',
    'square_id': '27734'
  },
  {
    'uuid': '95433f2f-e814-4d4c-bec1-05669d113e16',
    'name': 'eius',
    'square_id': '44773'
  },
  {
    'uuid': '9afecc3e-2bfd-42c6-9e6c-946b71b479f7',
    'name': 'autem',
    'square_id': '6592'
  },
  {
    'uuid': 'd5daf004-0335-443e-8150-4f2d84f2859f',
    'name': 'et',
    'square_id': '91538'
  },
  {
    'uuid': '2e03bf8d-d611-4ae8-b3d5-b0afe7fa2297',
    'name': 'et',
    'square_id': '93915'
  }
]

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Confirmed' },
  { id: 4, name: 'Past' },
  { id: 5, name: 'Cancelled' }
]

storiesOf('FoodFleet|store/StoreList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('stores is empty', () => ({
    components: { StoreList },
    data () {
      return {
        stores: [],
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
  }))
  .add('Stores is set', () => ({
    components: { StoreList },
    data () {
      return {
        stores: stores,
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
      changeStatus (status, store) {
        action('change-status')(status, store)
      },
      changeStatusMultiple (status, stores) {
        action('change-status-multiple')(status, stores)
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
        @manage-edit="edit"
        @manage-delete="del"
        @manage-multiple-delete="multipleDelete"
        @change-status="changeStatus"
        @change-status-multiple="changeStatusMultiple"
      />
    `
  }))
