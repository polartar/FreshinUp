import { storiesOf } from '@storybook/vue'

import AddStore from './AddStore'

storiesOf('FoodFleet|event/AddStore', module)
  .add('default', () => ({
    components: { AddStore },
    data () {
      return {}
    },
    methods: {},
    template: `
      <v-container>
        <add-store />
      </v-container>
    `
  }))
