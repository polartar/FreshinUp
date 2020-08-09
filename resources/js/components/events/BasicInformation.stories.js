import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'

// Components
import BasicInformation from './BasicInformation.vue'

// Mock GET request to /users for colleagues
const mock = new MockAdapter(axios)
mock.onGet('/companies?filter[type_key]=host').reply(200, {
  data: [
    { uuid: '28138d6d-9605-42e8-9ceb-f2616a514ee5', name: 'Host 1' },
    { uuid: 2, name: 'Host 2' },
    { uuid: 3, name: 'Host 3' },
    { uuid: 4, name: 'Host 4' }
  ]
})

mock.onGet('/users?filter[type]=1').reply(200, {
  data: [
    { uuid: 'c6be43eb-461f-4654-82b5-7dd6a6f11e54', name: 'John Smith' },
    { uuid: 2, name: 'Bob Dylan' },
    { uuid: 3, name: 'Peter Parker' },
    { uuid: 4, name: 'Nick Fury' }
  ]
})

const event = {
  uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
  name: 'accusantium',
  status: 1,
  start_at: '2019-10-10 11:04:19',
  end_at: '2019-10-12 11:04:19',
  staff_notes: 'Example foodfleet staff notes',
  member_notes: 'Example fleet member notes',
  customer_notes: 'Example customer notes',
  manager: {
    id: 1,
    uuid: 'c6be43eb-461f-4654-82b5-7dd6a6f11e54',
    name: 'Demo Admin'
  },
  event_tags: [
    {
      uuid: '1',
      name: 'minus'
    },
    {
      uuid: '2',
      name: 'hic'
    }
  ],
  host: {
    id: 89,
    uuid: '28138d6d-9605-42e8-9ceb-f2616a514ee5',
    name: 'Swift-Wehner'
  },
  attendees: 10,
  budget: 1000,
  commission_rate: 12,
  commission_type: 2,
  type: 1
}

storiesOf('FoodFleet|events/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { BasicInformation },
    methods: {
      onCancel () {
        action('Cancel')('cancel')
      },
      onSave (params) {
        action('Save')(params)
      }
    },
    template: `
      <v-container>
           <basic-information
                @cancel="onCancel"
                @save="onSave"
            />
      </v-container>
    `
  }))
  .add('event set', () => ({
    components: { BasicInformation },
    data () {
      return {
        event: event
      }
    },
    methods: {
      onCancel () {
        action('Cancel')('cancel')
      },
      onSave (params) {
        action('Save')(params)
      },
      onDelete () {
        action('Delete')('delete')
      }
    },
    template: `
      <v-container>
          <basic-information
                  :event="event"
                   @cancel="onCancel"
                   @save="onSave"
                   @delete="onDelete"
              />
      </v-container>
    `
  }))
  .add('event set and read-only', () => ({
    components: { BasicInformation },
    data () {
      return {
        event: event
      }
    },
    template: `
      <v-container>
           <basic-information
                  :event="event"
                  read-only
              />
      </v-container>
    `
  }))
