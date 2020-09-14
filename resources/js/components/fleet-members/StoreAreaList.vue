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
    <template v-slot:item-inner-radius="{ item }">
      {{ item.radius }} mi.
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
export const HEADERS = [
  { text: 'Area Name', sortable: true, value: 'name', align: 'left' },
  { text: 'State', sortable: true, value: 'state', align: 'left' },
  { text: 'Radius', sortable: true, value: 'radius', align: 'left' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]
export const ITEM_ACTIONS = [
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
      headers: HEADERS,
      itemActions: ITEM_ACTIONS
    }
  }
}
</script>
