<template>
  <register-steps
    ref="steps"
    :is-loading="loading"
    @input="createAccount"
    @close="onClose"
  />
</template>

<script>
import RegisterSteps from '~/components/RegisterSteps.vue'
import get from 'lodash/get'

export default {
  meta: {
    auth: false
  },
  layout: 'guest',
  components: {
    RegisterSteps
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$auth.logout({ redirect: false })
    vm.$store.dispatch('page/setLoading', false)
    next && next()
  },
  data () {
    return {
      loading: false
    }
  },
  methods: {
    createAccount (data) {
      this.loading = true
      this.$store.dispatch('users/createCustomerOrSupplier', { data: {
        ...data,
        type: this.typeId
      } })
        .then(responseData => {
        // I am sad that I have to use this twice :(
          this.$refs.steps.step = 3
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
          // I am sad that I have to use this twice :(
          this.$refs.steps.step = 1
        })
        .then(() => {
          this.loading = false
        })
    },
    onClose () {
      this.$router.push({ path: '/' })
    }
  }
}
</script>
