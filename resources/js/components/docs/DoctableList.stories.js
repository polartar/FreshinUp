import { storiesOf } from '@storybook/vue'

// Components
import DoctableList from './DoctableList.vue'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'

let statuses = [
  { value: 1, text: 'Pending' },
  { value: 2, text: 'Approved' },
  { value: 3, text: 'Rejected' },
  { value: 4, text: 'Expiring' },
  { value: 5, text: 'Expired' }
]

const sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
]

export const Empty = () => ({
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
})

export const Populated = () => ({
  components: { DoctableList },
  data () {
    return {
      docs: FIXTURE_DOCUMENTS,
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
})

storiesOf('FoodFleet|components/docs/DoctableList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Populated', Populated)
