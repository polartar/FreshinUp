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
              @click="assignMultiple(item.action)"
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
      <td>
        <div class="subheading primary--text">
          {{ props.item.name }}
        </div>
        <div class="grey--text">
          @{{ props.item.type && props.item.type.name }}
        </div>
      </td>
      <td>
        <div class="grey--text">
          {{ props.item.location.name }}
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
      <td v-if="props.item.assigned">
        <f-btn>
          Assign
        </f-btn>
      </td>
      <td v-else>
        <f-btn
          color="#508c85"
          class="white--text"
          @click="assign('assign', props.item)"
        >
          Assigned
        </f-btn>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FBtn from 'fresh-bus/components/ui/FBtn'
import FChip from 'fresh-bus/components/ui/FChip'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'
export default {
  components: { FBtn, FChip },
  mixins: [
    Pagination,
    FormatRangeDate
  ],
  props: {
    stores: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'FLEET MEMBER', value: 'name,type', align: 'left' },
        { text: 'LOCATION', value: 'location', align: 'left' },
        { text: 'TAGS', sortable: false, value: 'store_tags', align: 'left' },
        { text: 'MANAGE', sortable: false, value: 'manage', align: 'left' }
      ]
    }
  },
  computed: {
    selectedActions () {
      if (!this.selected.length) return []
      return this.generateActions()
    }
  },
  methods: {
    assign (action, store) {
      this.$emit('manage-' + action, store)
    },
    assignMultiple (action) {
      this.$emit('manage-multiple-' + action, this.selected)
    },
    generateActions (actions) {
      if (!(actions instanceof Array)) {
        actions = []
      }
      actions.push({ action: 'assign', text: 'Assign' })
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
