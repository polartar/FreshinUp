<template>
  <v-content>
    <v-container
      fluid
      fill-height
    >
      <v-layout
        justify-center
        align-center
      >
        <v-flex
          xs10
          md6
        >
          <v-card>
            <v-form
              v-model="valid"
              @submit.prevent="whenValid(submit)"
            >
              <v-card-title>
                <h3>New Password</h3>
              </v-card-title>
              <v-card-text>
                <v-text-field
                  ref="email"
                  v-model="input.email"
                  v-validate="'required|email'"
                  data-vv-name="email"
                  :error-messages="errors.collect('email')"
                  label="Email"
                  name="email"
                  type="email"
                  required
                />
                <v-text-field
                  ref="password"
                  v-model="input.password"
                  v-validate="'required'"
                  :append-icon="isPasswordVisible ? 'visibility' : 'visibility_off'"
                  :error-messages="errors.collect('password')"
                  :type="passwordFieldType"
                  data-vv-name="password"
                  label="Password"
                  required
                  @click:append="isPasswordVisible = !isPasswordVisible"
                />
                <v-text-field
                  v-model="input.password_confirmation"
                  v-validate="'required|confirmed:password'"
                  :append-icon="isPasswordConfirmationVisible ? 'visibility' : 'visibility_off'"
                  :error-messages="errors.collect('password_confirmation')"
                  :type="passwordConfirmationFieldType"
                  data-vv-as="password"
                  data-vv-name="password"
                  hint="Must match Password"
                  label="Confirm Password"
                  required
                  @click:append="isPasswordConfirmationVisible = !isPasswordConfirmationVisible"
                />
              </v-card-text>
              <v-card-actions>
                <v-btn
                  color="secondary"
                  type="button"
                  flat
                  small
                  href="/auth"
                >
                  Go to Login
                </v-btn>
                <v-spacer />
                <v-btn
                  color="success"
                  :loading="submitting"
                  class="ma-0"
                  type="submit"
                >
                  Reset Password
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-content>
</template>

<script>
import PasswordConfirmation from 'fresh-bus/components/mixins/PasswordConfirmation'
import Validate from 'fresh-bus/components/mixins/Validate'
import get from 'lodash/get'

export default {
  layout: 'blank',
  meta: { layout: 'blank', auth: false },
  mixins: [
    Validate,
    PasswordConfirmation
  ],
  inject: ['$validator'],
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', false)
  },
  data: () => ({
    input: {
      email: '',
      password: '',
      confirm_password: ''
    },
    submitting: false,
    valid: false,
    token: null
  }),
  methods: {
    submit () {
      this.submitting = true
      this.$store.dispatch('auth/resetPassword', {
        data: {
          token: this.$route.params.token,
          ...this.input
        }
      })
        .then(() => {
          this.$router.push({ name: 'auth' })
        })
        .catch((error) => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.submitting = true
        })
    }
  }
}
</script>
