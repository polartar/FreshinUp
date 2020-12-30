import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import { FIXTURE_USER } from '../../../../tests/Javascript/__data__/user'

import BasicInformation from './BasicInformation.vue'
import { USER_TYPE } from '../../store/modules/userTypes'
import { FIXTURE_USER_LEVELS } from '../../../../tests/Javascript/__data__/userLevels'
import { FIXTURE_USER_TYPES } from '../../../../tests/Javascript/__data__/userTypes'

import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'
import { FIXTURE_USER_STATUSES } from '../../../../tests/Javascript/__data__/userStatuses'

const mock = new MockAdapter(axios)

mock.onGet(/users/)
  .reply(200, {
    uuid: 'f9ecb331-28b4-3d1b-a4c7-132be8c0e677',
    first_name: 'John',
    last_name: 'Doe'
  })

export const Default = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information/>
      </v-container>
    `
})

export const IsLoading = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information is-loading/>
      </v-container>
    `
})

const methods = {
  onSave (payload) {
    action('onSave')(payload)
  },
  onCancel () {
    action('onCancel')()
  },
  onDelete (payload) {
    action('onDelete')(payload)
  },
  onChangePassword (payload) {
    action('onChangePassword')(payload)
  }
}

export const ForSupplier = () => ({
  components: { BasicInformation },
  data () {
    return {
      user: { ...FIXTURE_USER, type: USER_TYPE.SUPPLIER },
      levels: FIXTURE_USER_LEVELS,
      types: FIXTURE_USER_TYPES,
      statuses: FIXTURE_USER_STATUSES
    }
  },
  methods,
  template: `
      <v-container>
        <basic-information
          :value="user"
          :levels="levels"
          :types="types"
          :statuses="statuses"
          @input="onSave"
          @cancel="onCancel"
          @delete="onDelete"
          @change-password="onChangePassword"
        />
      </v-container>
    `
})

export const ForCustomer = () => ({
  components: { BasicInformation },
  data () {
    return {
      user: { ...FIXTURE_USER, type: USER_TYPE.CUSTOMER },
      levels: FIXTURE_USER_LEVELS,
      types: FIXTURE_USER_TYPES,
      statuses: FIXTURE_USER_STATUSES,
      isAdmin: true
    }
  },
  template: `
      <v-container>
        <basic-information
          :value="user"
          :levels="levels"
          :types="types"
          :statuses="statuses"
          :is-admin="isAdmin"
          @input="onSave"
          @cancel="onCancel"
          @delete="onDelete"
          @change-password="onChangePassword"
        />
      </v-container>
    `,
  methods
})

storiesOf('FoodFleet|components/users/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('IsLoading', IsLoading)
  .add('ForSupplier', ForSupplier)
  .add('ForCustomer', ForCustomer)
