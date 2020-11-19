import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import MenuItems from './MenuItems'
import MenuItemForm from './MenuItemForm'
import { FIXTURE_MENU_ITEMS } from '../../../../tests/Javascript/__data__/menuItems'

export const Empty = () => ({
  components: { MenuItems },
  data () {
    return {
      items: []
    }
  },
  template: `
    <menu-items
      :items="items"
    />
  `
})

export const Loading = () => ({
  components: { MenuItems, MenuItemForm },
  template: `
    <menu-items
      is-loading
    />
  `
})

export const Populated = () => ({
  components: { MenuItems, MenuItemForm },
  data () {
    return {
      dialog: false,
      items: FIXTURE_MENU_ITEMS,
      pagination: {
        rowsPerPage: 10,
        page: 1,
        totalItems: 35
      },
      sorting: {
        sortBy: 'uuid',
        descending: false
      }
    }
  },
  methods: {
    onManageView (act, item) {
      action('onManageView')(act, item)
    },
    onManageDelete (act, item) {
      action('onManageDelete')(act, item)
    },
    onManageMultipleDelete (act, items) {
      action('onManageMultipleDelete')(act, items)
    },
    onNew (payload) {
      action('onNew')(payload)
    }
  },
  template: `
      <menu-items
        :dialog="dialog"
        :items="items"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @manage-view="onManageView"
        @manage-delete="onManageDelete"
        @manage-multiple-delete="onManageMultipleDelete"
      >
        <template #new-form>
          <menu-item-form
            @input="onNew"
            @cancel="dialog = false"
          />
        </template>

      </menu-items>
    `
})

storiesOf('FoodFleet|components/menu-items/MenuItems', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('Loading', Loading)
  .add('Populated', Populated)
