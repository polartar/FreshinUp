<template>
  <v-data-table
    v-model="selected"
    class="event-list-table"
    :headers="headers"
    :items="menus"
    :hide-headers="$vuetify.breakpoint.xsOnly"
    :hide-actions="$vuetify.breakpoint.xsOnly"
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
      <span
        v-else
        class="font-weight-bold"
      >
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
      <td
        v-if="!$vuetify.breakpoint.xsOnly"
        class="py-4"
      >
        <v-checkbox
          v-model="props.selected"
          primary
          hide-details
        />
      </td>
      <td class="full-width">
        <div class="subheading primary--text">
          {{ props.item.item }}
        </div>
        <div
          :class="`grey--text ${ $vuetify.breakpoint.xsOnly ? 'pt-2': '' }`"
        >
          {{ props.item.description }}
        </div>
      </td>
      <td>
        <div
          v-if="$vuetify.breakpoint.xsOnly"
          class="font-weight-bold"
        >
          Servings
        </div>
        <div class="grey--text">
          {{ props.item.servings }}
        </div>
      </td>
      <td>
        <div
          v-if="$vuetify.breakpoint.xsOnly"
          class="font-weight-bold"
        >
          Cost
        </div>
        <div class="grey--text">
          {{ formatMoney(props.item.cost) }}
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
import FormatMoney from 'fresh-bus/components/mixins/FormatMoney'
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
export default {
  components: {
    FBtnMenu
  },
  mixins: [
    Pagination,
    FormatMoney
  ],
  props: {
    menus: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Item', sortable: true, value: 'item', align: 'left' },
        { text: 'Servings', sortable: true, value: 'servings', align: 'left' },
        { text: 'Cost', value: 'cost', align: 'left' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
      ]
    }
  },
  computed: {
    itemActions () {
      let actions = [
        { action: 'edit', text: 'Edit' },
        { action: 'delete', text: 'Delete' }
      ]
      return actions
    },
    selectedActions () {
      if (!this.selected.length) return []
      return [ { action: 'delete', text: 'Delete' } ]
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
    }
  }
}
</script>

<style lang="styl" scoped>
@media (max-width: 600px) {
  .full-width{
    width: 100%;
  }
  /deep/ table.v-table tbody tr{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 10px 0;
  }
  /deep/ table.v-table tbody tr td{
    padding: 10px 0;
    box-sizing: content-box;
    flex-shrink: 1;
    flex-grow: 0;
    height: auto;
  }
}
</style>
