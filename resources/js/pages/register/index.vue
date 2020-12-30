<template>
  <v-container class="ff-register__container">
    <register-steps
      ref="steps"
      :is-loading="loading"
      :type-id="typeId"
      @input="createAccount"
      @close="onClose"
    />
  </v-container>
</template>

<script>
import RegisterSteps from '~/components/RegisterSteps.vue'
import get from 'lodash/get'
import { USER_TYPE } from '../../store/modules/userTypes'

export default {
  meta: {
    layout: 'guest'
  },
  layout: 'guest',
  components: {
    RegisterSteps
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', false)
    next && next()
  },
  data () {
    return {
      loading: false
    }
  },
  computed: {
    typeId () {
      return get(this.$route, 'query.type', 'customer') === 'supplier'
        ? USER_TYPE.SUPPLIER
        : USER_TYPE.CUSTOMER
    }
  },
  methods: {
    createAccount (data) {
      this.loading = true
      this.$store.dispatch('users/createCustomerOrSupplier', {
        data: {
          ...data,
          type: this.typeId
        }
      })
        .then(responseData => {
          // I am sad that I have to use this twice :(
          this.$refs.steps.step = 3
          // no success message because step 3 is a success page
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
      this.$router.push({ path: '/auth' })
    }
  }
}
</script>

<style scoped>
  .ff-register__container {
    max-width: 80rem;
  }
</style>
