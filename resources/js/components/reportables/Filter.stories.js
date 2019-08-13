import { storiesOf } from '@storybook/vue'
import { withBackgrounds } from '@storybook/addon-backgrounds'
import { action } from '@storybook/addon-actions'

// Components
import ReportableFilter from './Filter.vue'

storiesOf('reportables/Filter', module)
  .addDecorator(withBackgrounds([
    { name: 'report-center', value: '#c5dbe3' }
  ]))
  .add('default', () => ({
    components: { ReportableFilter },
    methods: {
      onRun (params) {
        action('Run')(params)
      }
    },
    template: `
      <v-container>
        <reportable-filter
        @runFilter="onRun"
        />
      </v-container>
  `
  }))
