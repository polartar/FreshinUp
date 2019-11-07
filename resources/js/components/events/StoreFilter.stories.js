import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import StoreFilter from './StoreFilter.vue'

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
        filters: { },
        types: [
          { uuid: 1, name: 'modi' },
          { uuid: 2, text: 'ipsum' },
          { uuid: 3, text: 'architecto' }
        ]
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
          :types="types"
          @runFilter="filterMember"
        />
      </v-container>
    `
  }))
