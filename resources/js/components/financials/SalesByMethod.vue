<template>
  <div class="pa-4">
    <doughnut
      :chart-data="chartData"
      :options="options"
    />
  </div>
</template>

<script>
import Doughnut from '~/components/charts/Doughnut.vue'
import reduce from 'lodash/reduce'

export default {
  components: {
    Doughnut
  },
  props: {
    paymentTypeTotals: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      options: {
        cutoutPercentage: 70,
        legend: {
          display: false
        }
      },
      colors: [
        this.$vuetify.theme.primary,
        this.$vuetify.theme.warning,
        this.$vuetify.theme.secondary,
        this.$vuetify.theme.info
      ]
    }
  },
  computed: {
    values () {
      return this.paymentTypeTotals.map(value => value.value)
    },
    total () {
      return reduce(this.values, (result, value, key) => {
        result += value
        return result
      }, 0)
    },
    labels () {
      return this.paymentTypeTotals.map(value => value.name + ' ' + this.getPercentageString(value.value))
    },
    chartData () {
      return {
        labels: this.labels,
        datasets: [{
          data: this.values,
          backgroundColor: this.colors,
          borderWidth: 0
        }]
      }
    }
  },
  methods: {
    getPercentageString (value) {
      return `${Math.round((this.total !== 0 ? value / this.total : 0) * 100)}%`
    }
  }
}
</script>

<style>
  .circle {
    width: 20px;
    height: 20px;
    display: inline-block;
    border-radius: 100%;
    vertical-align: middle;
  }
</style>
