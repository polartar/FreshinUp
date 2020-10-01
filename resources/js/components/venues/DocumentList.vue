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
    <template v-slot:item-inner-status_id="{ item }">
      <status-select
        v-model="item.status_id"
        :options="statuses"
        @input="changeStatus($event, item)"
      />
    </template>
    <template v-slot:item-inner-title="{ item }">
      {{ item.title }}
    </template>
    <template v-slot:item-inner-created_at="{ item }">
      {{ formatDate(item.created_at, 'MMM DD, YYYY') }}
    </template>
    <template v-slot:item-inner-expiration_at="{ item }">
      {{ formatDate(item.expiration_at, 'MMM DD, YYYY') }}
    </template>
  </f-data-table>
</template>

<script>
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import StatusSelect from '~/components/docs/StatusSelect'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'

export const HEADERS = [
  { text: 'Status', sortable: true, value: 'status_id', align: 'left' },
  { text: 'Document', sortable: true, value: 'title', align: 'left' },
  { text: 'Submitted on', sortable: true, value: 'created_at', align: 'center' },
  { text: 'Expiration date', sortable: true, value: 'expiration_at', align: 'center' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]
export const ITEM_ACTIONS = [
  { action: 'view', text: 'View / Edit' },
  { action: 'delete', text: 'Delete' }
]
export const MULTIPLE_ITEM_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]
export default {
  components: { StatusSelect, FDataTable },
  mixins: [FormatDate],
  props: {
    isLoading: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      itemActions: ITEM_ACTIONS,
      multipleItemActions: MULTIPLE_ITEM_ACTIONS,
      headers: HEADERS
    }
  },
  methods: {
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    }
  }
}
</script>
