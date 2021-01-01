import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import { FIXTURE_USER } from '../../../../tests/Javascript/__data__/user'

import BasicInformation from './BasicInformation.vue'
import { FIXTURE_USER_LEVELS } from '../../../../tests/Javascript/__data__/userLevels'
import { FIXTURE_USER_TYPES } from '../../../../tests/Javascript/__data__/userTypes'

import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'
import { FIXTURE_USER_STATUSES } from '../../../../tests/Javascript/__data__/userStatuses'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import { FIXTURE_COMPANIES } from '../../../../tests/Javascript/__data__/companies'

const mock = new MockAdapter(axios)

mock.onGet(/users/)
  .reply(200, {
    data: FIXTURE_USERS
  })

mock.onGet(/companies/)
  .reply(200, {
    data: FIXTURE_COMPANIES
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

export const BasicView = () => ({
  components: { BasicInformation },
  data () {
    return {
      user: FIXTURE_USER,
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

export const AdminView = () => ({
  components: { BasicInformation },
  data () {
    return {
      user: FIXTURE_USER,
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
        is-admin
        @input="onSave"
        @cancel="onCancel"
        @delete="onDelete"
        @change-password="onChangePassword"
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/users/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('IsLoading', IsLoading)
  .add('BasicView', BasicView)
  .add('AdminView', AdminView)
