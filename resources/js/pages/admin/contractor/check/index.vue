<template>
  <div />
</template>

<script>
import { mapActions } from 'vuex'

export default {
  layout: 'admin',
  data () {
    return {
      pageTitle: 'Square authorization'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const data = {
      code: vm.$route.query.code
    }
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('squares/authorize', { data })
      .then(() => {
        vm.$store.dispatch('generalMessage/setMessage', 'Square account is now connected to this Fleet Member')
        vm.$router.push({ path: '/admin/fleet-members' })
        // This will reload the user with the
        vm.$store.dispatch('currentUser/getCurrentUser')
      })
      .catch((error) => {
        console.error(error)
      })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>

<style scoped>
</style>
