<template>
  <v-data-table
    class="elevation-1"
    :headers="headers"
    :items="transactions"
    :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
    :pagination.sync="pagination"
    :loading="isLoading"
    :total-items="totalItems"
    disable-initial-sort
  >
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
        v-for="(parameter, index) in dataVisibilityComputed"
        :key="index"
        class="text-xs-left"
      >
        <div v-if="parameter === 'event_location'">
          <title-link
            :value="props.item.event.name"
            :to="viewDetails(props.item.uuid)"
            color="primary"
          />
          <br>
          {{ formatEventLocation(props.item.event.location) }}
        </div>
        <div v-else-if="parameter === 'square_created_at' || parameter === 'square_updated_at'">
          {{ formatTransactionDate(props.item[parameter]) }}
        </div>
        <div v-else-if="parameter === 'total_money' || parameter === 'total_tax_money' || parameter === 'total_discount_money' || parameter === 'total_service_charge_money'">
          {{ formatMoney(props.item[parameter], { format: '$0,0.00', precision: 4 }) }}
        </div>
        <div v-else-if="parameter === 'items'">
          {{ formatItems(props.item[parameter]) }}
        </div>
        <div v-else-if="parameter === 'event_tags'">
          {{ formatEventTags(props.item.event.event_tags) }}
        </div>
        <div v-else-if="parameter === 'customer' || parameter === 'store'">
          {{ props.item[parameter].name }}
        </div>
        <div v-else-if="parameter === 'host'">
          {{ props.item.event.host.name }}
        </div>
        <div v-else-if="parameter === 'supplier'">
          {{ props.item.store.supplier.name }}
        </div>
        <div v-else-if="parameter === 'store_square_id'">
          {{ props.item.store.square_id }}
        </div>
        <div v-else-if="parameter === 'customer_square_id'">
          {{ props.item.customer.square_id }}
        </div>
        <div v-else-if="parameter === 'customer_reference_id'">
          {{ props.item.customer.reference_id }}
        </div>
        <div v-else>
          {{ props.item[parameter] }}
        </div>
      </td>
      <td class="text-xs-center">
        <v-btn
          color="primary"
          dark
          :to="viewDetails(props.item.uuid)"
        >
          View details
        </v-btn>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import TitleLink from 'fresh-bus/components/TitleLink'
import Pagination from 'fresh-bus/components/mixins/Pagination'
import reduce from 'lodash/reduce'
import FormatMoney from '@freshinup/core-ui/src/mixins/FormatMoney'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'

export default {
  components: {
    TitleLink
  },
  mixins: [Pagination, FormatMoney, FormatDate],
  props: {
    transactions: {
      type: Array,
      required: true
    },
    dataVisibility: {
      type: Array,
      default: () => [
        'event_location',
        'square_created_at',
        'items',
        'total_money',
        'total_tax_money',
        'total_discount_money'
      ]
    }
  },
  data () {
    return {
      parameters: [
        { name: 'event_location', label: 'Event / Venue / Location' },
        { name: 'square_created_at', label: 'Creation Date' },
        { name: 'square_updated_at', label: 'Update Date' },
        { name: 'total_money', label: 'Total' },
        { name: 'total_tax_money', label: 'Tax Total' },
        { name: 'total_discount_money', label: 'Total Discount' },
        { name: 'total_service_charge_money', label: 'Total Service Charge' },
        { name: 'items', label: 'Items' },
        { name: 'event_tags', label: 'Event Tags' },
        { name: 'square_id', label: 'Square ID' },
        { name: 'store', label: 'Fleet member' },
        { name: 'store_square_id', label: 'Fleet Member Square ID' },
        { name: 'host', label: 'Customer Company' },
        { name: 'supplier', label: 'Supplier' },
        { name: 'customer', label: 'Customer name' },
        { name: 'customer_square_id', label: 'Customer Square ID' },
        { name: 'customer_reference_id', label: 'Customer Reference ID' }
      ]
    }
  },
  computed: {
    headers () {
      let headerObjects = reduce(this.dataVisibilityComputed, (result, value, key) => {
        let headerObject = this.parameters.find(item => item.name === value)
        result.push({
          text: headerObject.label,
          sortable: false,
          value: headerObject.name,
          align: 'left',
          class: 'font-weight-bold'
        })
        return result
      }, [])
      headerObjects.push({
        sortable: false,
        value: 'action'
      })
      return headerObjects
    },
    dataVisibilityComputed () {
      return this.dataVisibility
    }
  },
  methods: {
    formatTransactionDate (date) {
      return this.formatDate(date, 'MMM DD, YYYY - hh:mma')
    },
    viewDetails (uuid) {
      return '/admin/financials/transactions/' + uuid
    },
    formatItems (array) {
      return reduce(array, (result, value, key) => {
        if (result !== '') {
          result += ', '
        }
        result += value.quantity + ' ' + value.name
        return result
      }, '')
    },
    formatEventTags (array) {
      return reduce(array, (result, value, key) => {
        if (result !== '') {
          result += ', '
        }
        result += value.name
        return result
      }, '')
    },
    formatEventLocation (location) {
      const venue = location && location.venue
      return (venue ? venue.name + ' / ' : '') + (location ? location.name : '')
    }
  }
}
</script>
