import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DocumentList from './DocumentList'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'

export const Populated = () => ({
  components: { DocumentList },
  data () {
    return {
      documents: FIXTURE_DOCUMENTS,
      statuses: FIXTURE_DOCUMENT_STATUSES
    }
  },
  methods: {
    changeStatus (status, doc) {
      action('change-status')(status, doc)
    },
    viewDetails (value) {
      action('view-details')(value)
    }
  },
  template: `
      <document-list
        :documents="documents"
        :statuses="statuses"
        @change-status="changeStatus"
        @view-details="viewDetails"
      />`
})

export const Empty = () => ({
  components: { DocumentList },
  data () {
    return {
      documents: [],
      statuses: []
    }
  },
  template: `
      <document-list
        :documents="documents"
        :statuses="statuses"
      />
    `
})

storiesOf('FoodFleet|components/event/DocumentList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Populated', Populated)
