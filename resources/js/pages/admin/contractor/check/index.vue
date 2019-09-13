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
    let data = {}
    forEach(vm.$route.query, function (value, key) {
      if (key === 'code') {
        data['code'] = value
      }
    })
    vm.$store.dispatch('squares/createItem', { data }).then((result) => {
      vm.result = true
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
      .catch(() => {
        vm.result = false
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>

<style scoped>
</style>
