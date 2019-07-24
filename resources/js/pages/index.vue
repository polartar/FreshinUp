<template>
  <v-content>
    Welcome to Food Fleet
  </v-content>
</template>

<script>
import { mapActions } from 'vuex'
/**
   * TODO Here until https://zube.io/freshinup/freshplatform/c/185 is complete at which point this can move to the Component definition
   */
const beforeRouteEnterOrUpdate = (vm, to, from, next) => {
  vm.setPageLoading(false)
}

export default {
  beforeRouteEnter (to, from, next) {
    next(vm => {
      beforeRouteEnterOrUpdate(vm, to, from, next)
    })
  },
  beforeRouteUpdate (to, from, next) {
    beforeRouteEnterOrUpdate(this, to, from, next)
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  }
}
</script>

<style scoped>
</style>
