<template>
  <v-data-table
    v-model="selected"
    class="event-list-table"
    :headers="headers"
    :items="menus"
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
      <td class="py-4">
        <v-checkbox
          v-model="props.selected"
          primary
          hide-details
        />
      </td>
      <td>
        <div class="subheading primary--text">
          {{ props.item.title }}
        </div>
        <div class="grey--text">
          {{ props.item.description }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.servings }}
        </div>
      </td>
      <td>
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
        { text: 'Item', sortable: true, value: 'title', align: 'left' },
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
</style>
