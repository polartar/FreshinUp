import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import AddStore from './AddStore'

import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'
import { FIXTURE_EVENT } from '../../../../tests/Javascript/__data__/event'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'
import { FIXTURE_STORE_TAGS } from '../../../../tests/Javascript/__data__/storeTags'

const mock = new MockAdapter(axios)
mock.onGet(/.*\/store-tags.*/).reply(200, {
  data: FIXTURE_STORE_TAGS
})

export const Basic = () => ({
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

export const Populated = () => ({
  components: { AddStore },
  data () {
    return {
      stores: FIXTURE_STORES,
      types: FIXTURE_STORE_TYPES,
      event: FIXTURE_EVENT
    }
  },
  methods: {
    onFilter (payload) {
      action('run-filter')(payload)
    },
    manage () {
      action('manage')(arguments)
    }
  },
  template: `
    <v-container>
      <add-store
        :stores="stores"
        :types="types"
        :event="event"
        @run-filter="onFilter"
        @manage="manage"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/event/AddStore', module)
  .add('Basic', Basic)
  .add('Populated', Populated)
