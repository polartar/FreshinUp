import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import FleetSummary from './FleetSummary'

storiesOf('FoodFleet|events/FleetMemberSummary', module)
  .add(
    'default',
    () => ({
      components: { FleetSummary },
      methods: {
        view () {
          action('view')('view clicked')
        }
      },
      data () {
        return {
          title: 'Fleet Member Summary',
          button_text: 'View Fleet Member Profile',
          fleet: { owner: 'Dan Smith', 'lisence due': 'Dec, 30 2020', 'contact phone': '938 374822', 'contact email': 'dan.simth@gmail.com' },
          show_remove: true,
          button_remove_text: 'Remove fleet member from this event',
          tags: [ { id: 1, name: 'SEAFOOD' }, { id: 2, name: 'SMOKED' }, { id: 3, name: 'DESSERT' }, { id: 4, name: 'BAY AREA' }, { id: 5, name: 'VEGAN OPTIONS' }, { id: 6, name: 'SMOKED' }, { id: 7, name: 'DESSERT' }, { id: 8, name: 'SEAFOOD' }, { id: 9, name: 'SMOKED' } ],
          selected: [ { id: 1, name: 'SEAFOOD' }, { id: 2, name: 'SMOKED' }, { id: 3, name: 'DESSERT' }, { id: 4, name: 'BAY AREA' }, { id: 5, name: 'VEGAN OPTIONS' }, { id: 6, name: 'SMOKED' }, { id: 7, name: 'DESSERT' }, { id: 8, name: 'SEAFOOD' }, { id: 9, name: 'SMOKED' } ]
        }
      },
      template: `        
        <v-container fluid>
          <v-layout row>
            <fleet-summary
             @view="view"
             :title="title"
             :fleet="fleet"
             :tags="tags"
             :selected="selected"
             :button_text="button_text"
             :show_remove="show_remove"
             :button_remove_text="button_remove_text"
            />
            </v-layout>
        </v-container>
      `
    })
  )
