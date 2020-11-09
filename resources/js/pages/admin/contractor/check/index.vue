<template>
  <div />
</template>

<script>
import { mapActions } from 'vuex'
import get from 'lodash/get'

export default {
  layout: 'admin',
  data () {
    return {
      pageTitle: 'Square authorization'
    }
  },
  methods: {
    get,
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
      })
      .catch((error) => {
        const message = get(error, 'response.data.data[0].detail', error.message)
        vm.$store.dispatch('generalErrorMessages/setErrors', message)
      })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        // by default it is redirecting to this path but if square authorization
        // was to be used elsewhere we would take that path as query params
        vm.$router.push({ path: '/admin/fleet-members' })
        if (next) next()
      })
  }
}
</script>
