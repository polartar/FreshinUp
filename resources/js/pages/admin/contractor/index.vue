<template>
  <div>
    <v-btn :href="url">
      Authorize Square
    </v-btn>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  layout: 'admin',
  data () {
    return {
      pageTitle: 'Square authorization',
      scopes: [
        'PAYMENTS_READ',
        'CUSTOMERS_READ',
        'EMPLOYEES_READ',
        'INVENTORY_READ',
        'ITEMS_READ',
        'MERCHANT_PROFILE_READ',
        'ORDERS_READ',
        // 'SETTLEMENTS_READ',
        // 'BANK_ACCOUNTS_READ',
        // 'CUSTOMERS_WRITE',
        // 'PAYMENTS_WRITE_ADDITIONAL_RECIPIENTS', 'PAYMENTS_WRITE', 'PAYMENTS_READ'
        // Let’s look at the scope that we set for this walkthrough:
        //
        // MERCHANT_PROFILE_READ enables calls to List Merchants and Retrieve Merchant endpoints. It is useful to be able to call these endpoints so you can verify seller information tied to the OAuth token.
        //
        // PAYMENTS_WRITE and PAYMENTS_READ enable you to create, cancel, and retrieve payments using the Payments API. Later in our walkthrough, you call Create Payment with an OAuth token.
        //
        // PAYMENTS_WRITE_ADDITIONAL_RECIPIENTS enables you to add an application fee to a payment. The application fee is taken from the payment taken on behalf of the seller and added to your developer account. The seller gets the balance of the payment (less the Square transaction fee). The application fee is specified as part of a Create Payment call.
      ]
    }
  },
  computed: {
    url () {
      const BASE_URL = (process.env.SQUARE_ENVIRONMENT === 'production')
        ? 'https://connect.squareup.com'
        : 'https://connect.squareupsandbox.com'

      const params = {
        client_id: process.env.SQUARE_APP_ID,
        scope: this.scopes.join('+'),
        // session: false,
      }
      const queryParams = Object.keys(params).map(key => `${key}=${params[key]}`).join('&')
      return `${BASE_URL}/oauth2/authorize?${queryParams}`
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    if (!process.env.SQUARE_APP_ID) {
      this.$store.dispatch('generalErrorMessages/setErrors', 'Unable to find square application id')
      return false
    }
    if (!process.env.SQUARE_ENVIRONMENT) {
      this.$store.dispatch('generalErrorMessages/setErrors', 'Unable to find square environment')
      return false
    }
    vm.setPageLoading(false)
    next && next()
  }
}
</script>

<style scoped>
</style>
