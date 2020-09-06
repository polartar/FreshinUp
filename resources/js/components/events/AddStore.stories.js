import { storiesOf } from '@storybook/vue'

import AddStore from './AddStore'

import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_EVENT } from '../../../../tests/Javascript/__data__/event'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'

export const Default = () => ({
  components: { AddStore },
  data () {
    return {
      event: FIXTURE_EVENT
    }
  },
  template: `
    <v-container>
      <add-store :event="event"/>
    </v-container>
  `
})

export const WithData = () => ({
  components: { AddStore },
  data () {
    return {
      stores: FIXTURE_STORES,
      types: FIXTURE_STORE_TYPES,
      event: FIXTURE_EVENT
    }
  },
  template: `
    <v-container>
      <add-store
        :stores="stores"
        :store-types="types"
        :event="event"/>
    </v-container>
  `
})

storiesOf('FoodFleet|components/event/AddStore', module)
  .add('Default', Default)
  .add('WithData', WithData)
