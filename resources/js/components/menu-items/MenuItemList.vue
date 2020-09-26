<template>
  <f-data-table
    :headers="headers"
    :items="items"
    :is-loading="isLoading"
    :item-actions="itemActions"
    :multi-item-actions="multipleItemActions"
    item-key="id"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item-inner-title,description="{ item }">
      <div class="subheading primary--text">
        {{ item.title }}
      </div>
      <div class="grey--text">
        {{ item.description }}
      </div>
    </template>
    <template v-slot:item-inner-cost="{ item }">
      <div class="grey--text">
        $ {{ item.cost }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import get from 'lodash/get'

export const DEFAULT_HEADERS = [
  { text: 'Item', sortable: true, value: 'title,description', align: 'left' },
  { text: 'Cost', sortable: true, value: 'cost', align: 'right' },
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
  components: { FDataTable },
  props: {
    items: { type: Array, default: () => [] },
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
    get
  }
}
</script>
