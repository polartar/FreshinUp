<template>
  <f-data-table
    :headers="headers"
    :items="stores"
    :item-actions="itemActions"
    :multi-item-actions="multipleItemActions"
    item-key="uuid"
    v-bind="$attrs"
    v-on="$listeners"
  >
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

    <template v-slot:item-inner-status_id="{ item }">
      <status-select
        :value="item.status_id"
        :options="statuses"
        @input="changeStatus($event, item)"
      />
    </template>

    <template v-slot:item-inner-name="{ item }">
      <div class="subheading primary--text">
        <a
          href="#manage-view"
          title="view item"
          class="font-weight-bold text-not-underline"
          @click.prevent="manage({ action: 'view'}, item)"
        >{{ item.name }}</a>
      </div>
      <div class="grey--text">
        {{ get(item, 'type.name') }}
      </div>
    </template>

    <template v-slot:item-inner-tags="{ item }">
      <v-chip
        v-for="(tag, index) in item.tags"
        :key="index"
      >
        {{ tag.name }}
      </v-chip>
    </template>

    <template v-slot:item-inner-owner="{ item }">
      <div class="grey--text">
        {{ get(item, 'owner.name') }}
      </div>
      <div class="grey--text">
        {{ get(item, 'owner.company_name') }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import FManageMultiple from '@freshinup/core-ui/src/components/FManageMultiple'
import StatusSelect from './StatusSelect'
import get from 'lodash/get'
export const HEADERS = [
  { text: 'Status', sortable: true, value: 'status_id' },
  { text: 'Fleet Member Name / Type', sortable: true, value: 'name' },
  { text: 'Tags', sortable: false, value: 'tags' },
  { text: 'Owned By', sortable: true, value: 'owner' },
  { text: 'State Of Incorporation', sortable: true, value: 'state_of_incorporation' },
  { text: 'Manage', sortable: false, value: 'manage' }
]
export const ITEM_ACTIONS = [
  { action: 'view', text: 'View / Edit' },
  { action: 'delete', text: 'Delete' }
]
export const MULTIPLE_ITEM_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]

export default {
  components: {
    FManageMultiple,
    FDataTable,
    StatusSelect
  },
  mixins: [
    Pagination,
    FormatDate
  ],
  props: {
    stores: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: HEADERS,
      itemActions: ITEM_ACTIONS,
      multipleItemActions: MULTIPLE_ITEM_ACTIONS,
      actionBtnTitle: 'Manage'
    }
  },
  computed: {
    selectedStoreActions () {
      if (!this.selected.length) return []
      let actions = []
      actions.push({ action: 'delete', text: 'Delete' })
      return actions
    }
  },
  methods: {
    get,
    manage (item, store) {
      this.$emit('manage-' + item.action, store)
      this.$emit('manage', item.action, store)
    },
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
      this.$emit('manage-multiple', action, this.selected)
      this.selected = []
    },
    changeStatus (value, store) {
      this.$emit('change-status', value, store)
    },
    changeStatusMultiple (value) {
      this.$emit('change-status-multiple', value, this.selected)
    }
  }
}
</script>

<style scoped lang="scss">
  .fresh-bus-admin-user-list {
    &__joined_date {
      white-space: nowrap;
    }
  }
  .highlight {
    background: #ffa;
  }
  table.v-table tbody td.select-td{
    padding-top: 5px;
    padding-bottom: 5px;
  }
</style>
