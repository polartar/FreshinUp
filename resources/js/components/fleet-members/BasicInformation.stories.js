import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import BasicInformation from './BasicInformation'

import {
  FIXTURE_FLEET_MEMBER,
  FIXTURE_MEMBER_LOCATIONS,
  FIXTURE_MEMBER_TYPE
} from '../../../../tests/Javascript/__data__/fleet-members'

export const Default = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information />
      </v-container>
    `
})

export const WithData = () => ({
  components: { BasicInformation },
  data () {
    return {
      member: FIXTURE_FLEET_MEMBER,
      types: FIXTURE_MEMBER_TYPE,
      locations: FIXTURE_MEMBER_LOCATIONS
    }
  },
  template: `
      <v-container>
        <basic-information :member="member" :locations="locations" :types="types" @save="onSave" @cancel="onCancel" @delete="onDelete"/>
      </v-container>
    `,
  methods: {
    onSave (payload) {
      action('onSave')(payload)
    },

    onCancel () {
      action('onCancel')()
    },

    onDelete (payload) {
      action('onDelete')(payload)
    }
  }
})

storiesOf('FoodFleet|fleet-member/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('With data', WithData)
