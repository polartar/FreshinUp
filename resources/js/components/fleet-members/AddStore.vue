<template>
  <div>
    <div class="px-2 py-2">
      <filter-sorter
        :types="types"
        @input="runFilters"
      />
    </div>
    <div
      class="px-2"
      style="border-top: 1px solid gainsboro;"
    >
      <f-data-table
        :headers="headers"
        :items="stores"
        :multi-item-actions="multipleItemActions"
        item-key="uuid"
        hide-actions
        select-all
        v-bind="$attrs"
        v-on="$listeners"
      >
        <template v-slot:item-inner-name="{ item }">
          <div style="position: relative;">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <a
                  class="primary--text"
                  href="#"
                  @click.prevent="viewItem"
                >
                  {{ get(item, 'name') }}
                </a>
              </template>
              View fleet member details (new tab)
            </v-tooltip>
          </div>
        </template>
        <template v-slot:item-inner-tags="{ item }">
          <div>
            <v-chip
              v-for="(tag, index) of get(item, 'tags', [])"
              :key="index"
              color="secondary"
              text-color="white"
            >
              {{ tag.name }}
            </v-chip>
          </div>
        </template>
        <template v-slot:item-inner-state_of_incorporation="{ item }">
          <div class="grey--text">
            {{ get(item, 'state_of_incorporation') }}
          </div>
        </template>
        <template v-slot:item-inner-manage="{ item }">
          <v-btn
            :depressed="manageButtonLabel(item) !== 'Assign'"
            :disabled="!isEligible(item)"
            :class="manageButtonClass(item)"
            @click="onManageClicked(item)"
          >
            {{ manageButtonLabel(item) }}
          </v-btn>
        </template>
      </f-data-table>
    </div>
  </div>
</template>
<script>
import get from 'lodash/get'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import AddStoreFilterSorter from './AddStoreFilterSorter'

export const HEADERS = [
  { text: 'Fleet member', value: 'name' },
  { text: 'State of incorporation', value: 'state_of_incorporation' },
  { text: 'Tags', value: 'tags' },
  { text: 'Manage', value: 'manage' }
]

export const MULTIPLE_ITEM_ACTIONS = []
export default {
  components: {
    FDataTable,
    FilterSorter: AddStoreFilterSorter
  },
  props: {
    headers: { type: Array, default: () => HEADERS },
    multipleItemActions: { type: Array, default: () => MULTIPLE_ITEM_ACTIONS },
    event: { type: Object, required: true, default: () => ({}) },
    stores: { type: Array, default: () => [] },
    types: { type: Array, default: () => [] }
  },
  data () {
    return {
      showFilters: false
    }
  },
  methods: {
    get,
    runFilters (payload) {
      this.$emit('run-filter', payload)
    },
    viewItem (item) {
      this.$emit('manage', 'view', item)
      this.$emit('manage-view', item)
    },
    // TODO: methods need more attention at some point later in the future
    hasBookedAnEvent (member) {
      const stores = get(member, 'event_stores', [])
      return stores.findIndex(e => e.event_uuid !== this.event.uuid) !== -1
    },
    isEligible (member) {
      return !this.hasBookedAnEvent(member) && !member['has_expired_licences_docs']
    },
    isAssignedToThisEvent (member) {
      const stores = get(member, 'event_stores', [])
      return stores.findIndex(e => e.event_uuid === this.event.uuid) !== -1
    },
    isDeclined (member) {
      const stores = get(member, 'event_stores', [])
      return stores.findIndex(e => e.event_uuid === this.event.uuid && e.declined) !== -1
    },
    manageButtonLabel (item) {
      if (this.isEligible(item)) {
        if (this.isDeclined(item)) { return 'Declined' }
        if (!this.isAssignedToThisEvent(item)) { return 'Assign' } else { return 'Assigned' }
      }
      if (this.hasBookedAnEvent(item)) { return 'Booked' }
      if (item['has_expired_licences_docs']) { return 'Expired' }
      return ''
    },
    onManageClicked (item) {
      if (this.manageButtonLabel(item) === 'Assign') {
        this.$emit('manage', 'assign', item)
        this.$emit('manage-assign', item)
      }
    },
    manageButtonClass (item) {
      const label = this.manageButtonLabel(item)
      return {
        'primary': label === 'Assign',
        'blue-grey lighten-5': ['Expired', 'Booked'].includes(label),
        'blue-grey lighten-3 white--text': ['Declined', 'Assigned'].includes(label)
      }
    }
  }
}
</script>
