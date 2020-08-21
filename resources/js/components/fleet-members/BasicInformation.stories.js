import { storiesOf } from '@storybook/vue'

import BasicInformation from './BasicInformation'

const junkMember = {}

storiesOf('FoodFleet|fleet-member/BasicInformation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('empty', () => ({
    components: { BasicInformation },
    template: `
      <v-container>
        <basic-information />
      </v-container>
    `
  }))
  .add('with data', () => ({
    components: { BasicInformation },
    data () {
      return {
        member: junkMember
      }
    },
    template: `
      <v-container>
        <basic-information :member="member"/>
      </v-container>
    `
  }))
