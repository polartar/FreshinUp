<template>
  <v-data-table
    class="document-list-table"
    :headers="headers"
    :items="documents"
    :loading="isLoading"
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
      <td class="change-status-btn">
        <status-select
          v-model="props.item.status_id"
          :options="statuses"
          @input="changeStatus($event, props.item)"
        />
      </td>
      <td class="event-doc">
        <div>{{ props.item.title }}</div>
        <div>{{ props.item.owner }}</div>
      </td>
      <td class="text-xs-left date">
        {{ formatDate(props.item.updated_at) }}
      </td>
      <td class="text-xs-right">
        <v-btn
          class="primary view-details-btn"
          @click="viewDetails"
        >
          View Details
        </v-btn>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import StatusSelect from '~/components/events/StatusSelect'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
export default {
  components: { StatusSelect },
  mixins: [ FormatDate ],
  props: {
    documents: {
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
      headers: [
        { text: 'ITEMS', sortable: true, value: 'items', align: 'left' },
        { text: '', sortable: false, value: 'document', align: 'left' },
        { text: 'LAST UPDATED', sortable: true, value: 'last_updated', align: 'left' },
        { text: '', sortable: false, value: 'manage', align: 'left' }
      ]
    }
  },
  methods: {
    changeStatus (value, event) {
      this.$emit('change-status', value, event)
    },
    viewDetails (value) {
      this.$emit('view-details', value)
    }
  }
}
</script>

<style lang="styl" scoped>
  /deep/ table.v-table tbody td {
    height: 80px;
    padding: 0 10px;
    margin: 0;
  }
  .event-doc div:first-child {
    font-size: 17px;
    color: #508c85;
  }
  .event-doc div:nth-child(2) {
    color: #a0a9ba;
    max-width: 150px;
  }
  /deep/ table.v-table thead th {
    font-weight: bolder;
  }
  .change-status-btn {
    width: 200px;
  }
  .document-list-table {
    max-width: 740px;
    max-height: 748px;
  }
  .view-details-btn {
    width: 115px;
    height: 36px;
    font-size: 12px;
    line-height: 1.33;
    text-transform: capitalize;
  }
  .date {
    min-width: 190px;
  }
</style>
