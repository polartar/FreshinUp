<template>
  <div>
    <v-btn :href="url">
      Authorize Square
    </v-btn>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  layout: 'admin',
  components: {
  },
  data () {
    return {
      pageTitle: 'Square authorization',
      squareUrl: process.env.SQ_DOMAIN,
      squareAppId: process.env.SQ_APP_ID,
      scopes: [
        'PAYMENTS_READ',
        'CUSTOMERS_READ',
        'EMPLOYEES_READ',
        'INVENTORY_READ',
        'ITEMS_READ',
        'MERCHANT_PROFILE_READ',
        'ORDERS_READ'
      ]
    }
  },
  computed: {
    url () {
      return this.squareUrl + '/oauth2/authorize?client_id=' + this.squareAppId + '&scope=' + this.scopes.join(' ')
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    ...mapGetters(['currentUser']),
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(false)
    Promise.all([
      vm.$store.dispatch('currentUser/getCurrentUser')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>

<style scoped>
</style>
