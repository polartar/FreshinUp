import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import Bootstrap from './Bootstrap.vue'

storiesOf('FoodFleet|dashboard/Bootstrap', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#ede8e2', default: true }
    ]
  })
  .add('default', () => ({
    components: { Bootstrap },
    data () {
      return {
        content: {
          title: '1. Bootstrap title',
          description: 'Bootstrap description',
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
            <bootstrap
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
    components: { Bootstrap },
    data () {
      return {
        content1: {
          title: '1. Bootstrap title',
          description: 'Bootstrap description',
          button: 'Take me there!'
        },
        content2: {
          title: '2. Disabled bootstrap',
          description: 'Bootstrap description2',
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
            <bootstrap
              :content="content1"
              :nav-to="navTo1"
              :icon="icon1"
            />
          </v-flex>
          <v-flex xs4 ml-2>
            <bootstrap
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
