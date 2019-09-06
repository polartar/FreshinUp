<template>
  <div>
    <v-btn href="https://connect.squareup.com/oauth2/authorize?client_id=sq0idp-HRNa4cLFkJNW0ZU4HDM1ug&scope=PAYMENTS_READ">
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
      pageTitle: 'Square authorization'
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
