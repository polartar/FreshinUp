<template>
  <f-data-table
    class="store-list-table"
    :headers="headers"
    :items="stores"
    :item-actions="itemActions"
    :multi-item-actions="multipleItemActions"
    item-key="uuid"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item-inner-status_id="{ item }">
      <status-select
        :value="item.status_id"
        :options="statuses"
        @input="changeStatus(item.status_id, item)"
      />
    </template>

    <template v-slot:item-inner-name="{ item }">
      <div class="subheading primary--text">
        <a
          href="#manage-view"
          title="view item"
          class="font-weight-bold text-not-underline"
          @click.prevent="viewItem(item)"
        >{{ item.name }}</a>
      </div>
      <div class="grey--text">
        {{ get(item, 'type.name') }}
      </div>
    </template>

    <template v-slot:item-inner-store_tags="{ item }">
      <f-chip
        v-for="tag in item.store_tags"
        :key="tag.uuid"
        color="secondary"
      >
        {{ tag.name }}
      </f-chip>
    </template>

    <template v-slot:item-inner-owner="{ item }">
      <div class="grey--text">
        {{ get(item, 'owner.name') }}
      </div>
      <div class="grey--text">
        @ {{ get(item, 'owner.company.name') }}
      </div>
    </template>

    <template v-slot:item-inner-location="{ item }">
      <div class="grey--text">
        {{ get(item, 'location.name') }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FChip from 'fresh-bus/components/ui/FChip'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'
import StatusSelect from '~/components/fleet-members/StatusSelect.vue'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import get from 'lodash/get'

export const HEADERS = [
  { text: 'STATUS', sortable: true, value: 'status_id', align: 'left' },
  { text: 'NAME / TYPE', value: 'name', align: 'left' },
  { text: 'TAGS', sortable: false, value: 'store_tags', align: 'left' },
  { text: 'OWNER', value: 'owner', align: 'left' },
  { text: 'LOCATION', value: 'location', align: 'left' },
  { text: 'MANAGE', sortable: false, value: 'manage', align: 'center' }
]
export const ITEM_ACTIONS = [
  { action: 'view', text: 'View' },
  { action: 'unassign', text: 'Unassign' }
]

export const MULTIPLE_ITEM_ACTIONS = [
  { action: 'unassign', text: 'Unassign' }
]
export default {
  components: { FChip, StatusSelect, FDataTable },
  mixins: [
    Pagination,
    FormatRangeDate
  ],
  props: {
    stores: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    headers: { type: Array, default: () => HEADERS },
    itemActions: { type: Array, default: () => ITEM_ACTIONS },
    multipleItemActions: { type: Array, default: () => MULTIPLE_ITEM_ACTIONS }
  },
  methods: {
    get,
    viewItem (item) {
      this.$emit('manage', 'view', item)
      this.$emit('manage-view', item)
    },
    changeStatus (value, store) {
      this.$emit('change-status', value, store)
    }
  }
}
</script>
