import { storiesOf } from '@storybook/vue'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import BasicFilter from './BasicFilter.vue'

let filters = {
  event_uuid: null,
  host_uuid: null,
  store_uuid: null,
  supplier_uuid: null,
  date_after: null,
  date_before: null
}

let filtersDateRange = {
  event_uuid: null,
  host_uuid: null,
  store_uuid: null,
  supplier_uuid: null,
  date_after: '2019-01-08',
  date_before: '2019-01-08'
}

// Mock GET request to /users for colleagues
const mock = new MockAdapter(axios)
mock.onGet('/events').reply(200, {
  data: [
    { uuid: 1, name: 'Event 1' },
    { uuid: 2, name: 'Event 2' },
    { uuid: 3, name: 'Event 3' },
    { uuid: 4, name: 'Event 4' }
  ]
})

mock.onGet('/companies?filter[type_key]=host').reply(200, {
  data: [
    { uuid: 1, name: 'Host 1' },
    { uuid: 2, name: 'Host 2' },
    { uuid: 3, name: 'Host 3' },
    { uuid: 4, name: 'Host 4' }
  ]
})

mock.onGet('foodfleet/stores').reply(200, {
  data: [
    { uuid: 1, name: 'Truck 1' },
    { uuid: 2, name: 'Truck 2' },
    { uuid: 3, name: 'Truck 3' },
    { uuid: 4, name: 'Truck 4' }
  ]
})

mock.onGet('/companies?filter[type_key]=supplier').reply(200, {
  data: [
    { uuid: 1, name: 'Supplier 1' },
    { uuid: 2, name: 'Supplier 2' },
    { uuid: 3, name: 'Supplier 3' },
    { uuid: 4, name: 'Supplier 4' }
  ]
})

storiesOf('FoodFleet|financials/BasicFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { BasicFilter },
    data () {
      return {
        filters: filters
      }
    },
    template: `
      <v-container>
        <basic-filter
        :filters="filters"
        />
      </v-container>
    `
  }))
  .add('with date range set', () => ({
    components: { BasicFilter },
    data () {
      return {
        filters: filtersDateRange
      }
    },
    template: `
      <v-container>
        <basic-filter
        :filters="filters"
        />
      </v-container>
    `
  }))
