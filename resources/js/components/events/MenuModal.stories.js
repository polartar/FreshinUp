import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import MenuModal from './MenuModal.vue'

storiesOf('FoodFleet|event/MenuModal', module)
  .add('default', () => ({
    components: { MenuModal },
    data () {
      return {
        dialog: true,
        menu: {
          item: null,
          servings: null,
          cost: null,
          description: null
        }
      }
    },
    methods: {
      saveMenu (params) {
        action('save')(params)
      }
    },
    template: `
        <menu-modal
          v-model="dialog"
          :menu="menu"
          @save="saveMenu"
        />
        `
  }))
  .add('edit menu', () => ({
    components: { MenuModal },
    data () {
      return {
        dialog: true,
        menu: {
          id: 1,
          item: 'title 1',
          servings: 10,
          cost: 123,
          description: 'description 1'
        }
      }
    },
    methods: {
      saveMenu (params) {
        action('save')(params)
      }
    },
    template: `
        <menu-modal
          v-model="dialog"
          :menu="menu"
          @save="saveMenu"
        />
        `
  }))
