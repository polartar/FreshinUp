import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import FleetMemberSummary from './FleetMemberSummary'

storiesOf('FoodFleet|events/FleetMemberSummary', module)
  .add(
    'data set',
    () => ({
      components: { FleetMemberSummary },
      methods: {
        onButtonClick () {
          action('onButtonClick')('button clicked')
        },
        onRemoveClick () {
          action('onRemoveClick')('remove clicked')
        }
      },
      data () {
        return {
          member: {
            owner: 'Dan Smith',
            lisence_due: 'Dec, 30 2020',
            phone: '938 374822',
            email: 'dan.simth@gmail.com',
            tags: ['SEAFOOD', 'SMOKED', 'DESSERT', 'BAY AREA', 'VEGAN OPTIONS', 'SMOKED', 'DESSERT', 'SEAFOOD', 'SMOKED']
          }
        }
      },
      template: `        
  <v-container fluid>
    <v-layout>
      <v-flex md4>
        <fleet-member-summary
          :member="member"
          @onButtonClick="onButtonClick"
          @onRemoveClick="onRemoveClick"
        />
      </v-flex>
    </v-layout>
  </v-container>
  `
    })
  )
