import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DocumentList from './DocumentList'

let documents = [
  {
    title: 'Document Title',
    owner: 'document owner',
    status: 1,
    updated_at: '2019-10-10 11:04:19'
  },
  {
    title: 'Employee IDs',
    owner: 'employee info center',
    status: 1,
    updated_at: '2019-10-10 11:04:19'
  },
  {
    title: 'Title',
    owner: 'document center',
    status: 1,
    updated_at: '2019-10-12 11:04:19'
  }
]

let statuses = [
  { id: 1, name: 'Pending' },
  { id: 2, name: 'Rejected' },
  { id: 3, name: 'Approved' },
  { id: 4, name: 'Expiring' },
  { id: 5, name: 'Cancelled' }
]

storiesOf('FoodFleet|event/DocumentList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('without documents', () => ({
    components: { DocumentList },
    data () {
      return {
        documents: [],
        statuses: statuses
      }
    },
    template: `
      <document-list
        :documents="documents"
        :statuses="statuses"
      />
    `
  }))
  .add('with document examples', () => ({
    components: { DocumentList },
    data () {
      return {
        documents: documents,
        statuses: statuses
      }
    },
    methods: {
      changeStatus (status, event) {
        action('change-status')(status, event)
      }
    },
    template: `
      <document-list
        :documents="documents"
        :statuses="statuses"
        @change-status="changeStatus"
      />`
  }))
