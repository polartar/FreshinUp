import { storiesOf } from '@storybook/vue'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import BasicFilter from './BasicFilter.vue'

let filters = {
  event_uuid: null,
  company_uuid: null,
  fleet_member_uuid: null,
  contractor_uuid: null,
  date_after: null,
  date_before: null
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

mock.onGet('/companies').reply(200, {
  data: [
    { uuid: 1, name: 'Company 1' },
    { uuid: 2, name: 'Company 2' },
    { uuid: 3, name: 'Company 3' },
    { uuid: 4, name: 'Company 4' }
  ]
})

mock.onGet('/fleet-members').reply(200, {
  data: [
    { uuid: 1, name: 'Truck 1' },
    { uuid: 2, name: 'Truck 2' },
    { uuid: 3, name: 'Truck 3' },
    { uuid: 4, name: 'Truck 4' }
  ]
})

mock.onGet('/companies?filter[type]=customer').reply(200, {
  data: [
    { uuid: 1, name: 'Customer 1' },
    { uuid: 2, name: 'Customer 2' },
    { uuid: 3, name: 'Customer 3' },
    { uuid: 4, name: 'Customer 4' }
  ]
})

storiesOf('FoodFleet|searches/BasicFilter', module)
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
