import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import FleetSummary from './FleetSummary'

storiesOf('FoodFleet|events/FleetMemberSummary', module)
  .add(
    'title and items only',
    () => ({
      components: { FleetSummary },
      data () {
        return {
          title: 'Fleet Member Summary',
          items: { owner: 'Dan Smith', 'lisence due': 'Dec, 30 2020', 'contact phone': '938 374822', 'contact email': 'dan.simth@gmail.com' },
        }
      },
      template: `
        <v-container fluid>
          <v-layout row>
            <fleet-summary
             :title="title"
             :items="items"
            />
            </v-layout>
        </v-container>
      `
    })
  )
  .add(
    'with button set',
    () => ({
      components: { FleetSummary },
      methods: {
        onButtonClick () {
          action('onButtonClick')('button clicked')
        }
      },
      data () {
        return {
          title: 'Fleet Member Summary',
          items: { owner: 'Dan Smith', 'lisence due': 'Dec, 30 2020', 'contact phone': '938 374822', 'contact email': 'dan.simth@gmail.com' },
          button_text: 'View Fleet Member Profile'
        }
      },
      template: `        
        <v-container fluid>
          <v-layout row>
            <fleet-summary
             @onButtonClick="onButtonClick"
             :title="title"
             :items="items"
             :button_text="button_text"
            />
            </v-layout>
        </v-container>
      `
    })
  )
  .add(
    'with tags set',
    () => ({
      components: { FleetSummary },
      methods: {
        onButtonClick () {
          action('onButtonClick')('button clicked')
        }
      },
      data () {
        return {
          title: 'Fleet Member Summary',
          items: { owner: 'Dan Smith', 'lisence due': 'Dec, 30 2020', 'contact phone': '938 374822', 'contact email': 'dan.simth@gmail.com' },
          tags: [ { id: 1, name: 'SEAFOOD' }, { id: 2, name: 'SMOKED' }, { id: 3, name: 'DESSERT' }, { id: 4, name: 'BAY AREA' }, { id: 5, name: 'VEGAN OPTIONS' }, { id: 6, name: 'SMOKED' }, { id: 7, name: 'DESSERT' }, { id: 8, name: 'SEAFOOD' }, { id: 9, name: 'SMOKED' } ],
          button_text: 'View Fleet Member Profile'
        }
      },
      template: `        
    <v-container fluid>
      <v-layout row>
        <fleet-summary
         @onButtonClick="onButtonClick"
         :title="title"
         :items="items"
         :tags="tags"
         :button_text="button_text"
        />
        </v-layout>
    </v-container>
  `
    })
  )
  .add(
    'with remove button set',
    () => ({
      components: { FleetSummary },
      methods: {
        onButtonClick () {
          action('onButtonClick')('button clicked')
        },
        remove () {
          action('remove')('remove clicked')
        }
      },
      data () {
        return {
          title: 'Fleet Member Summary',
          items: { owner: 'Dan Smith', 'lisence due': 'Dec, 30 2020', 'contact phone': '938 374822', 'contact email': 'dan.simth@gmail.com' },
          tags: [ { id: 1, name: 'SEAFOOD' }, { id: 2, name: 'SMOKED' }, { id: 3, name: 'DESSERT' }, { id: 4, name: 'BAY AREA' }, { id: 5, name: 'VEGAN OPTIONS' }, { id: 6, name: 'SMOKED' }, { id: 7, name: 'DESSERT' }, { id: 8, name: 'SEAFOOD' }, { id: 9, name: 'SMOKED' } ],
          button_text: 'View Fleet Member Profile',
          button_remove_text: 'Remove fleet member from this event'
        }
      },
      template: `        
    <v-container fluid>
      <v-layout row>
        <fleet-summary
         @onButtonClick="onButtonClick"
         @remove="remove"
         :title="title"
         :items="items"
         :tags="tags"
         :button_text="button_text"
         :button_remove_text="button_remove_text"
        />
        </v-layout>
    </v-container>
  `
    })
  )
