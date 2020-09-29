<template>
  <f-data-table
    :headers="headers"
    :items="options"
    :is-loading="isLoading"
    :item-actions="itemActions"
    :multi-item-actions="multipleItemActions"
    item-key="uuid"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item-inner-name="{ item }">
      <div class="subheading primary--text">
        {{ item.name }}
      </div>

      <f-chip
        text-color="white"
        color="warning"
        class="font-weight-bold text-uppercase caption"
      >
        {{ get(item, 'category.name') }}
      </f-chip>
    </template>
    <template v-slot:item-inner-eventNames="{ item }">
      <div class="text-truncate">
        {{ item.eventNames }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import FChip from '@freshinup/core-ui/src/components/FChip'
import get from 'lodash/get'
export const HEADERS = [
  { text: 'Location name / category', sortable: true, value: 'name' },
  { text: 'Capacity', sortable: true, value: 'capacity', align: 'center' },
  { text: 'Associated events', sortable: false, value: 'eventNames', align: 'right' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]
export const ITEM_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]
export const DEFAULT_MULTIPLE_ITEM_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]
export default {
  components: { FDataTable, FChip },
  props: {
    items: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      headers: HEADERS,
      itemActions: ITEM_ACTIONS,
      multipleItemActions: DEFAULT_MULTIPLE_ITEM_ACTIONS
    }
  },
  computed: {
    options () {
      return get(this, 'items', [])
        .map(location => ({
          ...location,
          eventNames: get(location, 'events', [])
            .map(event => event.name).join(', ')
        }))
    }
  },
  methods: {
    get
  }
}
</script>
