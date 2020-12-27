<template>
  <v-content
    class="background-image"
    :data-background-image="background"
  >
    <v-container
      fluid
      fill-height
    >
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
            :is-loading="loading"
            :logo="logo"
            :title="title"
            :value="auth"
            @password-forgot="forgotPwDialog = true"
            @input="login"
            @register-as="registerAs"
          >
            <v-snackbar
              v-model="isMessageVisible"
              color="success"
              :timeout="6000"
              top
            >
              {{ message }}
              <v-btn
                dark
                flat
                @click="setMessageVisibility(false)"
              >
                Close
              </v-btn>
            </v-snackbar>
            <v-snackbar
              v-model="isVisible"
              color="error"
              :timeout="6000"
              top
            >
              {{ errorMessages }}
              <v-btn
                dark
                flat
                @click="setErrorVisibility(false)"
              >
                Close
              </v-btn>
            </v-snackbar>
          </login>
        </v-flex>
      </v-layout>
    </v-container>

    <v-dialog
      v-model="forgotPwDialog"
      max-width="500"
    >
      <forgot-password
        ref="forgotpasswordmodal"
        @close="forgotPwDialog = false"
        @showstatus="showMessage"
      />
    </v-dialog>
  </v-content>
</template>

<script>
import BusAuth from 'fresh-bus/pages/auth/index.vue'
import Login from '~/components/users/Login.vue'
import { mapActions, mapGetters } from 'vuex'
import { createHelpers } from 'vuex-map-fields'

// TODO: move refactor to fresh-bus along with Login component

const generalErrorMessageFields = createHelpers({
  getterType: 'generalErrorMessages/getField',
  mutationType: 'generalErrorMessages/updateField'
}).mapFields

const generalMessageFields = createHelpers({
  getterType: 'generalMessage/getField',
  mutationType: 'generalMessage/updateField'
}).mapFields

export default {
  components: { Login },
  extends: BusAuth,
  layout: 'blank',
  meta: { layout: 'blank' },
  data () {
    return {
      loading: false,
      auth: {
        email: '',
        password: ''
      }
    }
  },
  computed: {
    ...generalErrorMessageFields([
      'isVisible'
    ]),
    ...generalMessageFields({
      isMessageVisible: 'isVisible'
    }),
    ...mapGetters('generalErrorMessages', {
      errorMessages: 'errorMessages'
    }),
    ...mapGetters('generalMessage', {
      message: 'message'
    })
  },
  methods: {
    ...mapActions('generalMessage', {
      setMessageVisibility: 'setVisibility'
    }),
    ...mapActions('generalMessage', {
      setMessageVisibility: 'setVisibility'
    }),
    login (data) {
      this.error = null
      this.loading = true
      this.$auth.login({
        method: 'post',
        url: 'login',
        data,
        rememberMe: true
      })
        .then(() => {
          this.$router.push({ path: this.loginSuccessRedirectPath })
          this.$router.go()
        })
        .catch(() => {
          this.$store.dispatch('generalErrorMessages/setErrors', 'Username and Password were not accepted')
        })
        .then(() => {
          this.loading = false
        })
    },
    registerAs (type) {
      this.$router.push({ path: `/register?type=${type}` })
    },
    showMessage (message, type) {
      if (type === 'error') {
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      } else {
        this.$store.dispatch('generalMessage/setMessage', message)
      }
    }
  },
  beforeRouteEnterOrUpdate (vm, from, to, next) {
    vm.$store.dispatch('page/setLoading', false)
    vm.auth.email = vm.$route.query.email
  }
}
</script>

<style scoped>
  a {
    text-decoration: none;
  }

  .rounded-card {
    border-radius: 5px;
    padding: 16px;
  }

  .align-center {
    text-align: center;
  }

  .background-image {
    min-height: 100%;
    background-color: #fafafa;
    /*background-size: cover;*/
    background-image: url(/images/background.png);
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;

  }

  @media only screen and (min-width: 40rem) {
    .background-image {
      background-image: url(/images/background@2x.png);
    }
  }

  @media only screen and (min-width: 80rem) {
    .background-image {
      background-image: url(/images/background@3x.png);
    }
  }

  .ff-login__button .v-btn__content {
    padding: 4rem 2rem;
  }
</style>
