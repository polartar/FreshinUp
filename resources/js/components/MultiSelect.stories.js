import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import MultiSelect from './MultiSelect.vue'

let statuses = [
  { id: 1, name: 'Draft' },
  { id: 2, name: 'Pending' },
  { id: 3, name: 'Confirmed' },
  { id: 4, name: 'Past' },
  { id: 5, name: 'Cancelled' }
]

storiesOf('FoodFleet|ui/MultiSelect', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { MultiSelect },
    data () {
      return {
        status: null,
        statuses: statuses
      }
    },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <multi-select
          v-model="status"
          item-value="id"
          item-text="name"
          placeholder="Select Status"
          :items="statuses"
        />
      </v-container>
    `
  }))
  .add('with statuses', () => ({
    components: { MultiSelect },
    data () {
      return {
        status: statuses.map(item => item.id),
        statuses: statuses
      }
    },
    methods: {
      filterEvents (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <multi-select
          v-model="status"
          item-value="id"
          item-text="name"
          placeholder="Select Status"
          :items="statuses"
        />
      </v-container>
    `
  }))
