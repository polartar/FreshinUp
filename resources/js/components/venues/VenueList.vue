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
    <template v-slot:item-inner-created_at="{ item }">
      <div class="grey--text">
        {{ formatDate(item.created_at, 'MMM DD, YYYY') }}
      </div>
    </template>
    <template v-slot:item-inner-status_id="{ item }">
      <status-select
        v-model="item.status_id"
        :options="statuses"
        @input="changeStatus($event, props.item)"
      />
    </template>
    <template v-slot:item-inner-name,owner_uuid="{ item }">
      <div class="subheading primary--text">
        {{ item.name }}
      </div>
      <div class="grey--text">
        {{ item.owner && item.owner.name }}
      </div>
    </template>
    <template v-slot:item-inner-locations="{ item }">
      <div class="grey--text">
        {{ getLocations(item.locations) }}
      </div>
    </template>
  </f-data-table>
</template>

<script>
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
import StatusSelect from '~/components/events/StatusSelect.vue'

export default {
  components: { FDataTable, StatusSelect },
  mixins: [
    FormatDate
  ],
  props: {
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Status', sortable: false, value: 'status_id', align: 'left' },
        { text: 'Venue / Owner', value: 'name,owner_uuid', align: 'left' },
        { text: 'Locations', sortable: true, value: 'locations', align: 'left' },
        { text: 'Submitted On', value: 'created_at', align: 'left' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
      ],
      itemActions: [
        { action: 'view', text: 'View / Edit' },
        { action: 'delete', text: 'Delete' }
      ],
      actionBtnTitle: 'Manage'
    }
  },
  methods: {
    changeStatus (value, venue) {
      this.$emit('change-status', value, venue)
    },
    getLocations (locations) {
      return locations.map(location => location.name).join(',')
    }

  }
}
</script>
