<template>
  <v-card>
    <v-card-title
      justify-space-between
      align-center
    >
      <span class="grey--text">My upcoming Events</span>
      <v-spacer />
      <v-btn
        color="primary"
        round
        @click="viewAll()"
      >
        View all
      </v-btn>
    </v-card-title>
    <v-divider />
    <v-card-text>
      <f-data-table
        :items="events"
        :is-loading="isLoading"
        :headers="HEADERS"
        :item-actions="ITEM_ACTIONS"
        :multi-item-actions="ITEMS_ACTIONS"
        v-bind="$attrs"
        v-on="$listeners"
      >
        <template v-slot:item-inner-status_id="{ item }">
          <event-status-select
            v-model="item.status_id"
            :options="statuses"
            @input="changeStatus($event, item)"
          />
        </template>
        <template v-slot:item-inner-name="{ item }">
          <a
            href="#manage-view"
            class="primary--text text-not-underline font-weight-bold"
            @click.prevent="viewItem(item)"
          >{{ item.name }}</a>
        </template>
        <template v-slot:item-inner-start_at="{ item }">
          <div class="grey--text">
            {{ formatDate(item.start_at) }} <br>
            {{ formatDate(item.end_at) }}
          </div>
        </template>
        <template v-slot:item-inner-venue="{ item }">
          <div class="grey--text">
            @ {{ get(item, 'venue.name') }} <br>
            {{ get(item, 'location.name') }}
          </div>
        </template>
        <template v-slot:header-inner-status_id="{ items }">
          <span
            v-if="items.length <= 1"
            class="grey--text"
          >Status</span>
          <f-manage-multiple
            v-else
            :items="statuses"
            item-label="name"
            label="Change status"
            @item="manageMultiple('status', items, $event)"
          />
        </template>
      </f-data-table>
    </v-card-text>
  </v-card>
</template>

<script>

import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import FManageMultiple from '@freshinup/core-ui/src/components/FManageMultiple'
import EventStatusSelect from '~/components/events/StatusSelect.vue'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import get from 'lodash/get'

export const HEADERS = [
  { text: 'Status', sortable: true, value: 'status_id', align: 'left' },
  { text: 'Event', sortable: true, value: 'name', align: 'left' },
  { text: 'Date & Time', sortable: true, value: 'start_at', align: 'left' },
  { text: 'Venue', sortable: false, value: 'venue', align: 'left' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]

export const ITEM_ACTIONS = [
  { action: 'view', text: 'View / Edit' },
  { action: 'delete', text: 'Delete' }
]

export const ITEMS_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]

export default {
  components: {
    FDataTable,
    EventStatusSelect,
    FManageMultiple
  },
  mixins: [FormatDate],
  props: {
    isLoading: { type: Boolean, default: false },
    events: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      HEADERS,
      ITEM_ACTIONS,
      ITEMS_ACTIONS
    }
  },
  methods: {
    get,
    viewItem (item) {
      this.$emit('manage-view', item)
      this.$emit('manage', 'view', item)
    },
    viewAll () {
      this.$emit('manage-multiple', 'view')
      this.$emit('manage-multiple-view')
    },
    changeStatus (value, item) {
      this.$emit('change-status', value, item)
    },
    manageMultiple (action, items, value) {
      this.$emit('manage-multiple-' + action, items, value)
      this.$emit('manage-multiple', action, items, value)
    }
  }
}
</script>
