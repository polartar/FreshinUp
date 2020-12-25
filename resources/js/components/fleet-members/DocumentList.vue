<template>
  <!--  TODO: Replace with FDataTable -->
  <v-card>
    <v-card-title class="px-3">
      <v-layout
        align-center
        justify-center
        row
        fill-height
      >
        <v-flex>
          <h3 class="grey--text">
            Fleet Member Documents
          </h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            v-model="newDocumentDialog"
            max-width="400"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                dark
                @click="newDocumentDialog = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add New Document
              </v-btn>
            </template>
            <v-card>
              <v-divider />
              <v-card-text class="grey--text">
                <FleetMemberDocuemnt />
              </v-card-text>
              <v-divider />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <hr>

    <v-card-text class="ma-2">
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        :sortables="sortables"
        without-expansion
        icon="search"
        color="transparent"
        class="filter-transparent"
        @runFilter="$emit('runFilter', $event)"
      />

      <v-data-table
        v-model="selected"
        class="elevation-1"
        :headers="headers"
        :items="docs"
        :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
        :loading="isLoading"
        :total-items="totalItems"
        v-bind="$attrs"
        item-key="uuid"
        select-all
        disable-initial-sort
        v-on="$listeners"
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
              >Manage Multiple</v-btn>
              <v-list>
                <v-list-tile
                  v-for="(item, index) in selectedDocActions"
                  :key="index"
                  @click="manageMultiple(item.action)"
                >
                  <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
          </span>
          <span v-else-if="selected.length > 1 && props.header.value === 'status'">
            <v-menu offset-y>
              <v-btn
                slot="activator"
                light
              >Change Statuses</v-btn>
              <v-list>
                <v-list-tile
                  v-for="(item, index) in statuses"
                  :key="index"
                  @click="changeStatusMultiple(item.value)"
                >
                  <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
          </span>

          <span v-else>{{ props.header.text }}</span>
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
          <template v-for="(header, headerIndex) in headers">
            <slot
              v-if="header.value === 'id'"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="idx"
                class="text-xs-left"
              >
                <slot
                  :name="'item-inner-' + header.value"
                  :item="props.item"
                >
                  {{ props.item.id }}
                </slot>
              </td>
            </slot>
            <slot
              v-else-if="header.value === 'title,owner'"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="headerIndex"
                class="text-xs-left"
              >
                <slot
                  :name="'item-inner-' + header.value"
                  :item="props.item"
                >
                  <div class="subheading">
                    {{ props.item.title }}
                  </div>
                  {{ props.item.owner && props.item.owner.name }}
                </slot>
              </td>
            </slot>
            <slot
              v-else-if="header.value === 'created_at'"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="headerIndex"
                class="text-xs-center"
              >
                <slot
                  :name="'item-inner-' + header.value"
                  :item="props.item"
                >
                  <div
                    class="fresh-bus-admin-user-list__joined_date"
                  >
                    {{ formatDate(props.item.created_at, "MMM DD, YYYY") }}
                  </div>
                </slot>
              </td>
            </slot>
            <slot
              v-else-if="header.value === 'expiration_at'"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="headerIndex"
                class="text-xs-center"
              >
                <slot
                  :name="'item-inner-' + header.value"
                  :item="props.item"
                >
                  <div
                    class="fresh-bus-admin-user-list__joined_date"
                  >
                    {{ formatDate(props.item.expiration_at, "MMM DD, YYYY") }}
                  </div>
                </slot>
              </td>
            </slot>
            <slot
              v-else-if="['status_id', 'manage'].indexOf(header.value) === -1"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="headerIndex"
                :class="'text-xs-' + header.align"
              >
                <slot
                  :name="'item-inner-' + header.value"
                  :item="props.item"
                >
                  {{ props.item[header.value] }}
                </slot>
              </td>
            </slot>
            <slot
              v-else-if="header.value === 'status_id'"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="headerIndex"
                class="justify-center select-td"
              >
                <status-select
                  v-model="props.item.status_id"
                  :options="statuses"
                  @input="changeStatus($event, props.item)"
                />
              </td>
            </slot>
            <slot
              v-else-if="header.value === 'manage'"
              :name="'item-' + header.value"
              :item="props.item"
            >
              <td
                :key="headerIndex"
                class="justify-center text-xs-center"
              >
                <slot
                  :name="'item-inner-' + header.value"
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
    </v-card-text>
  </v-card>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
import StatusSelect from '~/components/docs/StatusSelect'
import FilterSorter from '~/components/docs/FilterSorter.vue'
import FleetMemberDocuemnt from '~/components/docs/FleetMemberDocs.vue'

export const HEADERS = [
  {
    text: 'Status',
    sortable: true,
    value: 'status_id',
    align: 'left',
    width: '200'
  },
  { text: 'Document', value: 'title,owner', align: 'left', width: '300' },
  { text: 'Submitted on', value: 'created_at', align: 'center' },
  {
    text: 'Expiration date',
    sortable: true,
    value: 'expiration_at',
    align: 'center'
  },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]
export const ITEM_ACTIONS = [
  { action: 'view', text: 'View / Edit' }
  // disabled for now { action: 'delete', text: 'Delete' }
]
export default {
  components: { FBtnMenu, StatusSelect, FilterSorter },
  mixins: [Pagination, FormatDate],
  props: {
    docs: {
      type: Array,
      default: () => []
    },
    totalItems:{
      type:Array,
      default:()=>[]
    },
    statuses: {
      type: Array,
      default: () => [] // { text: '', value: 0 }
    },
    types: {
      type: Array,
      default: () => []
    },
    sortables: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: HEADERS,
      itemActions: ITEM_ACTIONS,
      actionBtnTitle: 'Manage',
      newDocumentDialog: false
    }
  },
  computed: {
    selectedDocActions () {
      if (!this.selected.length) return []
      let actions = []
      actions.push({ action: 'delete', text: 'Delete' })
      return actions
    }
  },
  methods: {
    manage (item, doc) {
      this.$emit('manage-' + item.action, doc)
      this.$emit('manage', item.action, doc)
    },
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
      this.$emit('manage-multiple', action, this.selected)
      this.selected = []
    },
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    },
    changeStatusMultiple (value) {
      this.$emit('change-status-multiple', value, this.selected)
    },
    sort (item) {
      this.$emit('sort', item.id)
    },
    searchInput (val) {
      this.$emit('searchInput', val)
    }
  }
}
</script>

<style scoped>
  /deep/ .filter-sorter-container .v-text-field .v-input__slot {
    border: 1px solid #ccc;
    margin-bottom: 0;
  }
  /deep/ .filter-sorter-container .v-text-field__details {
    display: none;
  }
  /deep/ .filter-sorter-container .v-divider {
    display: none;
  }
  /deep/ .filter-sorter-expanded-layout {
    display: none;
  }

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
