import { storiesOf } from '@storybook/vue'

// Components
import EventList from './EventList.vue'

let events = [
  {
    uuid: 'a7936425-485a-4419-9acd-13cdccaed346',
    name: 'accusantium',
    status: 1,
    start_at: '2019-10-10 11:04:19',
    end_at: '2019-10-12 11:04:19',
    manager: {
      id: 1,
      uuid: 'c6be43eb-461f-4654-82b5-7dd6a6f11e54',
      name: 'Demo Admin'
    },
    venue: {
      uuid: '4b2e762d-ec19-44ef-a1ad-78e7c45dec00',
      name: 'New Hattie'
    },
    event_tags: [
      {
        uuid: 'ff4b2b90-ad3a-41a5-aeaf-6cade3854674',
        name: 'minus'
      },
      {
        uuid: 'f05ef6a0-b149-40d1-a571-bc725ea9cf7a',
        name: 'hic'
      }
    ],
    host: {
      id: 89,
      uuid: '28138d6d-9605-42e8-9ceb-f2616a514ee5',
      name: 'Swift-Wehner'
    }
  },
  {
    uuid: 'c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5',
    name: 'saepe',
    status: 2,
    start_at: '2019-10-10 11:04:19',
    end_at: '2019-10-12 11:04:19',
    manager: {
      id: 3,
      uuid: '2ccdd232-c73a-4398-a2dc-342de7d43bf1',
      name: 'Level 2 User'
    },
    venue: {
      uuid: '4d6ace0e-5f3f-423a-ab47-648a142ba450',
      name: 'Baronhaven'
    },
    event_tags: [
      {
        uuid: 'ff4b2b90-ad3a-41a5-aeaf-6cade3854674',
        name: 'minus'
      },
      {
        uuid: 'dd6dccb2-424e-4f0c-8c36-3bf75b5f7cb0',
        name: 'sit'
      }
    ],
    host: {
      id: 96,
      uuid: '5d3e79a3-81aa-4645-b1fd-c6173026e01f',
      name: 'Goodwin-Carroll'
    }
  },
  {
    uuid: '790aba97-1eb6-4630-82d9-7bd561256c67',
    name: 'quibusdam',
    status: 3,
    start_at: '2019-10-10 11:04:19',
    end_at: '2019-10-12 11:04:19',
    manager: {
      id: 2,
      uuid: '16527881-c80f-42d8-850f-594b6d5ec4a0',
      name: 'Level 1 User'
    },
    venue: {
      uuid: 'cfc8c89e-000b-4adb-8f1a-9cec5aecc6ef',
      name: 'Lake Lavernehaven'
    },
    event_tags: [
      {
        uuid: 'ff4b2b90-ad3a-41a5-aeaf-6cade3854674',
        name: 'minus'
      },
      {
        uuid: 'f05ef6a0-b149-40d1-a571-bc725ea9cf7a',
        name: 'hic'
      }
    ],
    host: {
      id: 94,
      uuid: '77f3a8af-450f-4505-889f-a705cf720b3a',
      name: 'Carter-Green'
    }
  }
]

let statuses = [
  { value: 1, text: 'Draft' },
  { value: 2, text: 'Pending' },
  { value: 3, text: 'Confirmed' },
  { value: 4, text: 'Past' },
  { value: 5, text: 'Cancelled' }
]

storiesOf('FoodFleet|event/EventList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('events is empty', () => ({
    components: { EventList },
    data () {
      return {
        events: [],
        statuses: statuses,
        pagination: {
          page: 1,
          rowsPerPage: 10,
          totalItems: 5
        },
        sorting: {
          descending: false,
          sortBy: ''
        }
      }
    },
    template: `
      <v-container>
        <EventList
          :events="events"
          :statuses="statuses"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
        />
      </v-container>
    `
  }))
  .add('Events is set', () => ({
    components: { EventList },
    data () {
      return {
        events: events,
        statuses: statuses,
        pagination: {
          page: 1,
          rowsPerPage: 10,
          totalItems: 5
        },
        sorting: {
          descending: false,
          sortBy: ''
        }
      }
    },
    template: `
      <v-container>
        <EventList
          :events="events"
          :statuses="statuses"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
        />
      </v-container>
    `
  }))
