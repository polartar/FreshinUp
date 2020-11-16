import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import MenuItemForm from './MenuItemForm'
import { FIXTURE_MENU_ITEMS } from '../../../../tests/Javascript/__data__/menuItems'

export const Default = () => ({
  components: { MenuItemForm },
  template: `
    <v-container class="white">
      <MenuItemForm/>
    </v-container>
  `
})

export const Loading = () => ({
  components: { MenuItemForm },
  template: `
    <v-container class="white">
      <MenuItemForm
        is-loading
      />
    </v-container>
  `
})

export const WithData = () => ({
  components: { MenuItemForm },
  data () {
    return {
      item: FIXTURE_MENU_ITEMS[0]
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

export const WithServings = () => ({
  components: { MenuItemForm },
  data () {
    return {
      item: FIXTURE_MENU_ITEMS[0]
    }
  },
  template: `
    <v-container class="white">
      <MenuItemForm
        :value="item"
      />
    </v-container>
  `
})

export const WithoutServings = () => ({
  components: { MenuItemForm },
  data () {
    return {
      item: FIXTURE_MENU_ITEMS[1]
    }
  },
  template: `
    <v-container class="white">
      <MenuItemForm
        :value="item"
        :include-servings="false"
      />
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
  .add('Loading', Loading)
  .add('WithData', WithData)
  .add('WithServings', WithServings)
  .add('WithoutServings', WithoutServings)
