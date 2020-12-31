import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import CustomerList from './CustomerList'
import { FIXTURE_CUSTOMER_STATUSES } from '../../../../tests/Javascript/__data__/customerStatuses'
import { FIXTURE_CUSTOMERS } from '../../../../tests/Javascript/__data__/customers'

export const Default = () => ({
  components: { CustomerList },
  template: `
      <customer-list />
    `
})

export const Populated = () => ({
  components: { CustomerList },
  data () {
    return {
      customers: FIXTURE_CUSTOMERS,
      statuses: FIXTURE_CUSTOMER_STATUSES
    }
  },
  methods: {
    changeStatus (status, customer) {
      action('change-status')(status, customer)
    },
    manage (act, item) {
      action('manage')(act, item)
    }
  },
  template: `
      <customer-list
        :customers="customers"
        :statuses="statuses"
        @change-status="changeStatus"
        @manage="manage"
      />`
})

storiesOf('FoodFleet|components/events/CustomerList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
