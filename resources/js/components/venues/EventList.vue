<template>
  <f-data-table
    :headers="headers"
    :items="items"
    :is-loading="isLoading"
    :item-actions="itemActions"
    :multi-item-actions="itemActions"
    item-key="id"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item-inner-status_id="{ item }">
      <status-select
        v-model="item.status_id"
        :options="statuses"
        @input="changeStatus($event, item)"
      />
    </template>
    <template v-slot:item-inner-name,tags="{ item }">
      <div class="grey--text">
        {{ item.name }}
      </div>
      <div v-if="item.tags.length">
        <v-chip
          v-for="(tag, index) of item.tags"
          :key="index"
          class="white--text"
          color="orange"
        >
          {{ tag.name }}
        </v-chip>
      </div>
    </template>
    <template v-slot:item-inner-start_at,venue="{ item }">
      <div class="grey--text">
        {{ item.start_at }}
      </div>
      <div class="grey--text">
        @{{ get(item, 'venue.name') }}
      </div>
    </template>
    <template v-slot:item-inner-manager="{ item }">
      <div class="grey--text">
        {{ get(item, 'manager.name') }}
      </div>
    </template>
    <template v-slot:item-inner-host="{ item }">
      <div class="grey--text">
        {{ get(item, 'host.name') }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import StatusSelect from '~/components/docs/StatusSelect'
import get from 'lodash/get'

export const HEADERS = [
  { text: 'Status', sortable: false, value: 'status_id', align: 'left' },
  { text: 'Title / Category', value: 'name,tags', align: 'left' },
  { text: 'Date/ Venue', sortable: true, value: 'start_at,venue', align: 'left' },
  { text: 'Managed By', value: 'manager', align: 'left' },
  { text: 'Customer', value: 'host', align: 'left' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
]
export const ITEM_ACTIONS = [
  { action: 'view', text: 'View / Edit' },
  { action: 'delete', text: 'Delete' }
]
export default {
  components: { FDataTable, StatusSelect },
  props: {
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      headers: HEADERS,
      itemActions: ITEM_ACTIONS
    }
  },
  methods: {
    get,
    changeStatus (value, event) {
      this.$emit('change-status', value, event)
    }
  }
}
</script>
