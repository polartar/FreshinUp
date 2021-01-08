<template>
  <v-card>
    <v-card-title
      justify-space-between
      align-center
    >
      <span class="grey--text">My fleets</span>
      <v-spacer />
      <v-btn
        color="primary"
        round
        @click="viewAll"
      >
        View All
      </v-btn>
    </v-card-title>
    <v-divider />
    <v-layout
      row
      class="px-4 py-2"
    >
      <v-flex
        v-for="(stat, statIndex) in statusStats"
        :key="statIndex"
        xs12
        sm6
        md3
      >
        <store-status-stat
          :label="stat.label"
          :value="stat.value"
          :color="stat.color"
        />
      </v-flex>
    </v-layout>
    <v-divider />
    <v-card-text>
      <v-layout
        row
        wrap
      >
        <v-flex xs12>
          <f-data-table
            :headers="headers"
            :items="stores"
            :item-actions="itemActions"
            :multi-item-actions="itemsActions"
            :is-loading="isLoading"
            v-bind="$attrs"
            v-on="$listeners"
          >
            <template v-slot:item-inner-status_id="{ item }">
              <store-status-select
                v-model="item.status_id"
                :options="statuses"
                @input="changeStatus($event, item)"
              />
            </template>
            <template v-slot:item-inner-name="{ item }">
              <a
                class="font-weight-bold text-not-underline"
                href="#view-item"
                @click.prevent="viewItem(item)"
              >{{ item.name }}</a> <br>
              {{ get(item, 'type.name') }}
            </template>
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
          </f-data-table>
        </v-flex>
      </v-layout>
    </v-card-text>
  </v-card>
</template>

<script>
import StoreStatusStat from '~/components/fleet-members/StoreStatusStat.vue'
import StoreStatusSelect from '~/components/fleet-members/StatusSelect.vue'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import FManageMultiple from '@freshinup/core-ui/src/components/FManageMultiple'
import get from 'lodash/get'

export const HEADERS = [
  { text: 'Status', sortable: true, value: 'status_id', align: 'left' },
  { text: 'Name / Category', sortable: true, value: 'name', align: 'left' },
  { text: 'Hometown', sortable: true, value: 'state_of_incorporation', align: 'left' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]

export const ITEM_ACTIONS = [
  { action: 'edit', text: 'View / Edit' },
  { action: 'delete', text: 'Delete' }
]

export const ITEMS_ACTIONS = [
  { action: 'delete', text: 'Delete' }
]

export default {
  components: {
    StoreStatusStat,
    StoreStatusSelect,
    FDataTable,
    FManageMultiple
  },
  props: {
    isLoading: { type: Boolean, default: false },
    stores: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    statusStats: { type: Array, default: () => [] },
    headers: { type: Array, default: () => HEADERS },
    itemActions: { type: Array, default: () => ITEM_ACTIONS },
    itemsActions: { type: Array, default: () => ITEMS_ACTIONS }
  },
  methods: {
    get,
    changeStatus (value, item) {
      this.$emit('change-status', value, item)
    },
    viewItem (item) {
      this.$emit('manage-view', item)
      this.$emit('manage', 'view', item)
    },
    viewAll () {
      this.$emit('manage-multiple-view')
      this.$emit('manage-multiple', 'view')
    },
    manageMultiple (action, items, value) {
      this.$emit('manage-multiple-' + action, items, value)
      this.$emit('manage-multiple', action, items, value)
    }
  }
}
</script>
