import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DocumentList from './DocumentList'

let documents = [
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
  }))
