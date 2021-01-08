<template>
  <f-data-table
    :headers="headers"
    :items="customers"
    :item-actions="itemActions"
    :multi-item-actions="multipleItemActions"
    item-key="uuid"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item-inner-status="{ item }">
      <status-select
        v-model="item.status"
        :options="statuses"
        @input="changeStatus(item.status, item)"
      />
    </template>

    <template v-slot:item-inner-created_at="{ item }">
      {{ formatDate(item.created_at) }}
    </template>
    <template v-slot:item-inner-updated_at="{ item }">
      {{ formatDate(item.updated_at) }}
    </template>

    <template v-slot:item-inner-manage="{ item }">
      <v-btn
        class="primary ml-0"
        @click="viewItem(item)"
      >
        View Details
      </v-btn>
    </template>
  </f-data-table>
</template>

<script>
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import StatusSelect from '~/components/events/StatusSelect.vue'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'

export const HEADERS = [
  { text: 'CONTRACT STATUS', sortable: true, value: 'status', align: 'left' },
  { text: 'LAST UPDATED ON', sortable: false, value: 'updated_at', align: 'left' },
  { text: 'SUBMITTED ON', sortable: true, value: 'created_at', align: 'left' },
  { text: 'MANAGE', sortable: false, value: 'manage', align: 'center' }
]
export const ITEM_ACTIONS = [
  { action: 'view', text: 'View' }
]

export const MULTIPLE_ITEM_ACTIONS = []
export default {
  components: {
    FDataTable,
    StatusSelect
  },
  mixins: [ FormatDate ],
  props: {
    customers: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    headers: { type: Array, default: () => HEADERS },
    itemActions: { type: Array, default: () => ITEM_ACTIONS },
    multipleItemActions: { type: Array, default: () => MULTIPLE_ITEM_ACTIONS }
  },
  methods: {
    changeStatus (value, customer) {
      this.$emit('change-status', value, customer)
    },
    viewItem (item) {
      this.$emit('manage', 'view', item)
      this.$emit('manage-view', item)
    }
  }
}
</script>

<style lang="styl" scoped>
  /deep/ table.v-table thead th {
    font-weight: bolder;
  }
  /deep/ table.v-table {
    border-top: 1px solid lightgray;
    border-bottom: 1px solid lightgray;
  }
</style>
