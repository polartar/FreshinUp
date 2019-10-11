import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import ListFilter from './ListFilter.vue'

let statuses = [
  { value: 1, text: 'Draft' },
  { value: 2, text: 'Pending' },
  { value: 3, text: 'Confirmed' },
  { value: 4, text: 'Past' },
  { value: 5, text: 'Cancelled' }
]

let sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
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

storiesOf('FoodFleet|event/ListFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { ListFilter },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <list-filter
          @runFilter="filterEvents"
        />
      </v-container>
    `
  }))
  .add('with statuses', () => ({
    components: { ListFilter },
    data () {
      return {
        statuses: statuses
      }
    },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <list-filter
          :statuses="statuses"
          @runFilter="filterEvents"
        />
      </v-container>
    `
  }))
  .add('with sortables', () => ({
    components: { ListFilter },
    data () {
      return {
        sortables: sortables
      }
    },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <list-filter
          :sortables="sortables"
          @runFilter="filterEvents"
        />
      </v-container>
    `
  }))
