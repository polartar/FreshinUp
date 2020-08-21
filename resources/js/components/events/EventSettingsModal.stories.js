import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import EventSettingsModal from './EventSettingsModal'

storiesOf('FoodFleet|components/event/EventSettingsModal', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { EventSettingsModal },
    methods: {
      save (params) {
        action('save')(params)
      },
      cancel () {
        action('cancel')()
      }
    },
    template: `
      <event-settings-modal
        @save="save"
        @cancel="cancel"
      />
    `
  }))
