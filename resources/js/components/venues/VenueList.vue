<template>
  <v-data-table
    v-model="selected"
    class="event-list-table"
    :headers="headers"
    :items="venues"
    :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
    :pagination.sync="pagination"
    :loading="isLoading"
    :total-items="totalItems"
    item-key="uuid"
    select-all
    disable-initial-sort
  >
    <v-progress-linear
      slot="progress"
      indeterminate
      height="10"
    />
    <template
      slot="headerCell"
      slot-scope="props"
    >
      <span v-if="selected.length > 1 && props.header.value === 'manage'">
        <v-menu offset-y>
          <v-btn
            slot="activator"
            color="primary"
            dark
          >
            Manage Multiple
          </v-btn>
          <v-list>
            <v-list-tile
              v-for="(item, index) in selectedActions"
              :key="index"
              @click="manageMultiple(item.action)"
            >
              <v-list-tile-title>
                {{ item.text }}
              </v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </span>
      <span v-else-if="selected.length > 1 && props.header.value === 'status_id'">
        <v-menu offset-y>
          <v-btn
            slot="activator"
            light
          >
            Change Statuses
          </v-btn>
          <v-list>
            <v-list-tile
              v-for="(item, index) in statuses"
              :key="index"
              @click="changeStatusMultiple(item.id)"
            >
              <v-list-tile-title>
                {{ item.name }}
              </v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </span>

      <span v-else>
        {{ props.header.text }}
      </span>
    </template>
    <template slot="no-data">
      <v-alert
        :value="true"
        color="error"
        icon="warning"
      >
        Sorry, nothing to display here :(
      </v-alert>
    </template>

    <template
      slot="items"
      slot-scope="props"
    >
      <td>
        <v-checkbox
          v-model="props.selected"
          primary
          hide-details
        />
      </td>
      <td class="select-td">
        <status-select
          v-model="props.item.status_id"
          :options="statuses"
          @input="changeStatus($event, props.item)"
        />
      </td>
      <td>
        <div class="subheading primary--text">
          {{ props.item.name }}
        </div>
        <div class="grey--text">
          {{ props.item.owner && props.item.owner.name }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ getLocations(props.item.locations) }}
        </div>
      </td>

      <td class="grey--text">
        {{ formatDate(props.item.created_at, 'MMM DD, YYYY') }}
      </td>
      <td>
        <f-btn-menu
          :items="itemActions"
          item-label="text"
          @item="manage($event, props.item)"
        >
          Manage
        </f-btn-menu>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
import StatusSelect from '~/components/events/StatusSelect.vue'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export default {
  components: { FBtnMenu, StatusSelect },
  mixins: [
    Pagination,
    FormatDate
  ],
  props: {
    venues: {
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
  computed: {
    selectedActions () {
      if (!this.selected.length) return []
      let actions = []
      actions.push({ action: 'delete', text: 'Delete' })
      return actions
    }
  },
  watch: {
    venues () {
      const venueUuids = this.venues.map(item => item.uuid)
      this.selected = this.selected.filter(item => venueUuids.includes(item.uuid))
    }
  },
  methods: {
    manage (item, venue) {
      this.$emit('manage-' + item.action, venue)
      this.$emit('manage', item.action, venue)
    },
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
      this.$emit('manage-multiple', action, this.selected)
    },
    changeStatus (value, venue) {
      this.$emit('change-status', value, venue)
    },
    changeStatusMultiple (value) {
      this.$emit('change-status-multiple', value, this.selected)
    },
    getLocations (locations) {
      return locations.map(location => location.name).join(',')
    }

  }
}
</script>

<style lang="styl" scoped>
  .highlight {
    background: #ffa;
  }

  /deep/ table.v-table tbody td.select-td {
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 16px;
  }

  /deep/ table.v-table tbody td.tag-td {
    width: 15%;
  }
</style>
