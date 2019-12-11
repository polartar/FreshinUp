import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import EventSettings from './EventSettings'

storiesOf('FoodFleet|event/EventSettings', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { EventSettings },
    data () {
      return {
        isDialogOpened: true
      }
    },
    methods: {
      save (params) {
        action('save')(params)
      },
      cancel () {
        action('cancel')()
      }
    },
    template: `
       <event-settings
        :isDialogOpened="isDialogOpened"
        @save="save"
        @cancel="cancel"
      />
    `
  }))
