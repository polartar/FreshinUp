import { storiesOf } from '@storybook/vue'
import AverageTicket from './AverageTicket.vue'

storiesOf('FoodFleet|financials/AverageTicket', module)
  .addParameters({
    backgrounds: [
      { name: 'white', value: '#c5dbe3', default: true },
      { name: 'blue', value: '#205a80' }
    ]
  })
  .add('values set', () => {
    return {
      components: { AverageTicket },
      template: `
          <v-container>
            <v-layout row>
              <v-flex xs4>
                <v-card>
                 <v-card-title class="font-weight-bold subheading">
                    AVG. Ticket
                  </v-card-title>
                  <v-divider></v-divider>
                  <average-ticket
                      average-ticket=23470
                  />
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
      `
    }
  })
