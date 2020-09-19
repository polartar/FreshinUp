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
        @{{ item.venue && item.venue.name }}
      </div>
    </template>
    <template v-slot:item-inner-manager="{ item }">
      <div class="grey--text">
        {{ item.manager && item.manager.name }}
      </div>
    </template>
    <template v-slot:item-inner-host="{ item }">
      <div class="grey--text">
        {{ item.host && item.host.name }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import StatusSelect from '~/components/docs/StatusSelect'

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
  computed: {
    selectedDocActions () {
      if (!this.selected.length) return []
      let actions = []
      actions.push({ action: 'delete', text: 'Delete' })
      return actions
    }
  },
  methods: {
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    },
    manageMultiple (value) {
      this.$emit('manage-multiple', value, this.selected)
    }
  }
}
</script>
