<template>
  <div class="py-2">
    <div class="text-xs-center my-5">
      <div class="display-1 primary--text">
        {{ formatMoney(gross, { format: '$0,0' }) }}
      </div>
      <div class="font-weight-bold subheading mt-2">
        Gross
      </div>
    </div>
    <div class="text-xs-center my-5">
      <div class="display-1 primary--text">
        {{ formatMoney(net, { format: '$0,0' }) }}
      </div>
      <div class="font-weight-bold subheading mt-2">
        Net
      </div>
    </div>
    <div class="text-xs-center my-5">
      <div class="display-1 primary--text">
        {{ formatMoney(cash, { format: '$0,0' }) }}
      </div>
      <div class="font-weight-bold subheading mt-2">
        Cash
      </div>
    </div>
    <div class="text-xs-center my-5">
      <div class="display-1 primary--text">
        {{ formatMoney(credit, { format: '$0,0' }) }}
      </div>
      <div class="font-weight-bold subheading mt-2">
        Credit
      </div>
    </div>
  </div>
</template>

<script>
import FormatMoney from 'fresh-bus/components/mixins/FormatMoney'
import reduce from 'lodash/reduce'

export default {
  mixins: [FormatMoney],
  props: {
    gross: {
      type: Number,
      required: true
    },
    net: {
      type: Number,
      required: true
    },
    paymentTypeTotals: {
      type: Array,
      required: true
    }
  },
  computed: {
    cash () {
      return this.paymentTypeTotals.find(total => {
        return total.name === 'CASH'
      }).value
    },
    credit () {
      return reduce(this.paymentTypeTotals, (result, value, key) => {
        if (value.name !== 'CASH') {
          result += value.value
        }
        return result
      }, 0)
    }
  }
}
</script>
<style lang="styl" scoped>

</style>
