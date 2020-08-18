<template>
  <v-data-table
    v-model="selected"
    class="event-list-table"
    :headers="headers"
    :items="events"
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
          @{{ props.item.location && props.item.location.venue && props.item.location.venue.name }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ formatRangeDate(props.item.start_at, props.item.end_at) }}
        </div>
      </td>
      <td class="tag-td">
        <f-chip
          v-for="tag in props.item.event_tags"
          :key="tag.uuid"
          color="secondary"
        >
          {{ tag.name }}
        </f-chip>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.manager && props.item.manager.name }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.host && props.item.host.name }}
        </div>
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
import FChip from 'fresh-bus/components/ui/FChip'
import StatusSelect from '~/components/events/StatusSelect.vue'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'
export default {
  components: { FBtnMenu, StatusSelect, FChip },
  mixins: [
    Pagination,
    FormatRangeDate
  ],
  props: {
    events: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    },
    role: {
      default: 'admin',
      validator: value => {
        return ['admin', 'host', 'supplier'].indexOf(value) !== -1
      }
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Status', sortable: false, value: 'status_id', align: 'left' },
        { text: 'Title / Venue', value: 'name,venue', align: 'left' },
        { text: 'Date', sortable: true, value: 'start_at', align: 'left' },
        { text: 'Type', value: 'type_id', align: 'left' },
        { text: 'Managed By', value: 'manager', align: 'left' },
        { text: 'Customer', value: 'host', align: 'left' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
      ],
      actionBtnTitle: 'Manage'
    }
  },
  computed: {
    itemActions () {
      let actions = [
        { action: 'edit', text: 'Edit' }
      ]
      actions = this.generateActions(actions)
      return actions
    },
    selectedActions () {
      if (!this.selected.length) return []
      return this.generateActions()
    }
  },
  watch: {
    events () {
      const eventUuids = this.events.map(item => item.uuid)
      this.selected = this.selected.filter(item => eventUuids.includes(item.uuid))
    }
  },
  methods: {
    manage (item, event) {
      this.$emit('manage-' + item.action, event)
      this.$emit('manage', item.action, event)
    },
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
      this.$emit('manage-multiple', action, this.selected)
    },
    changeStatus (value, event) {
      this.$emit('change-status', value, event)
    },
    changeStatusMultiple (value) {
      this.$emit('change-status-multiple', value, this.selected)
    },
    generateActions (actions) {
      if (!(actions instanceof Array)) {
        actions = []
      }
      switch (this.role) {
        case 'admin':
          actions.push({ action: 'delete', text: 'Delete' })
          break
        case 'host':
          actions.push({ action: 'cancel', text: 'Cancel' })
          break
        case 'supplier':
          actions.push({ action: 'leave', text: 'Leave Event' })
          break
      }
      return actions
    }
  }
}
</script>

<style lang="styl" scoped>
  .highlight {
    background: #ffa;
  }
  /deep/ table.v-table tbody td.select-td{
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 16px;
  }
  /deep/ table.v-table tbody td.tag-td{
    width: 15%;
  }
</style>
