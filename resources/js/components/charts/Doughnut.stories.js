import { storiesOf } from '@storybook/vue'

// Components
import Doughnut from './Doughnut.vue'

const dataSet = [10, 20, 30]
const labels = ['Red', 'Green', 'Blue']
const colors = ['red', 'green', 'blue']

storiesOf('FoodFleet|charts/Doughnut', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => {
    return {
      components: { Doughnut },
      data () {
        return {
          dataCollection: {
            datasets: [{
              data: dataSet,
              borderWidth: 0
            }],

            labels: labels
          }
        }
      },
      template: `
          <v-container>
          <v-layout row>
            <v-flex sm6>
              <v-card class="px-3 py-3">
                <doughnut
                  :chart-data="dataCollection"
                />
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      `
    }
  }).add('with custom styling', () => {
    return {
      components: { Doughnut },
      data () {
        return {
          dataCollection: {
            datasets: [{
              data: dataSet,
              backgroundColor: colors,
              borderWidth: 0
            }],

            labels: labels
          },
          options: {
            legend: {
              display: true
            },
            cutoutPercentage: 70
          }
        }
      },
      template: `
        <v-container>
          <v-layout row>
            <v-flex sm6>
              <v-card class="px-3 py-3">
                <doughnut
                   :chart-data="dataCollection"
                   :options="options"
                />
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      `
    }
  }).add('without labels', () => {
    return {
      components: { Doughnut },
      data () {
        return {
          dataCollection: {
            datasets: [{
              data: dataSet,
              backgroundColor: colors,
              borderWidth: 0
            }],

            labels: labels
          },
          options: {
            legend: {
              display: false
            },
            cutoutPercentage: 70
          }
        }
      },
      template: `
        <v-container>
          <v-layout row>
            <v-flex sm6>
              <v-card class="px-3 py-3">
                <doughnut
                   :chart-data="dataCollection"
                   :options="options"
                />
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      `
    }
  })
