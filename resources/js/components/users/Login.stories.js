import { storiesOf } from '@storybook/vue'
// Components
import Login from './Login.vue'
import { action } from '@storybook/addon-actions'

export const Basic = () => ({
  components: { Login },
  methods: {
    onInput (payload) {
      action('input')(payload)
    },
    onRegisterAs (type) {
      action('register-as')(type)
    },
    onPasswordForgot () {
      action('password-forgot')()
    }
  },
  template: `
    <v-container fluid>
      <v-layout
        align-center
        justify-center
      >
        <v-flex
          xs12
          sm10
          md4
        >
          <login
            logo="/images/logo.png"
            title="FoodFleet"
            @input="onInput"
            @password-forgot="onPasswordForgot"
            @register-as="onRegisterAs"
          />
        </v-flex>
      </v-layout>
    </v-container>
  `
})

export const Loading = () => ({
  components: { Login },
  template: `
    <v-container fluid>
      <v-layout
        align-center
        justify-center
      >
        <v-flex
          xs12
          sm10
          md4
        >
          <login
            logo="/images/logo.png"
            title="Foodfleet"
            is-loading
          />
        </v-flex>
      </v-layout>`
})

export const WithError = () => ({
  components: { Login },
  data () {
    return {
      hasError: true
    }
  },
  template: `
    <v-container fluid>
      <v-layout
        align-center
        justify-center
      >
        <v-flex
          xs12
          sm10
          md4
        >
          <login
            title="FoodFleet"
            logo="/images/logo.png"
          >

            <v-snackbar
              :value="hasError"
              color="error"
              :timeout="6000"
              top
            >
              Wrong login or password
              <v-btn
                dark
                flat
                @click="hasError = false"
              >
                Close
              </v-btn>
            </v-snackbar>
          </login>
        </v-flex>
      </v-layout>
    </v-container>
  `
})

storiesOf('FoodFleet|users/Login', module)
  .add('Basic', Basic)
  .add('Loading', Loading)
  .add('WithError', WithError)
