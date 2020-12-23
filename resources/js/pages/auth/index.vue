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
            @password-forgot="forgotPwDialog = true"
            @input="login"
            @register-as="registerAs"
          >
            <v-snackbar
              :value="Boolean(error)"
              color="error"
              :timeout="6000"
              top
            >
              Wrong login or password
              <v-btn
                dark
                flat
                @click="error = null"
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
        @showstatus="showstatus"
      />
    </v-dialog>
  </v-content>
</template>

<script>
import BusAuth from 'fresh-bus/pages/auth/index.vue'
import Login from '~/components/users/Login.vue'

// TODO: move refactor to fresh-bus along with Login component

export default {
  components: { Login },
  extends: BusAuth,
  layout: 'blank',
  meta: { layout: 'blank' },
  data () {
    return {
      loading: false
    }
  },
  methods: {
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
          // TODO on login success set user menu items to the following
          // - Dashboard
          // - My Company
          // - My Fleet
          // - Events
          // - Documents
        })
        .catch(error => {
          console.error(error)
          this.error = 'Username and Password were not accepted'
        })
        .then(() => {
          this.loading = false
        })
    },
    registerAs (type) {
      this.$router.push({ path: `/register?type=${type}` })
    }
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
