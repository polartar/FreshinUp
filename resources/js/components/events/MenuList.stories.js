import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MenuList from './MenuList'

const menus = [
  { uuid: 1, title: 'menu item 1', description: 'description 1', servings: 12, cost: 200 },
  { uuid: 2, title: 'menu item 2', description: 'description 2', servings: 12, cost: 200 },
  { uuid: 3, title: 'menu item 3', description: 'description 3', servings: 12, cost: 2100 }
]

storiesOf('FoodFleet|event/MenuList', module)
  .add('default', () => {
    return {
      components: { MenuList },
      template: `
          <v-container>
            <menu-list />
          </v-container>
      `
    }
  })
  .add('with menus', () => {
    return {
      components: { MenuList },
      data () {
        return {
          menus: menus
        }
      },
      methods: {
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
          <v-container>
            <menu-list
              :menus="menus"
              @manage-edit="edit"
              @manage-delete="del"
              @manage-multiple-delete="multipleDelete"
            />
          </v-container>
      `
    }
  })
