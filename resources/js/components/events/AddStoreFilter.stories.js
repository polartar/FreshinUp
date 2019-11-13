import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import AddStoreFilter from './AddStoreFilter.vue'

const mock = new MockAdapter(axios)
mock.onGet('foodfleet/store-tags').reply(200, {
  data: [
    { uuid: 1, name: 'aperiam' },
    { uuid: 2, name: 'iure' },
    { uuid: 3, name: 'dicta' },
    { uuid: 4, name: 'voluptate' }
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

storiesOf('FoodFleet|event/AddStoreFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { AddStoreFilter },
    data () {
      return {
        types: [
          { uuid: 1, name: 'modi' },
          { uuid: 2, name: 'ipsum' },
          { uuid: 3, name: 'architecto' }
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
        <add-store-filter
          :types="types"
          @runFilter="filterMember"
        />
      </v-container>
    `
  }))
