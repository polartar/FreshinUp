import { storiesOf } from '@storybook/vue'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import AdvancedFilter from './AdvancedFilter.vue'

const baseFilters = {
  event_uuid: 1,
  date_after: '2012-09-11'
}

const mock = new MockAdapter(axios)
mock.onGet('event-tags').reply(200, {
  data: [
    { uuid: 1, name: 'Event Tag 1' },
    { uuid: 2, name: 'Event Tag 2' },
    { uuid: 3, name: 'Event Tag 3' },
    { uuid: 4, name: 'Event Tag 4' }
  ]
})

mock.onGet('locations').reply(200, {
  data: [
    { uuid: 1, name: 'Location 1' },
    { uuid: 2, name: 'Location 2' },
    { uuid: 3, name: 'Location 3' },
    { uuid: 4, name: 'Location 4' }
  ]
})

mock.onGet('customers?term').reply(200, {
  data: [
    { uuid: 1, name: 'Customer 1' },
    { uuid: 2, name: 'Customer 2' },
    { uuid: 3, name: 'Customer 3' },
    { uuid: 4, name: 'Customer 4' }
  ]
})

mock.onGet('customers?filter[square_id]').reply(200, {
  data: [
    { uuid: 1, square_id: '182848' },
    { uuid: 2, square_id: '152522' },
    { uuid: 3, square_id: '1255455' },
    { uuid: 4, square_id: '2365556' }
  ]
})

mock.onGet('customers?filter[reference_id]').reply(200, {
  data: [
    { uuid: 1, reference_id: '182848' },
    { uuid: 2, reference_id: '152522' },
    { uuid: 3, reference_id: '1255455' },
    { uuid: 4, reference_id: '2365556' }
  ]
})

mock.onGet('staff?term').reply(200, {
  data: [
    { uuid: 1, name: 'Staff 1' },
    { uuid: 2, name: 'Staff 2' },
    { uuid: 3, name: 'Staff 3' },
    { uuid: 4, name: 'Staff 4' }
  ]
})

mock.onGet('staff?filter[square_id]').reply(200, {
  data: [
    { uuid: 1, square_id: '182848' },
    { uuid: 2, square_id: '152522' },
    { uuid: 3, square_id: '1255455' },
    { uuid: 4, square_id: '2365556' }
  ]
})

mock.onGet('categories').reply(200, {
  data: [
    { uuid: 1, name: 'Category 1' },
    { uuid: 2, name: 'Category 2' },
    { uuid: 3, name: 'Category 3' },
    { uuid: 4, name: 'Category 4' }
  ]
})

mock.onGet('items').reply(200, {
  data: [
    { uuid: 1, name: 'Item 1' },
    { uuid: 2, name: 'Item 2' },
    { uuid: 3, name: 'Item 3' },
    { uuid: 4, name: 'Item 4' }
  ]
})

mock.onGet('transactions?filter[square_id]').reply(200, {
  data: [
    { uuid: 1, square_id: '182848' },
    { uuid: 2, square_id: '152522' },
    { uuid: 3, square_id: '1255455' },
    { uuid: 4, square_id: '2365556' }
  ]
})

mock.onGet('payments?filter[square_id]').reply(200, {
  data: [
    { uuid: 1, square_id: '182848' },
    { uuid: 2, square_id: '152522' },
    { uuid: 3, square_id: '1255455' },
    { uuid: 4, square_id: '2365556' }
  ]
})

const paymentTypes = [
  { uuid: 1, name: 'Credit Card' },
  { uuid: 2, name: 'Debit Card' },
  { uuid: 3, name: 'Cash' }
]

const devices = [
  { uuid: 1, name: 'Device 1' },
  { uuid: 2, name: 'Device 2' },
  { uuid: 3, name: 'Device 3' },
  { uuid: 4, name: 'Device 4' }
]

const advancedFilters = {
  event_tag_uuid: null,
  location_uuid: null,
  customer_uuid: null,
  staff_uuid: null,
  device_uuid: null,
  category_uuid: null,
  item_uuid: null,
  min_price: null,
  max_price: null,
  payment_type_uuid: null,
  transaction_uuid: null,
  payment_uuid: null
}

storiesOf('FoodFleet|financials/AdvancedFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('inside dialog', () => ({
    components: { AdvancedFilter },
    data () {
      return {
        baseFilters: baseFilters,
        paymentTypes: paymentTypes,
        devices: devices,
        filters: advancedFilters,
        dialog: false
      }
    },
    methods: {
      closeDialog () {
        this.dialog = false
      }
    },
    template: `
    <v-container>
        <v-dialog
            v-model="dialog"
            scrollable
            max-width="436"
          >
            <v-btn
              slot="activator"
              color="primary"
              dark
            >
              Advanced Filters
            </v-btn>

             <advanced-filter
              :base-filters="baseFilters"
              :advanced-filters="filters"
              :payment-types="paymentTypes"
              :devices="devices"
              @close="closeDialog"
              />
        </v-dialog>
    </v-container>
`
  }))
