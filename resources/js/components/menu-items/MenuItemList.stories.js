import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import MenuItemList from './MenuItemList'
import { FIXTURE_MENU_ITEMS } from '../../../../tests/Javascript/__data__/menuItems'

export const Empty = () => ({
  components: { MenuItemList },
  data () {
    return {
      items: []
    }
  },
  template: `
      <menu-item-list
        :items="items"
      />
    `
})

export const Loading = () => ({
  components: { MenuItemList },
  template: `
      <menu-item-list
        is-loading
      />
    `
})

export const Populated = () => ({
  components: { MenuItemList },
  data () {
    return {
      items: FIXTURE_MENU_ITEMS
    }
  },
  methods: {
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    }
  },
  template: `
      <menu-item-list
        :items="items"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/menu-items/MenuItemList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
