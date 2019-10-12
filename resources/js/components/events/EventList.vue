<template>
  <v-data-table
    v-model="selected"
    class="elevation-1"
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
          v-if="header.value === 'id'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="idx"
            class="text-xs-left"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              {{ props.item.uuid }}
            </slot>
          </td>
        </slot>
        <slot
          v-else-if="header.value === 'name,venue'"
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
          v-else-if="header.value === 'start_at,end_at'"
          :name="'item-'+header.value"
          :item="props.item"
        >
          <td
            :key="headerIndex"
            class="text-xs-center"
          >
            <slot
              :name="'item-inner-'+header.value"
              :item="props.item"
            >
              <div class="grey--text format-range-date">
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
            class="text-xs-center"
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
                <v-flex
                  v-for="tag in props.item.event_tags"
                  :key="tag.uuid"
                  xs6
                  class="text-xs-left"
                >
                  <f-chip
                    color="secondary"
                  >
                    {{ tag.name }}
                  </f-chip>
                </v-flex>
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
            class="text-xs-center"
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
            class="text-xs-center"
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
            class="justify-center text-xs-center select-td"
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
            class="justify-center text-xs-center"
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
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Status', sortable: true, value: 'status', align: 'center', width: '200' },
        { text: 'Title / Venue', value: 'name,venue', align: 'left', width: '300' },
        { text: 'Date', sortable: true, value: 'start_at,end_at', align: 'center' },
        { text: 'Tags', value: 'event_tags', align: 'center', width: '300' },
        { text: 'Managed By', value: 'manager', align: 'center' },
        { text: 'Customer', value: 'host', align: 'center' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
      ],
      itemActions: [
        { action: 'edit', text: 'Edit' },
        { action: 'delete', text: 'Delete' },
        { action: 'cancel', text: 'Cancel' },
        { action: 'leave', text: 'Leave Event' }
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
    }
  }
}
</script>

<style scoped lang="scss">
  .highlight {
    background: #ffa;
  }
  table.v-table tbody td.select-td{
    padding-top: 5px;
    padding-bottom: 5px;
  }
  .format-range-date{
    min-width: 130px;
  }
  .list-tag-wrap{
    min-width: 180px;
  }
</style>
