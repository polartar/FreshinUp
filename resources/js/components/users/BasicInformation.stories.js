import { storiesOf } from '@storybook/vue'
// import { action } from '@storybook/addon-actions'

import BasicInformation from './BasicInformation.vue'

export const Default = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information/>
      </v-container>
    `
})
export const DefaultModeDetail = () => ({
  components: { BasicInformation },
  template: `
      <v-container>
        <basic-information mode-detail>
      </v-container>
    `
})

storiesOf('FoodFleet|components/users/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Default detail', DefaultModeDetail)
