import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import { FIXTURE_USER } from '../../../../tests/Javascript/__data__/user'

import BasicInformation from './BasicInformation.vue'

export const Default = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information/>
      </v-container>
    `
})
export const DefaultModeDetail = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information mode-detail>
      </v-container>
    `
})

export const Populated = () => ({
  components: { BasicInformation },
  data () {
    return {
      user: FIXTURE_USER
    }
  },
  template: `
      <v-container>
        <basic-information
          :value="user"
        />
      </v-container>
    `
})

export const PopulatedModeDetail = () => ({
  components: { BasicInformation },
  data () {
    return {
      user: FIXTURE_USER
    }
  },
  template: `
      <v-container>
        <basic-information
          mode-detail
          :value="user"
        />
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
    },
    onChangePassword (payload) {
      action('onChangePassword')(payload)
    }
  }
})

storiesOf('FoodFleet|components/users/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Default detail', DefaultModeDetail)
  .add('Populated', Populated)
  .add('Populated detail', PopulatedModeDetail)
