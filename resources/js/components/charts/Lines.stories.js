import { storiesOf } from '@storybook/vue'

// Components
import Lines from './Lines.vue'

const dataSet = [10, 20, 30]
const labels = ['Red', 'Green', 'Blue']

storiesOf('FoodFleet|charts/Lines', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => {
    return {
      components: { Lines },
      data () {
        return {
          dataCollection: {
            labels: labels,
            datasets: [
              {
                label: 'Data',
                data: dataSet
              }
            ]
          }
        }
      },
      template: `
          <v-container>
          <v-layout row>
            <v-flex sm6>
              <v-card class="px-3 py-3">
                <lines
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
      components: { Lines },
      data () {
        return {
          dataCollection: {
            datasets: [{
              data: dataSet,
              backgroundColor: 'red',
              borderWidth: 0,
              label: 'Data'
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
                <lines
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
      components: { Lines },
      data () {
        return {
          dataCollection: {
            datasets: [{
              data: dataSet,
              backgroundColor: 'blue',
              borderWidth: 0
            }],

            labels: labels
          },
          options: {
            legend: {
              display: false
            }
          }
        }
      },
      template: `
        <v-container>
          <v-layout row>
            <v-flex sm6>
              <v-card class="px-3 py-3">
                <lines
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
