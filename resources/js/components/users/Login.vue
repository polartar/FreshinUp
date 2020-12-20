<template>
  <v-form @submit.prevent="save()">
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
        <h3
          class="headline mb-0 primary--text font-weight-bold"
          :style="{ justifyContent: 'center'}"
        >
          Log in to {{ title }}
        </h3>
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
              @click="passwordForgot"
            >
              Reset password here
            </v-btn>
          </v-flex>
          <v-flex xs12>
            <v-btn
              color="primary"
              class="ff-login__button mt-2 mb-2"
              type="submit"
              :loading="isLoading"
            >
              Login
            </v-btn>
          </v-flex>
          <v-flex>
            <p class="mb-1 mt-2">
              Not a member ?
            </p>
            <p>
              Sign up as a
              <a
                href="#register-customer"
                @click.prevent="registerAs('customer')"
              >Customer</a>
              or
              <a
                href="#register-supplier"
                @click.prevent="registerAs('supplier')"
              >Join our fleet</a>
            </p>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <slot />
  </v-form>
</template>

<script>
// TODO: move to core-ui
import MapValueKeysToData from '~/mixins/MapValueKeysToData'

export const DEFAULT_VALUE = {
  email: '',
  password: ''
}
export default {
  mixins: [MapValueKeysToData],
  props: {
    value: { type: Object, default: () => DEFAULT_VALUE },
    logo: { type: String },
    title: { type: String },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      ...DEFAULT_VALUE
    }
  },
  methods: {
    registerAs (type) {
      this.$emit('register-as', type)
    },
    passwordForgot () {
      this.$emit('password-forgot')
    }
  }
}
</script>
