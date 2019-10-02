<template>
  <div class="pa-4">
    <lines
      :chart-data="chartData"
      :options="options"
    />
  </div>
</template>

<script>
import Lines from '../charts/Lines'
import FormatMoney from 'fresh-bus/components/mixins/FormatMoney'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export default {
  components: {
    Lines
  },
  mixins: [FormatDate, FormatMoney],
  props: {
    sales: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      options: {
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            ticks: {
              callback: (value) => {
                return value ? this.formatMoney(Math.round(value), { format: '$0,0.00', precision: 4 }) : '$0.00'
              }
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: (tooltipItem, data) => {
              return tooltipItem.yLabel ? this.formatMoney(tooltipItem.yLabel, { format: '$0,0.00', precision: 4 }) : '$0.00'
            }
          }
        }
      }
    }
  },
  computed: {
    values () {
      return this.sales.map(value => value.value)
    },
    labels () {
      return this.sales.map(value => this.formatDate(value.date, 'MMM DD'))
    },
    chartData () {
      return {
        labels: this.labels,
        datasets: [
          {
            borderColor: '#15b6a9',
            fill: false,
            data: this.values,
            pointRadius: 5
          }
        ]
      }
    }
  }
}
</script>
<style lang="styl" scoped>

</style>
