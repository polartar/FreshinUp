import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MessageSend from './MessageSend.vue'

// Components
storiesOf('FoodFleet|event/MessageSend', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { MessageSend },
    methods: {
      onSendMessage (params) {
        action('send-message')(params)
      }
    },
    template: `
      <v-container>
        <MessageSend
          @send-message="onSendMessage"
        />
      </v-container>
    `
  }))
