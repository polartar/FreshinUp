import { storiesOf } from '@storybook/vue'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'

// Components
import MultiSimple from './MultiSimple.vue'

const mock = new MockAdapter(axios)
mock.onGet('/foodfleet/event-tags').reply(200, {
  data: [
    { uuid: 1, name: 'Event Tag 1' },
    { uuid: 2, name: 'Event Tag 2' },
    { uuid: 3, name: 'Event Tag 3' },
    { uuid: 4, name: 'Event Tag 4' }
  ]
})
mock.onGet('/terms').reply(200, {
  data: [
    { id: 1, name: 'John Smith' },
    { id: 2, name: 'Bob Loblaw' },
    { id: 3, name: 'Mario Brother' }
  ]
})

storiesOf('FoodFleet|ui/MultiSimple', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { MultiSimple },
    template: `
      <v-container>
        <multi-simple
          url="/terms"
        />
      </v-container>
    `
  }))
  .add('set tags', () => ({
    components: { MultiSimple },
    data () {
      return {
        selectTags: [1, 2, 3]
      }
    },
    template: `
      <v-container>
        <multi-simple
          v-model="selectTags"
          url="/tags"
          term-param="filter[name]"
          results-id-key="uuid"
          placeholder="Search Tag"
          background-color="white"
          class="mt-0 pt-0"
          height="48"
          notClearable
          solo
          flat
        />
      </v-container>
    `
  }))
