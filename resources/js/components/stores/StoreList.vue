<template>
  <div>
    <v-data-table
      v-model="selected"
      class="elevation-1"
      :headers="headers"
      :items="stores"
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
                v-for="(item, index) in selectedStoreActions"
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
                @click="changeStatusMultiple(item.value)"
              >
                <v-list-tile-title>
                  {{ item.text }}
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
        <td class="justify-center text-xs-center select-td">
          <v-select
            :items="statuses"
            :value="props.item.status"
            item-text="text"
            item-value="value"
            menu-props="auto"
            label="Status"
            hide-details
            single-line
            solo
            @change="changeStatus($event, props.item)"
          />
        </td>
        <td>
          {{ props.item.name }}
        </td>
        <td class="justify-center text-xs-center">
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
  </div>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
export default {
  components: { FBtnMenu },
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
      default: () => [] // { text: '', value: 0 }
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Status', sortable: false, value: 'status' },
        { text: 'Name', value: 'name' },
        { text: 'Manage', sortable: false, value: 'manage' }
      ],
      itemActions: [
        { action: 'view', text: 'View / Edit' },
        { action: 'delete', text: 'Delete' }
      ],
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
