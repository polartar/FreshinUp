import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import MenuItems from './MenuItems'
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

export const IsLoading = () => ({
  components: { MenuItems },
  template: `
    <menu-items
      is-loading
    />
  `
})

export const Populated = () => ({
  components: { MenuItems },
  data () {
    return {
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
    onManage (act, item) {
      action('onManage')(act, item)
    },
    onManageMultiple (act, items) {
      action('onManageMultiple')(act, items)
    },
    onPaginate (value) {
      action('onPaginate')(value)
    },
    onNew (payload) {
      action('onNew')(payload)
    }
  },
  template: `
      <menu-items
        :items="items"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @new-submit="onNew"
        @paginate="onPaginate"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    `
})

storiesOf('FoodFleet|components/menu-items/MenuItems', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Empty', Empty)
  .add('IsLoading', IsLoading)
  .add('Populated', Populated)
