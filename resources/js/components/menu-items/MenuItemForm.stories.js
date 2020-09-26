import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import MenuItemForm from './MenuItemForm'
import { FIXTURE_MENU } from '../../../../tests/Javascript/__data__/menus'

export const Default = () => ({
  components: { MenuItemForm },
  template: `
    <v-container class="white">
      <MenuItemForm/>
    </v-container>
  `
})

export const WithData = () => ({
  components: { MenuItemForm },
  data () {
    return {
      item: FIXTURE_MENU
    }
  },
  methods: {
    onSave (payload) {
      action('onSave')(payload)
    },
    onCancel () {
      action('onCancel')()
    }
  },
  template: `
    <v-container class="white">
      <MenuItemForm
        :value="item"
        @input="onSave"
        @cancel="onCancel"/>
    </v-container>
  `
})

storiesOf('FoodFleet|components/menu-items/MenuItemForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('With data', WithData)
