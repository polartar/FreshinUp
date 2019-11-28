import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DocumentList from './DocumentList'

let documents = [
  {
    title: 'Document Title',
    owner: 'document owner',
    status: 1,
    updated_at: '2019-10-10 11:04:19',
    uuid: 'f5777e5d-4ee4-4df3-abca-2dae0fb90b42'
  },
  {
    title: 'Employee IDs',
    owner: 'employee info center',
    status: 1,
    updated_at: '2019-10-10 11:04:19',
    uuid: 'f5777e5d-4ee4-4df3-abca-2dae0fb90b42'
  },
  {
    title: 'Title',
    owner: 'document center',
    status: 1,
    updated_at: '2019-10-12 11:04:19',
    uuid: 'f5777e5d-4ee4-4df3-abca-2dae0fb90b42'
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
  .add('with documents', () => ({
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
  }))
