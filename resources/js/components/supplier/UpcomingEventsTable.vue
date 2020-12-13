<template>
  <div>
    <v-layout class="white pa-2" justify-between>
      <span class="grey--text">My upcoming Events</span>
      <v-btn color="primary" round @click="viewAll()">View all</v-btn>
    </v-layout>
    <f-data-table
      :items="events"
      :is-loading="isLoading"
      :headers="EVENT_HEADERS"
      :item-actions="EVENT_ITEMS_ACTIONS"
    >
      <template v-slot:item-inner-status_id="{ item }">
        <event-status-select
          v-model="item.status_id"
          :options="eventStatuses"
          @input="changeEventStatus($event, item)"
        />
      </template>
      <template v-slot:item-inner-name="{ item }">
        <span class="primary--text">{{ item.name }}</span>
      </template>
      <template v-slot:item-inner-date="{ item }">
        {{ dateTimePeriod(item.start_at, item.end_at) }}
      </template>
      <template v-slot:item-inner-venue="{ item }">
        @ {{ item.venue.name }} <br/>
        {{ item.location.name }}
      </template>
    </f-data-table>
  </div>
</template>

<script>

  import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
  import EventStatusSelect from '~/components/events/StatusSelect.vue'
  import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'

  export const EVENT_HEADERS = [
    { text: 'Status', sortable: true, value: 'status_id', align: 'center' },
    { text: 'Event', sortable: true, value: 'name', align: 'center' },
    { text: 'Date & Time', sortable: true, value: 'date', align: 'center' },
    { text: 'Venue', sortable: true, value: 'venue', align: 'center' },
    { text: 'Manage', sortable: false, value: 'manage', align: 'center' },
  ]

  export const EVENT_ITEMS_ACTIONS = [
    { action: 'view', text: 'View' }
  ]
  export default {
    mixins: [FormatDate],
    components: {
      FDataTable,
      EventStatusSelect
    },
    props: {
      isLoading: { type: Boolean, default: false },
      eventStatuses: { type: Array, default: () => [] }
    },
    data () {
      return {
        EVENT_HEADERS,
        EVENT_ITEMS_ACTIONS
      }
    },
    methods: {
      viewAll () {
        this.$emit('view-all')
      },
      changeEventStatus (value, item) {
        this.$emit('change-status', value, item)
      },
      dateTimePeriod (start, end) {
        // TODO if same date.// Aug 25 - 28 / 2019 10:00 am – 4:00 pm
        return `${this.formatDate(start)} - ${this.formatDate(end)}`
      },
    }
  }
</script>
