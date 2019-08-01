import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import ClearButton from './ClearButton.vue'

storiesOf('ClearButton', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    methods: {
      onClear () {
        action('Clear')('Clicked')
      }
    },
    components: { ClearButton },
    template: `
      <v-container>
        <clear-button
        @clear="onClear"
        />
      </v-container>
`
  }))
