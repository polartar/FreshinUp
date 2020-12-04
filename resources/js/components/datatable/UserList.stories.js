import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
// Components
import UserList from './UserList'
import { FIXTURE_USER_STATUSES } from '../../../../tests/Javascript/__data__/userStatuses'
import { FIXTURE_USER_LEVELS } from '../../../../tests/Javascript/__data__/userLevels'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'

export const Empty = () => ({
  components: { UserList },
  data () {
    return {
      users: [],
      statuses: FIXTURE_USER_STATUSES,
      levels: FIXTURE_USER_LEVELS,
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
    <UserList
      :users="users"
      :levels="levels"
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
  components: { UserList },
  data () {
    return {
      users: FIXTURE_USERS,
      statuses: FIXTURE_USER_STATUSES,
      levels: FIXTURE_USER_LEVELS,
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
    <UserList
      :users="users"
      :levels="levels"
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

storiesOf('FoodFleet|components/datatable/UserList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Set', Set)
