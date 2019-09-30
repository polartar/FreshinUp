<template>
  <div>
    <v-data-table
      v-model="selected"
      class="elevation-1"
      :headers="headers"
      :items="fleetMembers"
      :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
      :pagination.sync="pagination"
      :loading="isLoading"
      :total-items="totalItems"
      item-key="id"
      hide-actions
      select-all
      must-sort
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
                {{ props.item.id }}
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'name,type'"
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
                <div class="subheading">
                  {{ props.item.name }}
                </div>
                {{ props.item.type }}
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'tags'"
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
                <div>{{ props.item.tags.map(item => item.name).join(',') }}</div>
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'events'"
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
                <div>{{ props.item.events.length }}</div>
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'events'"
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
                <div>{{ props.item.events.length }}</div>
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'address'"
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
                <div>{{ props.item.address.street }}, {{ props.item.address.city }}</div>
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
    <v-layout
      align-center
    >
      <v-flex
        grow
        justify-center
      >
        <v-layout
          justify-center
        >
          <v-pagination
            :value="page"
            :length="pagination.totalPages"
            :disabled="isLoading"
            :total-visible="6"
            @input="onPageChange"
          />
        </v-layout>
      </v-flex>
      <v-flex
        shrink
      >
        <v-select
          :value="rowsPerPage"
          :items="rowsPerPageItems"
          label="Results Per Page"
          @input="onRowsPerPageChange"
        />
      </v-flex>
    </v-layout>
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
    fleetMembers: {
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
        { text: 'Name / Category', value: 'name,type', align: 'left', width: '300' },
        { text: 'Tags', value: 'tags', align: 'center' },
        { text: 'Scheduled events', sortable: true, value: 'events', align: 'center' },
        { text: 'Hometown', value: 'address', align: 'center' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
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
  methods: {
    onPageChange (value) {
      this.pagination = { ...this.pagination, page: value }
    },
    onRowsPerPageChange (value) {
      this.pagination = { ...this.pagination, rowsPerPage: value }
    },
    manage (item, doc) {
      this.$emit('manage-' + item.action, doc)
      this.$emit('manage', item.action, doc)
    },
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
      this.$emit('manage-multiple', action, this.selected)
    },
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
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
  .doc-type-icon{
    text-align: center;
    font-size: 24px;
  }
</style>
