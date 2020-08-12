<template>
  <v-data-table
    v-model="selected"
    class="store-list-table"
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
      <td class="select-td">
        <f-btn-status
          :value="props.item.status"
          label-prop="name"
          :items="statuses"
        />
      </td>
      <td>
        <div class="subheading primary--text">
          {{ props.item.name }}
        </div>
        <div class="grey--text">
          {{ props.item.type && props.item.type.name }}
        </div>
      </td>
      <td class="tag-td">
        <f-chip
          v-for="tag in props.item.store_tags"
          :key="tag.uuid"
          color="secondary"
        >
          {{ tag.name }}
        </f-chip>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.owner && props.item.owner.name }}
        </div>
        <div class="grey--text">
          {{ props.item.owner && props.item.owner.company }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.location && props.item.location.name }}
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
import FChip from 'fresh-bus/components/ui/FChip'
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'
import FBtnStatus from 'fresh-bus/components/ui/FBtnStatus'
export default {
  components: { FChip, FBtnMenu, FBtnStatus },
  mixins: [
    Pagination,
    FormatRangeDate
  ],
  props: {
    stores: {
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
        { text: 'STATUS', sortable: true, value: 'status', align: 'left' },
        { text: 'NAME / TYPE', value: 'name,type', align: 'left' },
        { text: 'TAGS', sortable: false, value: 'store_tags', align: 'left' },
        { text: 'OWNER', value: 'owner', align: 'left' },
        { text: 'LOCATION', value: 'location', align: 'left' },
        { text: 'MANAGE', sortable: false, value: 'manage', align: 'left' }
      ]
    }
  },
  computed: {
    itemActions () {
      let actions = [
        { action: 'view-details', text: 'View details' }
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
    manage (item, store) {
      this.$emit('manage-' + item.action, store)
    },
    manageMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
    },
    generateActions (actions) {
      if (!(actions instanceof Array)) {
        actions = []
      }
      actions.push({ action: 'unassign', text: 'Unassign' })
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
