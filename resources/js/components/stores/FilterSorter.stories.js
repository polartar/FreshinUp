import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import FilterSorter from './FilterSorter.vue'

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Revision' },
  { id: 4, name: 'Rejected' },
  { id: 5, name: 'Approved' },
  { id: 6, name: 'On hold' }
]

let sortables = [
  { value: '-created_at', text: 'Newest' },
  { value: 'created_at', text: 'Oldest' },
  { value: 'title', text: 'Title (A - Z)' },
  { value: '-title', text: 'Title (Z - A)' }
]

const mock = new MockAdapter(axios)
mock.onGet('foodfleet/store-tags').reply(200, {
  data: [
    { uuid: '1', name: 'Tag 1' },
    { uuid: '2', name: 'Tag 2' },
    { uuid: '3', name: 'Tag 3' },
    { uuid: '4', name: 'Tag 4' }
  ]
})

mock.onGet('/users?filter[type]=2&filter[status]=1').reply(200, {
  data: [
    { uuid: '1', name: 'Owner 1' },
    { uuid: '2', name: 'Owner 2' },
    { uuid: '3', name: 'Owner 3' },
    { uuid: '4', name: 'Owner 4' }
  ]
})

storiesOf('FoodFleet|stores/FilterSorter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { FilterSorter },
    methods: {
      filterStores (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          @runFilter="filterStores"
        />
      </v-container>
    `
  }))
  .add('with statuses', () => ({
    components: { FilterSorter },
    data () {
      return {
        statuses: statuses
      }
    },
    methods: {
      filterStores (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          :statuses="statuses"
          @runFilter="filterStores"
        />
      </v-container>
    `
  }))
  .add('with sortables', () => ({
    components: { FilterSorter },
    data () {
      return {
        sortables: sortables
      }
    },
    methods: {
      filterStores (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container style="background-color: rgba(0,0,0,.2)">
        <filter-sorter
          :sortables="sortables"
          @runFilter="filterStores"
        />
      </v-container>
    `
  }))
