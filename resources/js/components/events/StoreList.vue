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
      <span v-else-if="selected.length > 1 && props.header.value === 'status'">
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
      <td>
        <div class="subheading primary--text">
          {{ props.item.name }}
        </div>
        <div class="grey--text">
          @{{ props.item.venue && props.item.venue.name }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.manager.name }}
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
        <f-btn>
          Manage
        </f-btn>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FBtn from 'fresh-bus/components/ui/FBtn'
import FChip from 'fresh-bus/components/ui/FChip'
import StatusSelect from '~/components/events/StatusSelect'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'
export default {
  components: { FBtn, StatusSelect, FChip },
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
        { text: 'FLEET MEMBER', value: 'name,venue', align: 'left' },
        { text: 'LOCATION', value: 'manager', align: 'left' },
        { text: 'TAGS', sortable: false, value: 'event_tags', align: 'left' },
        { text: 'MANAGE', sortable: false, value: 'manage', align: 'left' }
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
  methods: {
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
      this.$emit('manage-multiple', action, this.selected)
    },
    changeStatus (value, event) {
      this.$emit('change-status', value, event)
    },
    changeStatusMultiple (value) {
      this.$emit('change-status-multiple', value, this.selected)
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
