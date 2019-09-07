<template>
  <div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { forEach } from 'lodash'

export default {
  layout: 'admin',
  components: {
  },
  data () {
    return {
      pageTitle: 'Square authorization',
      result: null
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    ...mapGetters(['currentUser'])
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
  }
}
</script>

<style scoped>
</style>
