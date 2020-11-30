<template>
  <register-base
    :current-step="stepValue"
    :steps="steps"
  >
    <template
      slot="step-1"
      slot-scope="{ step, num }"
    >
      <v-stepper-content :step="num">
        <v-layout
          align-center
          justify-center
        >
          <register
            :industry-roles="industryRoles"
            class="elevation-0"
            @submit="userData"
          />
        </v-layout>
      </v-stepper-content>
    </template>
    <template
      slot="step-2"
      slot-scope="{ step, num }"
    >
      <v-stepper-content :step="num">
        <policy
          @set-enabled-policy-next="setEnabledPolicyNext"
        />

        <v-btn
          block
          color="success"
          :disabled="enablePolicyNext"
          @click="agreeToPolicy"
        >
          I Agree
        </v-btn>
        <v-btn
          block
          flat
          @click="stepValue = 1"
        >
          Back
        </v-btn>
      </v-stepper-content>
    </template>
    <template
      slot="step-3"
      slot-scope="{ step, num }"
    >
      <v-stepper-content :step="num">
        <v-card>
          <v-card-title primary-title>
            <v-layout
              align-center
              column
              justify-center
            >
              <h3 class="display-1 mb-0 text-center">
                Account Pending
              </h3>
              <p class="subheading text-center">
                Your account is under review
              </p>
            </v-layout>
          </v-card-title>

          <v-card-actions>
            <v-divider />
            <v-btn
              color="accent"
              @click="stepValue = 1"
            >
              Register Another User
            </v-btn>
            <v-divider />
          </v-card-actions>
        </v-card>
      </v-stepper-content>
    </template>
  </register-base>
</template>

<script>
import { mapGetters } from 'vuex'
import RegisterBase from '~/components/FSteps/FSteps.vue'
import Register from 'fresh-bus/components/auth/Register'
import Policy from '~/components/register/Policy.vue'
import omit from 'lodash/omit'

export default {
  meta: {
    auth: false
  },
  components: {
    RegisterBase,
    Register,
    Policy
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$auth.logout({ redirect: false })
    vm.$store.dispatch('page/setLoading', false)
    vm.$store.dispatch('industryRoles/getItems')
  },
  data () {
    return {
      steps: [{
        label: 'Step 1'
      }, {
        label: 'Step 2'
      }, {
        label: 'Step 3'
      }],

      registrationCompleted: false,
      chosenOption: '',
      stepValue: 1,
      id: null,
      user: {},
      enablePolicyNext: true
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('industryRoles', { industryRoles: 'getNames' })
  },
  methods: {
    userData (user) {
      this.user = user
      this.stepValue = 2
    },
    setEnabledPolicyNext (value) {
      this.enablePolicyNext = !value
    },
    agreeToPolicy () {
      this.$auth.register({
        redirect: null,
        method: 'post',
        url: 'register',
        data: {
          ...omit(this.user, ['affiliated', 'company']),
          requested_company: this.user.company
        },
        rememberMe: true
      })
        .then((response) => {
          this.registrationCompleted = true
          this.stepValue = 3
        })
    }
  }
}
</script>
