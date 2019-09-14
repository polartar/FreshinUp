<template>
  <v-container>
    <v-layout
      row
      wrap
      align-center
    >
      <v-flex
        xs12
        sm6
        :class="{'mb-4': $vuetify.breakpoint.xs}"
      >
        <doughnut
          :chart-data="chartData"
          :options="options"
        />
      </v-flex>
      <v-flex
        xs12
        sm6
        :class="{'pl-4': $vuetify.breakpoint.smAndUp}"
      >
        <v-layout
          v-for="(label, i) in labels"
          :key="i"
          d-flex
          mb-3
          row
          align-left
        >
          <v-flex>
            <v-icon
              small
              :color="colors[i]"
              class="mr-2"
            >
              fa-circle
            </v-icon>
            <span class="body-2 text-lowercase">{{ label }}</span>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>
  </v-container>
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
