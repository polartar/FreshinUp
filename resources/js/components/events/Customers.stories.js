import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import Customers from './Customers.vue'

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Confirmed' },
  { id: 4, name: 'Past' },
  { id: 5, name: 'Cancelled' }
]

let customers = [
  {
    uuid: 'b343f4a1-433a-7435-6ae4-14fdcaae4371',
    status: 3,
    updated_at: '2019-01-01 00:00:00',
    created_at: '2019-01-01 00:00:00'
  }
]

storiesOf('FoodFleet|event/Customers', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { Customers },
    data () {
      return {
        statuses: statuses,
        customers: []
      }
    },
    methods: {
      viewDetails (params) {
        action('manage-view-details')(params)
      }
    },
    template: `
      <v-container>
        <customers
          :statuses="statuses"
          :customers="customers"
          @manage-view-details="viewDetails"
        />
      </v-container>
    `
  }))
  .add('with customers', () => ({
    components: { Customers },
    data () {
      return {
        statuses: statuses,
        customers: customers
      }
    },
    methods: {
      viewDetails (params) {
        action('manage-view-details')(params)
      }
    },
    template: `
      <v-container>
        <customers
          :statuses="statuses"
          :customers="customers"
          @manage-view-details="viewDetails"
        />
      </v-container>
    `
  }))
