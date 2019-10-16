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
      <template
        v-for="(header, headerIndex) in headers"
      >
        <slot
          v-if="header.value === 'name,venue'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="text-xs-left"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <div class="subheading primary--text">
                {{ props.item.name }}
              </div>
              <div class="grey--text">
                @{{ props.item.venue && props.item.venue.name }}
              </div>
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'start_at'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="text-xs-left"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <div class="grey--text">
                {{ formatRangeDate(props.item.start_at, props.item.end_at) }}
              </div>
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'event_tags'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="text-xs-left tag-td"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <v-layout
                row
                wrap
                class="list-tag-wrap"
              >
                <f-chip
                  v-for="tag in props.item.event_tags"
                  :key="tag.uuid"
                  color="secondary"
                >
                  {{ tag.name }}
                </f-chip>
              </v-layout>
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'manager'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="text-xs-left"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <div class="grey--text">
                {{ props.item.manager.name }}
              </div>
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'host'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="text-xs-left"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <div class="grey--text">
                {{ props.item.host.name }}
              </div>
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="['status', 'manage'].indexOf(header.value) === -1"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            :class="'text-xs-' + header.align"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              {{ props.item[header.value] }}
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'status'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="justify-center text-xs-left select-td"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <status-select
                v-model="props.item.status"
                :options="statuses"
                @input="changeStatus($event, props.item)"
              />
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'manage'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="justify-center text-xs-left"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <f-btn-menu
                :items="itemActions"
                item-label="text"
                @item="manage($event, props.item)"
              >
                Manage
              </f-btn-menu>
            </slot>
          </td>
        </slot>
      </template>
    </template>
  </v-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
import FChip from 'fresh-bus/components/ui/FChip'
import StatusSelect from '~/components/events/StatusSelect'
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
        { text: 'Status', sortable: true, value: 'status', align: 'left' },
        { text: 'Title / Venue', value: 'name,venue', align: 'left' },
        { text: 'Date', sortable: true, value: 'start_at', align: 'left' },
        { text: 'Tags', value: 'event_tags', align: 'left' },
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
