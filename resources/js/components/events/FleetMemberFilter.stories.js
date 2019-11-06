import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import FleetMemberFilter from './FleetMemberFilter.vue'

let locations = [
  { value: 1, text: 'California' },
  { value: 2, text: 'Texas' },
  { value: 3, text: 'Florida' },
  { value: 4, text: 'Colorado' },
  { value: 5, text: 'Hawaii' },
  { value: 6, text: 'New Jersey' }
]

let types = [
  { value: 1, text: 'PC' },
  { value: 2, text: 'IOS' },
  { value: 3, text: 'ANDROID' }
]

let tags = [
  { value: 1, text: 'FOOD TAG 1' },
  { value: 2, text: 'FOOD TAG 2' },
  { value: 3, text: 'FOOD TAG 3' }
]

storiesOf('FoodFleet|event/FleetMemberFilter', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { FleetMemberFilter },
    data () {
      return {
        locations: locations,
        types: types,
        tags: tags
      }
    },
    methods: {
      filterMember (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <fleet-member-filter
          :locations="locations"
          :types="types"
          :tags="tags"
          @runFilter="filterMember"
        />
      </v-container>
    `
  }))
