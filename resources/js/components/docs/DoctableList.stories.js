import { storiesOf } from '@storybook/vue'

// Components
import DoctableList from './DoctableList.vue'

let docs = [
  {
    created_at: '2019-09-24T06:33:05.000000Z',
    description: 'G9IsfcBhWA2RlK2R1HcJx85XGvXgBPZ4Xgx5m48qaDzKYfROOJ',
    expiration_at: '2019-09-28 07:52:32',
    id: 1,
    notes: 'qJBS0ZJhlSipdYxkRRxF',
    owner: {
      id: 11, first_name: 'Colleague 2', last_name: 'User'
    },
    status: 1,
    title: 'sint1233',
    type: 1,
    updated_at: '2019-09-24T11:14:21.000000Z'
  },
  {
    created_at: '2019-09-24T06:33:05.000000Z',
    description: 'G9IsfcBhWA2RlK2R1HcJx85XGvXgBPZ4Xgx5m48qaDzKYfROOJ',
    expiration_at: '2019-09-28 07:52:32',
    id: 2,
    notes: 'qJBS0ZJhlSipdYxkRRxF',
    owner: {
      id: 11, first_name: 'Colleague 2', last_name: 'User'
    },
    status: 2,
    title: 'sint1233',
    type: 2,
    updated_at: '2019-09-24T11:14:21.000000Z'
  },
  {
    created_at: '2019-09-24T06:33:05.000000Z',
    description: 'G9IsfcBhWA2RlK2R1HcJx85XGvXgBPZ4Xgx5m48qaDzKYfROOJ',
    expiration_at: '2019-09-28 07:52:32',
    id: 3,
    notes: 'qJBS0ZJhlSipdYxkRRxF',
    owner: {
      id: 11, first_name: 'Colleague 2', last_name: 'User'
    },
    status: 3,
    title: 'sint1233',
    type: 2,
    updated_at: '2019-09-24T11:14:21.000000Z'
  }
]

let statuses = [
  { value: 1, text: 'Pending' },
  { value: 2, text: 'Approved' },
  { value: 3, text: 'Rejected' },
  { value: 4, text: 'Expiring' },
  { value: 5, text: 'Expired' }
]

let sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
]

storiesOf('FoodFleet|doc/DoctableList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('doc is empty', () => ({
    components: { DoctableList },
    data () {
      return {
        docs: [],
        statuses: statuses,
        sortables: sortables,
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
      <v-container>
        <doctable-list
          :docs="docs"
          :statuses="statuses"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
        />
      </v-container>
    `
  }))
  .add('docs is set', () => ({
    components: { DoctableList },
    data () {
      return {
        docs: docs,
        statuses: statuses,
        sortables: sortables,
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
      <v-container>
        <doctable-list
          :docs="docs"
          :statuses="statuses"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
        />
      </v-container>
    `
  }))
