import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import DocumentSection from './DocumentSection'

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
    title: 'Document Title',
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

storiesOf('FoodFleet|event/DocumentSection', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('documents are not set', () => ({
    components: { DocumentSection },
    data () {
      return {
        documents: [],
        statuses: statuses
      }
    },
    template: `
      <document-section 
        :statuses="statuses" 
        :documents="[]" 
      />
    `
  }))
  .add('documents are set', () => ({
    components: { DocumentSection },
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
      },
      createNewDoc () {
        action('create-new-doc')()
      }
    },
    template: `
      <document-section 
        :statuses="statuses" 
        :documents="documents" 
        @change-status="changeStatus"
        @view-details="viewDetails"
        @create-new-doc="createNewDoc"
      />
    `
  }))
