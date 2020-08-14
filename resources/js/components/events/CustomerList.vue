<template>
  <v-data-table
    :headers="headers"
    :items="customers"
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
        <status-select
          v-model="props.item.status"
          :items="statuses"
          @input="changeStatus(props.item.status, props.item)"
        />
      </td>
      <td class="py-3">
        {{ formatDate(props.item.updated_at) }}
      </td>
      <td class="text-xs-left py-3">
        {{ formatDate(props.item.created_at) }}
      </td>
      <td class="text-xs-left py-3">
        <v-btn
          class="primary ml-0"
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
import StatusSelect from '~/components/events/StatusSelect.vue'

export default {
  components: { StatusSelect },
  mixins: [ FormatDate ],
  props: {
    customers: {
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
        { text: 'CONTRACT STATUS', sortable: true, value: 'status', align: 'left' },
        { text: 'LAST UPDATED ON', sortable: false, value: 'updated_at', align: 'left' },
        { text: 'SUBMITTED ON', sortable: true, value: 'created_at', align: 'left' },
        { text: 'MANAGE', sortable: false, value: 'manage', align: 'left' }
      ]
    }
  },
  methods: {
    changeStatus (value, customer) {
      this.$emit('change-status', value, customer)
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
