import { storiesOf } from '@storybook/vue'

// Components
import FleetMemberList from './FleetMemberList.vue'

let fleetMembers = [
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
    address: { uuid: 1, city: 'CA', street: 'San Jose' },
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
    address: { uuid: 1, city: 'CA', street: 'San Jose' },
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
    address: { uuid: 1, city: 'CA', street: 'San Jose' },
    created_at: '2019-09-24T06:33:05.000000Z',
    updated_at: '2019-09-24T11:14:21.000000Z'
  }
]

let statuses = [
  { value: 1, text: 'Draft' },
  { value: 2, text: 'Pending' },
  { value: 3, text: 'Revision' },
  { value: 4, text: 'Rejected' },
  { value: 5, text: 'Approved' },
  { value: 6, text: 'On hold' }
]

storiesOf('FoodFleet|fleet-member/FleetMemberList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('doc is empty', () => ({
    components: { FleetMemberList },
    data () {
      return {
        fleetMembers: [],
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
      <v-container>
        <FleetMemberList
          :fleetMembers="fleetMembers"
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
  .add('Fleet Members is set', () => ({
    components: { FleetMemberList },
    data () {
      return {
        fleetMembers: fleetMembers,
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
      <v-container>
        <FleetMemberList
          :fleetMembers="fleetMembers"
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