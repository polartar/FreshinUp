import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import StoreFilter from './StoreFilter.vue'

const mock = new MockAdapter(axios)
mock.onGet('foodfleet/store-tags').reply(200, {
  data: [
    { uuid: 1, name: 'aperiam' },
    { uuid: 2, name: 'iure' },
    { uuid: 3, name: 'dicta' },
    { uuid: 4, name: 'voluptate' }
  ]
})

mock.onGet('foodfleet/owners').reply(200, {
  data: [
    { id: 1, name: 'Level1 User' },
    { id: 2, name: 'Level2 User' },
    { id: 3, name: 'Level3 User' },
    { id: 4, name: 'Level4 User' }
  ]
})

mock.onGet('foodfleet/locations').reply(200, {
  data: [
    { uuid: 1, name: 'South Abagail' },
    { uuid: 2, name: 'Lindseymouth' },
    { uuid: 3, name: 'Fredrickstad' },
    { uuid: 4, name: 'Zanderstad' }
  ]
})

storiesOf('FoodFleet|event/StoreFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { StoreFilter },
    data () {
      return {
        statuses: [
          { id: 1, name: 'Draft' },
          { id: 2, name: 'Pending' },
          { id: 3, name: 'Confirmed' },
          { id: 4, name: 'Declined' }
        ],
        types: [
          { id: 1, name: 'Mobile' },
          { id: 2, name: 'Category' }
        ]
      }
    },
    methods: {
      filterMember (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <store-filter
          :statuses="statuses"
          :types="types"
          @runFilter="filterMember"
        />
      </v-container>
    `
  }))
