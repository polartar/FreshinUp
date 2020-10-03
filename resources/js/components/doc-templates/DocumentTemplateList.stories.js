import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import DocumentTemplateList from './DocumentTemplateList'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'
import { FIXTURE_DOCUMENT_TEMPLATE_STATUSES } from '../../../../tests/Javascript/__data__/documentTemplateStatuses'

export const Empty = () => ({
  components: { DocumentTemplateList },
  data () {
    return {
      items: []
    }
  },
  template: `
      <document-template-list
        :items="items"
      />
    `
})

export const IsLoading = () => ({
  components: { DocumentTemplateList },
  template: `
      <document-template-list
        is-loading
      />
    `
})

export const Populated = () => ({
  components: { DocumentTemplateList },
  data () {
    return {
      items: FIXTURE_DOCUMENT_TEMPLATES,
      statuses: FIXTURE_DOCUMENT_TEMPLATE_STATUSES,
      pagination: {
        rowsPerPage: 5,
        page: 1,
        totalItems: 10
      },
      sorting: {
        sortBy: 'title',
        descending: false
      }
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    },
    onChangeStatus (status, item) {
      action('onChangeStatus')(status, item)
    },
    paginate (value) {
      action('paginate')(value)
    }
  },
  template: `
      <document-template-list
        :items="items"
        :statuses="statuses"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @paginate="paginate"
        @change-status="onChangeStatus"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/doc-templates/DocumentTemplateList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('IsLoading', IsLoading)
  .add('Populated', Populated)
