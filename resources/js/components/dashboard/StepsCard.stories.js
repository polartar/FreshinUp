import { storiesOf } from '@storybook/vue'

// Components
import StepsCard from './StepsCard.vue'

storiesOf('FoodFleet|dashboard/StepsCard', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#ede8e2', default: true }
    ]
  })
  .add('default', () => ({
    components: { StepsCard },
    data () {
      return {
        content: {
          title: '1. StepsCard title',
          description: 'StepsCard description',
          button: 'Take me there!'
        },
        icon: {
          name: 'icon-users',
          size: 145
        },
        navTo: 'myprofile'
      }
    },
    template: `
      <v-container
        fluid
      >
        <v-layout>
          <v-flex xs4>
            <steps-card
              :content="content"
              :nav-to="navTo"
              :icon="icon"
            />
          </v-flex>
        </v-layout>
      </v-container>
    `
  }))
  .add('more with disabled', () => ({
    components: { StepsCard },
    data () {
      return {
        content1: {
          title: '1. StepsCard title',
          description: 'StepsCard description',
          button: 'Take me there!'
        },
        content2: {
          title: '2. Disabled stepscard',
          description: 'StepsCard description2',
          button: 'Take me there!'
        },
        icon1: {
          name: 'icon-users',
          size: 145
        },
        icon2: {
          name: 'icon-companies',
          size: 145
        },
        navTo1: 'myprofile',
        navTo2: 'mycompany'
      }
    },
    template: `
      <v-container
        fluid
      >
        <v-layout>
          <v-flex xs4>
            <steps-card
              :content="content1"
              :nav-to="navTo1"
              :icon="icon1"
            />
          </v-flex>
          <v-flex xs4 ml-2>
            <steps-card
              :content="content2"
              :nav-to="navTo2"
              :icon="icon2"
              :disabled="true"
            />
          </v-flex>
        </v-layout>
      </v-container>
    `
  }))
