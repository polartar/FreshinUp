import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import Menus from './Menus.vue'

const menus = [
  { uuid: 1, title: 'menu item 1', description: 'Loosely based on the world-famous shrimp burgers of the South Carolina Lowcountry.', servings: 12, cost: 200 },
  { uuid: 2, title: 'menu item 2', description: 'description 2', servings: 12, cost: 200 },
  { uuid: 3, title: 'menu item 3', description: 'description 3', servings: 12, cost: 2100 }
]

storiesOf('FoodFleet|event/Menus', module)
  .add('default', () => ({
    components: { Menus },
    data () {
      return {
        menuTitle: "Kevin's Truck",
        menus: []
      }
    },
    methods: {
      create () {
        action('create')([])
      },
      saveMenu (params) {
        action('save')(params)
      },
      edit (params) {
        action('manage-edit')(params)
      },
      del (params) {
        action('manage-delete')(params)
      },
      multipleDelete (params) {
        action('manage-multiple-delete')(params)
      }
    },
    template: `
        <menus
          :menuTitle="menuTitle"
          :menus="menus"
          @create="create"
          @save="saveMenu"
          @manage-edit="edit"
          @manage-delete="del"
          @manage-multiple-delete="multipleDelete"
        />
        `
  }))
  .add('edit menu', () => ({
    components: { Menus },
    data () {
      return {
        menuTitle: "Kevin's Truck",
        menus: menus
      }
    },
    methods: {
      create (params) {
        action('create')(params)
      },
      saveMenu (params) {
        action('save')(params)
      },
      edit (params) {
        action('manage-edit')(params)
      },
      del (params) {
        action('manage-delete')(params)
      },
      multipleDelete (params) {
        action('manage-multiple-delete')(params)
      }
    },
    template: `
        <menus
          :menuTitle="menuTitle"
          :menus="menus"
          @create="create"
          @save="saveMenu"
          @manage-edit="edit"
          @manage-delete="del"
          @manage-multiple-delete="multipleDelete"
        />
        `
  }))
