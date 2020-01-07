import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FleetMemberDocs from './FleetMemberDocs.vue'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'

let docs = FIXTURE_DOCUMENTS

let statuses = FIXTURE_DOCUMENT_STATUSES

let sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
]

storiesOf('FoodFleet|doc/FleetMemberDocs', module)
  .addParameters({
    backgrounds: [{ name: 'default', value: '#f1f3f6', default: true }]
  })
  .add('doc is empty', () => ({
    components: { FleetMemberDocs },
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
        <fleet-member-docs
          :docs="docs"
          :statuses="statuses"
          :sortables="sortables"
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
    components: { FleetMemberDocs },
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
    methods: {
      manage (item, doc) {
        action('manage')(item, doc)
      },
      manageMultiple (act, selected) {
        action('manageMultiple')(act, selected)
      },
      changeStatus (value, doc) {
        action('changeStatus')(value, doc)
      },
      changeStatusMultiple (value, selected) {
        action('changeStatusMultiple')(value, selected)
      }
    },
    template: `
      <v-container>
        <fleet-member-docs
          :docs="docs"
          :statuses="statuses"
          :sortables="sortables"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
          @manage="manage"
          @manage-multiple="manageMultiple"
          @change-status="changeStatus"
          @change-status-multiple="changeStatusMultiple"
        />
      </v-container>
    `
  }))
