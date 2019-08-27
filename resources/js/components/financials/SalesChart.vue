<template>
  <div>
    <div class="font-weight-bold subheading pa-2">
      {{ title }}
    </div>
    <div class="py-2">
      <lines
        :chart-data="chartData"
        :options="options"
      />
    </div>
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
    title: {
      type: String,
      default: 'Total Sales / Time'
    },
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
                return this.formatMoney(value, { format: '$0,0' })
              }
            }
          }]
        },
        tooltips: {
          callbacks: {
            label: (tooltipItem, data) => {
              return this.formatMoney(tooltipItem.yLabel, { format: '$0,0' })
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
