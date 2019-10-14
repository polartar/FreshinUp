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
    { uuid: 4, name: 'Event Tag 4' },
    { uuid: 5, name: 'Event Tag 5' },
    { uuid: 6, name: 'Event Tag 6' },
    { uuid: 7, name: 'Event Tag 7' },
    { uuid: 8, name: 'Event Tag 8' },
    { uuid: 9, name: 'Event Tag 9' },
    { uuid: 10, name: 'Event Tag 10' },
    { uuid: 11, name: 'Aaaaa' },
    { uuid: 12, name: 'Bbbbb' }
  ]
})

storiesOf('FoodFleet|event/MultiSimple', module)
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
          url="foodfleet/event-tags"
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
  .add('set tags', () => ({
    components: { MultiSimple },
    template: `
      <v-container>
        <multi-simple
          :value="[1, 2, 3]"
          url="foodfleet/event-tags"
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
