import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import CustomerList from './CustomerList'

let customers = [
  {
    uuid: '6c3a3242-7158-408c-a7c7-8051100db64f',
    status: 1,
    created_at: '2019-09-24T06:33:05.000000Z',
    updated_at: '2019-09-24T11:14:21.000000Z'
  }
]

let statuses = [
  { id: 1, name: 'Required' },
  { id: 2, name: 'Draft' },
  { id: 3, name: 'Pending' },
  { id: 4, name: 'Approved' },
  { id: 5, name: 'Rejected' }
]

storiesOf('FoodFleet|event/CustomerList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('without customer', () => ({
    components: { CustomerList },
    data () {
      return {
        customers: [],
        statuses: statuses
      }
    },
    template: `
      <customer-list
        :customers="customers"
        :statuses="statuses"
      />
    `
  }))
  .add('with customers', () => ({
    components: { CustomerList },
    data () {
      return {
        customers: customers,
        statuses: statuses
      }
    },
    methods: {
      changeStatus (status, customer) {
        action('change-status')(status, customer)
      },
      viewDetails (value) {
        action('view-details')(value)
      }
    },
    template: `
      <customer-list
        :customers="customers"
        :statuses="statuses"
        @change-status="changeStatus"
        @view-details="viewDetails"
      />`
  }))
