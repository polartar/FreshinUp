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
          <v-form @submit.prevent="login">
            <v-card class="elevation-12 rounded-card">
              <v-toolbar
                flat
                color="transparent"
              >
                <v-img
                  :src="logo"
                  height="64"
                  contain
                />
              </v-toolbar>

              <v-card-title
                primary-title
                class="justify-center"
              >
                <div>
                  <h3
                    class="display-1 mb-0 primary--text font-weight-bold"
                    :style="{ justifyContent: 'center'}"
                  >
                    Log in to {{ title }}
                  </h3>
                </div>
              </v-card-title>

              <v-card-text>
                <v-text-field
                  v-model="email"
                  name="login"
                  placeholder="Email address"
                  type="text"
                  label="Email address"
                />
                <v-text-field
                  id="password"
                  v-model="password"
                  name="password"
                  placeholder="Password"
                  label="Password"
                  type="password"
                />
              </v-card-text>

              <div>
                <v-card-actions>
                  <v-layout
                    row
                    wrap
                  >
                    <v-flex xs12>
                      Forgot password ?
                      <v-btn
                        class="text-uppercase"
                        color="primary"
                        type="button"
                        flat
                        small
                        @click="forgotPwDialog = true"
                      >
                        Reset password here
                      </v-btn>
                    </v-flex>
                    <v-flex xs12>
                      <v-btn
                        color="primary"
                        class="ff-login__button mt-2 mb-2"
                        type="submit"
                        :loading="loading"
                      >
                        Login
                      </v-btn>
                    </v-flex>
                    <v-flex>
                      <p class="mb-1 mt-2">
                        Not a member ?
                      </p>
                      <p>
                        Sign up as a <router-link :to="{ path: '/register?type=customer'}">
                          Customer
                        </router-link> or <router-link :to="{ path: '/register?type=supplier'}">
                          Join our fleet
                        </router-link>
                      </p>
                    </v-flex>
                  </v-layout>
                </v-card-actions>
              </div>

              <v-snackbar
                v-model="hasErrors"
                :color="colorStatus"
                :timeout="6000"
                top
              >
                {{ error }}
                <v-btn
                  dark
                  flat
                  @click="hasErrors = false"
                >
                  Close
                </v-btn>
              </v-snackbar>
            </v-card>
          </v-form>
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

export default {
  extends: BusAuth,
  layout: 'blank',
  meta: { layout: 'blank' },
  data () {
    return {
      loading: false
    }
  },
  methods: {
    login () {
      this.hasErrors = null
      this.error = null
      this.colorStatus = null
      this.loading = true
      this.$auth.login({
        method: 'post',
        url: 'login',
        data: { email: this.email, password: this.password },
        rememberMe: true
      })
        .then(() => {
          this.$router.push({ path: this.loginSuccessRedirectPath })
          this.$router.go()
        })
        .catch(error => {
          console.error(error)
          this.hasErrors = true
          this.colorStatus = 'error'
          this.error = 'Username and Password were not accepted'
        })
        .then(() => {
          this.loading = false
        })
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
