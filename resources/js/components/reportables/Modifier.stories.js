import { storiesOf } from '@storybook/vue'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'
import { action } from '@storybook/addon-actions'

// Components
import Modifier from './Modifier.vue'

const paymentTypes = [
  { value: 1, text: 'Credit Card' },
  { value: 2, text: 'Money Transfer' },
  { value: 3, text: 'Google Pay' },
  { value: 4, text: 'Apple Pay' }
]

const modifierAutocomplete = {
  name: 'event_uuid',
  resource_name: 'events',
  label: 'Event',
  placeholder: 'All events',
  type: 'autocomplete',
  filter: 'name'
}

const modifierSelect = {
  name: 'payment_uuid',
  resource_name: 'payment_types',
  label: 'Payment type',
  placeholder: 'All payment types',
  type: 'select'
}

const modifierDate = {
  name: 'date_after',
  resource_name: 'date_after',
  label: 'Min Date',
  placeholder: 'Any',
  type: 'date'
}

const modifierText = {
  name: 'min_price',
  resource_name: null,
  label: 'Min price',
  placeholder: 'Min price',
  type: 'text'
}

// Mock GET request to /events
const mock = new MockAdapter(axios)
mock.onGet('/events').reply(200, {
  data: [
    { uuid: 1, name: 'Event 1' },
    { uuid: 2, name: 'Event 2' },
    { uuid: 3, name: 'Event 3' },
    { uuid: 4, name: 'Event 4' }
  ]
})

storiesOf('reportables/Modifier', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('autocomplete', () => {
    return {
      components: { Modifier },
      methods: {
        onChange (value) {
          action('Change')(value)
        }
      },
      data () {
        return {
          modifier: modifierAutocomplete
        }
      },
      template: `
          <v-container>
            <v-layout row>
                <v-flex sm3>
                  <modifier
                    :modifier="modifier"
                    @change="onChange"
                  />
                </v-flex>
            </v-layout>
          </v-container>
      `
    }
  })
  .add('select', () => {
    return {
      components: { Modifier },
      methods: {
        onChange (value) {
          action('Change')(value)
        }
      },
      data () {
        return {
          modifier: modifierSelect,
          items: paymentTypes
        }
      },
      template: `
        <v-container>
            <v-layout row>
                <v-flex sm3>
                  <modifier
                    :modifier="modifier"
                    :items="items"
                    @change="onChange"
                  />
                </v-flex>
            </v-layout>
        </v-container>
    `
    }
  })
  .add('date', () => {
    return {
      components: { Modifier },
      methods: {
        onChange (value) {
          action('Change')(value)
        }
      },
      data () {
        return {
          modifier: modifierDate
        }
      },
      template: `
      <v-container>
        <v-layout row>
          <v-flex sm3>
            <modifier
              :modifier="modifier"
              @change="onChange"
            />
          </v-flex>
        </v-layout>
      </v-container>
  `
    }
  })
  .add('text', () => {
    return {
      components: { Modifier },
      methods: {
        onChange (value) {
          action('Change')(value)
        }
      },
      data () {
        return {
          modifier: modifierText
        }
      },
      template: `
    <v-container>
      <v-layout row>
        <v-flex sm3>
          <modifier
            :modifier="modifier"
            @change="onChange"
          />
        </v-flex>
      </v-layout>
    </v-container>
`
    }
  })
