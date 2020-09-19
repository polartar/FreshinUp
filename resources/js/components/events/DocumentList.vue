<template>
  <!--  TODO: use FDataTable instead-->
  <v-data-table
    :headers="headers"
    :items="documents"
    hide-actions
    disable-initial-sort
  >
    <template
      slot="headerCell"
      slot-scope="props"
    >
      {{ props.header.text }}
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
      <td class="py-3">
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
      <td class="py-3">
        <div class="primary--text font-weight-bold title">
          {{ props.item.title }}
        </div>
        <div class="gray--text">
          {{ props.item.owner.first_name + props.item.owner.last_name }}
        </div>
      </td>
      <td class="text-xs-left py-3">
        {{ formatDate(props.item.updated_at) }}
      </td>
      <td class="text-xs-right py-3">
        <v-btn
          class="primary"
          @click="viewDetails(props.item.uuid)"
        >
          View Details
        </v-btn>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export default {
  mixins: [ FormatDate ],
  props: {
    documents: {
      type: Array,
      default: () => []
    },
    // ** these are document statuses **
    statuses: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      headers: [
        { text: 'ITEMS', sortable: true, value: 'title', align: 'left' },
        { text: 'NAME', sortable: false, value: 'document', align: 'left' },
        { text: 'LAST UPDATED', sortable: true, value: 'last_updated', align: 'left' },
        { text: 'MANAGE', sortable: false, value: 'manage', align: 'left' }
      ]
    }
  },
  methods: {
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    },
    viewDetails (value) {
      this.$emit('view-details', value)
    }
  }
}
</script>

<style lang="styl" scoped>
  /deep/ table.v-table thead th {
    font-weight: bolder;
  }
  /deep/ table.v-table {
    border-top: 1px solid lightgray;
    border-bottom: 1px solid lightgray;
  }
</style>
