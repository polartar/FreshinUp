<template>
  <div />
</template>

<script>
import { mapActions } from 'vuex'
import { forEach } from 'lodash'

export default {
  layout: 'admin',
  data () {
    return {
      pageTitle: 'Square authorization',
      result: false
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(false)
    const data = {
      code: vm.$route.query.code
    }
    vm.$store.dispatch('squares/authorize', { data })
      .then(() => {
        vm.result = true
      })
      .catch(() => {
        vm.result = false
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
