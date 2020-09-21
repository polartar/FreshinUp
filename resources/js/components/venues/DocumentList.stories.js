import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import DocumentList from './DocumentList'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'

export const Empty = () => ({
  components: { DocumentList },
  template: `
    <document-list />
  `
})

export const Loading = () => ({
  components: { DocumentList },
  template: `
    <document-list
      is-loading
    />
  `
})

export const Populated = () => ({
  components: { DocumentList },
  data () {
    return {
      items: FIXTURE_DOCUMENTS,
      statuses: FIXTURE_DOCUMENT_STATUSES
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    },
    onChangeStatus (value, document) {
      action('onChangeStatus')(value, document)
    }
  },
  template: `
    <document-list
      :items="items"
      :statuses="statuses"
      @change-status="onChangeStatus"
      @manage="onManage"
      @manage-multiple="onManageMultiple"
    />
  `
})

storiesOf('FoodFleet|components/venues/DocumentList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
