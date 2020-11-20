<template>
  <div>
    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items="items"
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
        <td class="text-xs-left">
          {{ props.item.category.name }}
        </td>
        <td class="text-xs-center">
          {{ props.item.name }}
        </td>
        <td class="text-xs-center">
          {{ props.item.quantity }}
        </td>
        <td class="text-xs-center">
          {{ formatMoney(props.item.total_money, { format: '$0,0.00', precision: 4 }) }}
        </td>
        <td class="text-xs-center">
          {{ formatMoney(props.item.total_discount_money, { format: '$0,0.00', precision: 4 }) }}
        </td>
        <td class="text-xs-center">
          {{ formatMoney(props.item.total_tax_money, { format: '$0,0.00', precision: 4 }) }}
        </td>
        <td class="text-xs-center">
          {{ formatMoney(props.item.total_money - props.item.total_tax_money, { format: '$0,0.00', precision: 4 }) }}
        </td>
      </template>
      <template
        slot="footer"
      >
        <td class="text-xs-left font-weight-bold">
          Totals:
        </td>
        <td />
        <td />
        <td class="text-xs-center font-weight-bold">
          {{ formatMoney(sumGrossSales, { format: '$0,0.00', precision: 4 }) }}
        </td>
        <td class="text-xs-center font-weight-bold">
          {{ formatMoney(sumDiscount, { format: '$0,0.00', precision: 4 }) }}
        </td>
        <td class="text-xs-center font-weight-bold">
          {{ formatMoney(sumTaxes, { format: '$0,0.00', precision: 4 }) }}
        </td>
        <td class="text-xs-center font-weight-bold">
          {{ formatMoney(sumNet, { format: '$0,0.00', precision: 4 }) }}
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import reduce from 'lodash/reduce'
import FormatMoney from '@freshinup/core-ui/src/mixins/FormatMoney'

export default {
  mixins: [FormatMoney],
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      trueVar: true,
      headers: [
        { text: 'Category', sortable: false, value: 'category', align: 'left', class: 'font-weight-bold' },
        { text: 'Item', sortable: false, value: 'name', align: 'center', class: 'font-weight-bold' },
        { text: 'Quantity', sortable: false, value: 'quantity', align: 'center', class: 'font-weight-bold' },
        { text: 'Gross Sales', sortable: false, value: 'total_money', align: 'center', class: 'font-weight-bold' },
        { text: 'Discount', sortable: false, value: 'total_discount_money', align: 'center', class: 'font-weight-bold' },
        { text: 'Taxes', sortable: false, value: 'total_tax_money', align: 'center', class: 'font-weight-bold' },
        { text: 'Net Sales', sortable: false, value: 'total_net_sales', align: 'center', class: 'font-weight-bold' }
      ]
    }
  },
  computed: {
    sumGrossSales () {
      return this.sum('total_money')
    },
    sumDiscount () {
      return this.sum('total_discount_money')
    },
    sumTaxes () {
      return this.sum('total_tax_money')
    },
    sumNet () {
      return this.sumGrossSales - this.sumTaxes
    }
  },
  methods: {
    sum (property) {
      return reduce(this.items, (result, value, key) => {
        return result + value[property]
      }, 0)
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .v-datatable__actions
  {
    display: none !important;
  }
</style>
