import { storiesOf } from '@storybook/vue'
import { withBackgrounds } from '@storybook/addon-backgrounds'
import { action } from '@storybook/addon-actions'

// Components
import FilterSorter from './Filter.vue'

storiesOf('FoodFleet|reportables/Filter', module)
  .addDecorator(withBackgrounds([
    { name: 'report-center', value: '#c5dbe3' }
  ]))
  .add('default', () => ({
    components: { FilterSorter },
    methods: {
      onRun (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <filter-sorter 
        @runFilter="onRun"
        />
      </v-container>
  `
  }))
