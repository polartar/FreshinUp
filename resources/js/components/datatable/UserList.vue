<template>
  <div>
    <v-data-table
      v-model="selected"
      class="elevation-1"
      :headers="reactiveHeaders"
      :items="users"
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
        <f-manage-multiple
          v-if="selected.length > 1 && props.header.value === 'manage'"
          :items="statuses"
          item-label="name"
          label="Delete"
          @item="manageMultiple('delete')"
        />
        <f-manage-multiple
          v-if="selected.length > 1 && props.header.value === 'status'"
          :items="statuses"
          item-label="name"
          label="Change status"
          @item="manageMultiple('status', $event)"
        />
        <f-manage-multiple
          v-if="selected.length > 1 && props.header.value === 'level'"
          :items="levels"
          item-label="name"
          label="Change level"
          @item="manageMultiple('level', $event)"
        />
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
          v-for="(header, headerIndex) in reactiveHeaders"
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
            v-else-if="header.value === 'first_name,email,title,company'"
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
                <div class="font-weight-bold mb-1">
                  {{ props.item.name }}<br>
                  {{ props.item.email }}<br>
                  {{ props.item.title }}
                  <span v-if="props.item.company">
                    @ {{ props.item.company.name }}
                  </span>
                </div>
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'joined_at'"
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
                <div class="fresh-bus-admin-user-list__joined_date">
                  {{ formatDate(props.item.joined_at, 'MMM DD, YYYY') }}
                </div>
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="header.value === 'requested_company'"
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
                <div class="font-weight-bold mb-1">
                  {{ props.item.requested_company }}
                </div>
              </slot>
            </td>
          </slot>
          <slot
            v-else-if="['level', 'status', 'manage'].indexOf(header.value) === -1"
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
            v-else-if="header.value === 'level'"
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
                <v-select
                  :items="levels"
                  :value="props.item.level"
                  item-text="name"
                  item-value="id"
                  menu-props="auto"
                  placeholder="Level"
                  hide-details
                  single-line
                  solo
                  @change="changeLevel($event, props.item)"
                />
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
              class="justify-center text-xs-center"
            >
              <slot
                :name="'item-inner-'+header.value"
                :item="props.item"
              >
                <v-select
                  :items="statuses"
                  :value="props.item.status"
                  item-text="name"
                  item-value="id"
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
import UserList from 'fresh-bus/components/datatable/user-list.vue'
import FManageMultiple from '@freshinup/core-ui/src/components/FManageMultiple'

export const DEFAULT_HEADERS = [
  { text: 'Status', sortable: true, value: 'status', align: 'center' },
  { text: 'Name / Email / Title / Company', value: 'first_name,email,title,company', align: 'left' },
  { text: 'Requested company', value: 'requested_company', align: 'left' },
  { text: 'Join Date', value: 'joined_at', align: 'center' },
  { text: 'Level', sortable: true, value: 'level', align: 'center' },
  { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
]

export const DEFAULT_ITEM_ACTIONS = [
  { action: 'view', text: 'View' },
  { action: 'edit', text: 'Edit' },
  { action: 'teams', text: 'Assign Team(s)' },
  { action: 'delete', text: 'Delete' }
]

export default {
  components: { FManageMultiple },
  extends: UserList,
  props: {
    headers: {
      type: Array,
      default: () => (DEFAULT_HEADERS)
    },
    itemActions: {
      type: Array,
      default: () => (DEFAULT_ITEM_ACTIONS)
    }
  },
  data () {
    return {
      selected: [],
      actionBtnTitle: 'Manage'
    }
  },
  computed: {
    reactiveHeaders () {
      return this.selected.length > 0
        ? this._headers.map(header => ({
          ...header,
          sortable: false
        }))
        : this._headers
    }
  },
  methods: {
    manageMultiple (action, value) {
      this.$emit('manage-multiple-' + action, this.selected, value)
      this.$emit('manage-multiple', action, this.selected, value)
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
</style>
