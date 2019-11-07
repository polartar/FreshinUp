import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import StoreFilter from './StoreFilter.vue'

let types = [
  { value: 1, text: 'PC' },
  { value: 2, text: 'IOS' },
  { value: 3, text: 'ANDROID' }
]

storiesOf('FoodFleet|event/StoreFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { StoreFilter },
    data () {
      return {
        filters: {
          types: types
        }
      }
    },
    methods: {
      filterMember (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <store-filter
          :filters="filters"
          @runFilter="filterMember"
        />
      </v-container>
    `
  }))
