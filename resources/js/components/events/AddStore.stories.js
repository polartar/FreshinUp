import { storiesOf } from '@storybook/vue'

import AddStore from './AddStore'

import {
  FIXTURE_FLEET_MEMBER_EVENT,
  FIXTURE_FLEET_MEMBERS,
  FIXTURE_MEMBER_TYPES
} from '../../../../tests/Javascript/__data__/fleet-members'

export const Default = () => ({
  components: { AddStore },
  data () {
    return {
      event: FIXTURE_FLEET_MEMBER_EVENT
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
      stores: FIXTURE_FLEET_MEMBERS,
      types: FIXTURE_MEMBER_TYPES,
      event: FIXTURE_FLEET_MEMBER_EVENT
    }
  },
  template: `
      <v-container>
        <add-store :members="stores" :member-types="types" :event="event"/>
      </v-container>
    `
})

storiesOf('FoodFleet|components/event/AddStore', module)
  .add('Default', Default)
  .add('with data', WithData)
