import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import FilterSorter from './FilterSorter.vue'

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Confirmed' },
  { id: 4, name: 'Past' },
  { id: 5, name: 'Cancelled' }
]

const mock = new MockAdapter(axios)
mock.onGet('/foodfleet/event-tags').reply(200, {
  data: [
    { uuid: 1, name: 'Event Tag 1' },
    { uuid: 2, name: 'Event Tag 2' },
    { uuid: 3, name: 'Event Tag 3' },
    { uuid: 4, name: 'Event Tag 4' }
  ]
})
mock.onGet('/companies?filter[type_key]=host').reply(200, {
  data: [
    { uuid: 1, name: 'company 1' },
    { uuid: 2, name: 'company 2' },
    { uuid: 3, name: 'company 3' },
    { uuid: 4, name: 'company 4' }
  ]
})
mock.onGet('/users').reply(200, {
  data: [
    { uuid: 1, name: 'user 1' },
    { uuid: 2, name: 'user 2' },
    { uuid: 3, name: 'user 3' },
    { uuid: 4, name: 'user 4' }
  ]
})

storiesOf('FoodFleet|event/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { FilterSorter },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          @runFilter="filterEvents"
        />
      </v-container>
    `
  }))
  .add('with statuses', () => ({
    components: { FilterSorter },
    data () {
      return {
        statuses: statuses,
        filters: {
          status: statuses.map(item => item.id),
          host_uuid: null,
          manager_uuid: null,
          event_tag_uuid: null,
          start_at: null,
          end_at: null
        }
      }
    },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          :filters="filters"
          :statuses="statuses"
          @runFilter="filterEvents"
        />
      </v-container>
    `
  }))
