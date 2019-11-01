import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import StoreList from './StoreList.vue'

let stores = [
  {
    uuid: 1,
    status: 1,
    name: 'sint1233',
    type: 'Food Truck',
    events: [
      { uuid: 1 },
      { uuid: 2 },
      { uuid: 3 }
    ],
    tags: [
      { uuid: 1, name: 'Asian' },
      { uuid: 2, name: 'Vietnamese' }
    ],
    addresses: [{ uuid: 1, city: 'Salvador', street: 'Rua' }],
    created_at: '2019-09-24T06:33:05.000000Z',
    updated_at: '2019-09-24T11:14:21.000000Z'
  },
  {
    uuid: 2,
    status: 2,
    name: 'sint1233',
    type: 'Food Truck',
    events: [
      { uuid: 1 },
      { uuid: 2 }
    ],
    tags: [
      { uuid: 1, name: 'Asian' },
      { uuid: 2, name: 'Vietnamese' },
      { uuid: 3, name: 'Asian Fusion' }
    ],
    addresses: [{ uuid: 1, city: 'Los Angeles', street: 'Road' }],
    created_at: '2019-09-24T06:33:05.000000Z',
    updated_at: '2019-09-24T11:14:21.000000Z'
  },
  {
    uuid: 3,
    status: 3,
    name: 'sint1233',
    type: 'Food Truck',
    events: [
      { uuid: 1 },
      { uuid: 2 },
      { uuid: 3 }
    ],
    tags: [
      { uuid: 1, name: 'Asian' },
      { uuid: 2, name: 'Vietnamese' },
      { uuid: 3, name: 'Asian Fusion' }
    ],
    addresses: [{ uuid: 1, city: 'San Jose', street: 'Street' }],
    created_at: '2019-09-24T06:33:05.000000Z',
    updated_at: '2019-09-24T11:14:21.000000Z'
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
