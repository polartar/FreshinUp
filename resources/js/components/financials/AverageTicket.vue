<template>
  <div class="py-2">
    <div class="text-xs-center my-5">
      <div class="display-1 primary--text">
        {{ formatMoney(averageTicket, { format: '$0,0.00', precision: 4 }) }}
      </div>
      <div class="font-weight-bold subheading mt-2">
        Per Transaction
      </div>
    </div>
  </div>
</template>

<script>
import FormatMoney from '@freshinup/core-ui/src/mixins/FormatMoney'
export default {
  mixins: [FormatMoney],
  props: {
    averageTicket: {
      type: Number,
      required: true
    }
  }
}
</script>
<style lang="styl" scoped>
</style>
