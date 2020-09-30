<template>
  <f-data-table
    :headers="headers"
    :items="items"
    :is-loading="isLoading"
    :item-actions="itemActions"
    :multi-item-actions="multipleItemActions"
    item-key="uuid"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item-inner-created_at="{ item }">
      <div class="grey--text">
        {{ formatDate(item.created_at, 'MMM DD, YYYY') }}
      </div>
    </template>
    <template v-slot:item-inner-status_id="{ item }">
      <status-select
        v-model="item.status_id"
        :options="statuses"
        @input="changeStatus($event, item)"
      />
    </template>
    <template v-slot:item-inner-name="{ item }">
      <div class="subheading primary--text">
        {{ item.name }}
      </div>
      <div class="grey--text">
        {{ get(item, 'owner.name') }}
      </div>
    </template>
    <template v-slot:item-inner-locations="{ item }">
      <div class="grey--text">
        {{ get(item, 'locations', []) | toNames }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
import StatusSelect from '~/components/events/StatusSelect.vue'
import get from 'lodash/get'

export const DEFAULT_HEADERS = [
  { text: 'Status', sortable: true, value: 'status_id', align: 'left' },
  { text: 'Venue / Owner', sortable: true, value: 'name', align: 'left' },
  { text: 'Locations', sortable: false, value: 'locations', align: 'left' },
  { text: 'Submitted On', sortable: true, value: 'created_at', align: 'left' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]
export const DEFAULT_ITEM_ACTIONS = [
  { action: 'view', text: 'View / Edit' },
  { action: 'delete', text: 'Delete' }
]
export const DEFAULT_MULTIPLE_ITEM_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]
export default {
  components: { FDataTable, StatusSelect },
  filters: {
    toNames (value) {
      return value.map(location => location.name).join(', ')
    }
  },
  mixins: [
    FormatDate
  ],
  props: {
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      headers: DEFAULT_HEADERS,
      itemActions: DEFAULT_ITEM_ACTIONS,
      multipleItemActions: DEFAULT_MULTIPLE_ITEM_ACTIONS
    }
  },
  methods: {
    get,
    changeStatus (value, venue) {
      this.$emit('change-status', value, venue)
    }
  }
}
</script>
