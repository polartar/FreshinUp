import { storiesOf } from '@storybook/vue'
// import { action } from '@storybook/addon-actions'

import NotificationSettings from './NotificationSettings.vue'

export const Default = () => ({
  components: { NotificationSettings },
  template: `
      <v-container>
        <notification-settings/>
      </v-container>
    `
})

storiesOf('FoodFleet|components/users/NotificationSettings', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
