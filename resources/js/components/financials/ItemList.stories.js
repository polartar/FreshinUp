import { storiesOf } from '@storybook/vue'

// Components
import ItemList from './ItemList.vue'

const items = [
  {
    uuid: '1',
    name: 'Item 1',
    quantity: 2,
    total_money: 1000,
    total_tax_money: 200,
    total_discount_money: 0,
    category: {
      uuid: '121212',
      name: 'Category 1'
    }
  },
  {
    uuid: '2',
    name: 'Item 2',
    quantity: 3,
    total_money: 2000,
    total_tax_money: 400,
    total_discount_money: 100,
    category: {
      uuid: '1212123',
      name: 'Category 2'
    }
  }
]

storiesOf('FoodFleet|financials/ItemList', module)
  .addParameters({
    backgrounds: [
      { name: 'white', value: '#c5dbe3', default: true },
      { name: 'blue', value: '#205a80' }
    ]
  })
  .add('items array empty', () => {
    return {
      components: { ItemList },
      data () {
        return {
          items: []
        }
      },
      template: `
          <v-container>
            <ItemList
                :items="items"
            />
          </v-container>
      `
    }
  })
  .add('items is set', () => {
    return {
      components: { ItemList },
      data () {
        return {
          items: items
        }
      },
      template: `
          <v-container>
            <ItemList
                :items="items"
            />
          </v-container>
      `
    }
  })
